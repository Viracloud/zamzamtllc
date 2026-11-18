<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\Admin;
use App\Models\DriverApplication;
use App\Models\QuoteRequest;
use App\Models\Setting;
use App\Mail\Websitemail;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', [
            'new_quotes' => QuoteRequest::where('status', 'New')->count(),
            'total_quotes' => QuoteRequest::notSpam()->count(),
            'new_applications' => DriverApplication::where('status', 'New')->count(),
            'total_applications' => DriverApplication::count(),
            'recent_quotes' => QuoteRequest::notSpam()->latest()->take(5)->get(),
            'recent_applications' => DriverApplication::latest()->take(5)->get(),
            'missing_fields' => Setting::current()->missingFields(),
        ]);
    }

    public function login()
    {
        if(Auth::guard('admin')->check()) {
            return redirect()->route('admin_dashboard');
        }
        return view('admin.login');
    }

    public function login_submit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $data = [
            'email' => (string) $request->input('email'),
            'password' => (string) $request->input('password'),
        ];

        if(Auth::guard('admin')->attempt($data)) {
            // New session id after login (prevents session fixation).
            $request->session()->regenerate();
            return redirect()->intended(route('admin_dashboard'))->with('success','Login is successful!');
        } else {
            return redirect()->route('admin_login')->withInput($request->only('email'))->with('error','The information you entered is incorrect! Please try again!');
        }
    }
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin_login')->with('success','Logout is successful!');
    }

    public function forget_password()
    {
        return view('admin.forget-password');
    }

    public function forget_password_submit(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $admin = Admin::where('email', $request->email)->first();
        if ($admin) {
            // Random, single-use, expiring token; only its hash is stored.
            $token = Str::random(64);
            $admin->token = hash('sha256', $token);
            $admin->token_expires_at = now()->addMinutes(self::RESET_MINUTES);
            $admin->save();

            $reset_link = route('admin_reset_password', [$token, $admin->email]);
            $subject = "Password Reset";
            $message = "To reset your password, click the link below. It works once and expires in " . self::RESET_MINUTES . " minutes.<br>";
            $message .= '<a href="' . e($reset_link) . '">' . e($reset_link) . '</a>';
            $message .= "<br><br>If you did not ask for this, ignore this email; your password stays the same.";

            try {
                \Mail::to($admin->email)->send(new Websitemail($subject, $message));
            } catch (\Throwable $e) {
                \Log::warning('Password reset email failed: ' . $e->getMessage());
            }
        }

        // Same answer whether or not the email exists, so it cannot be used to discover admin emails.
        return redirect()->back()->with('success','If that email belongs to an admin account, a password reset link has been sent. Please also check your spam folder.');
    }

    private const RESET_MINUTES = 60;

    /** The admin whose valid, unexpired reset token matches; null otherwise. */
    private function adminForToken(string $token, string $email): ?Admin
    {
        $admin = Admin::where('email', $email)->first();
        if (!$admin || !$admin->token || !$admin->token_expires_at
            || now()->greaterThan($admin->token_expires_at)
            || !hash_equals($admin->token, hash('sha256', $token))) {
            return null;
        }
        return $admin;
    }

    public function reset_password($token,$email)
    {
        if (!$this->adminForToken((string) $token, (string) $email)) {
            return redirect()->route('admin_login')->with('error','This reset link is invalid or has expired. Please request a new one.');
        }
        return view('admin.reset-password', compact('token','email'));
    }

    public function reset_password_submit(Request $request, $token, $email)
    {
        $admin = $this->adminForToken((string) $token, (string) $email);
        if (!$admin) {
            return redirect()->route('admin_login')->with('error','This reset link is invalid or has expired. Please request a new one.');
        }

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'max:255'],
            'confirm_password' => ['required','same:password'],
        ]);

        $admin->password = Hash::make($request->password);
        $admin->token = null;
        $admin->token_expires_at = null;
        $admin->save();

        return redirect()->route('admin_login')->with('success','Password reset is successful. You can login now.');
    }

    public function profile()
    {
        return view('admin.profile');
    }

    public function profile_update(Request $request)
    {
        $admin = Admin::where('id', Auth::guard('admin')->user()->id)->first();

        $request->validate([
            'name' => 'required|string|max:120',
            'email' => ['required', 'email', 'max:160', \Illuminate\Validation\Rule::unique('admins', 'email')->ignore($admin->id)],
        ]);

        if($request->hasFile('photo')) {
            $request->validate([
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Extension from the file's real content, never from the name the browser sent.
            $final_name = 'admin_'.time().'_'.Str::lower(Str::random(6)).'.'.$request->photo->extension();

            if($admin->photo != null && file_exists(public_path('uploads/'.$admin->photo))) {
                unlink(public_path('uploads/'.$admin->photo));
            }

            $request->photo->move(public_path('uploads'), $final_name);
            $admin->photo = $final_name;
        }

        if($request->password != null) {
            $request->validate([
                'password' => ['required', 'string', 'min:8', 'max:255'],
                'confirm_password' => ['required','same:password'],
            ]);
            $admin->password = Hash::make($request->password);        }

        $admin->name = $request->name;
        $admin->email = $request->email;
        $admin->update();

        return redirect()->back()->with('success','Profile update is successful!');
    }
}
