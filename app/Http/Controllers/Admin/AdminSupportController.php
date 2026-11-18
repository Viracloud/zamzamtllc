<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class AdminSupportController extends Controller
{
    /** Read-only page: nothing here can be edited from the admin panel. */
    public function index()
    {
        $number = trim((string) config('support.whatsapp'));
        $digits = preg_replace('/\D/', '', $number);

        $email = trim((string) config('support.email'));

        return view('admin.support', [
            'number' => $number,
            'link' => $digits !== '' ? 'https://wa.me/' . $digits : null,
            'email' => filter_var($email, FILTER_VALIDATE_EMAIL) ? $email : null,
        ]);
    }
}
