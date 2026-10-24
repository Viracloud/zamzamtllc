<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Mail\Websitemail;
use App\Models\DriverApplication;
use App\Models\QuoteRequest;
use App\Models\Service;
use App\Models\Setting;
use App\Models\SitePage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class FrontController extends Controller
{
    public function home()
    {
        return $this->render(SitePage::where('key', 'home')->firstOrFail());
    }

    /** Any builder page by its URL slug (about, careers, privacy-policy, custom pages…). */
    public function page(string $slug)
    {
        $page = SitePage::where('slug', $slug)->where('is_active', true)->firstOrFail();
        return $this->render($page);
    }

    public function service(string $slug)
    {
        $service = Service::where('slug', $slug)->where('is_active', true)->firstOrFail();
        $others = Service::active()->where('id', '!=', $service->id)->get();
        $page = SitePage::where('key', 'service')->first();
        $sections = $page ? $page->sections()->with('items')->where('is_active', true)->get() : collect();

        return view('front.service', compact('service', 'others', 'sections'));
    }

    public function sitemap()
    {
        $urls = SitePage::where('is_active', true)
            ->where(fn ($q) => $q->whereNotNull('slug')->orWhere('key', 'home'))
            ->orderBy('sort_order')->get()->map->url()
            ->merge(Service::active()->get()->map->url());

        return response()->view('front.sitemap', compact('urls'))->header('Content-Type', 'application/xml');
    }

    private function render(SitePage $page)
    {
        $sections = $page->sections()->with('items')->where('is_active', true)->get();
        return view('front.builder', compact('page', 'sections'));
    }

    public function quote_submit(Request $request)
    {
        if ($this->isBot($request)) {
            return $this->thanks($request, 'quote');
        }

        $data = $this->validateForm($request, 'quote', [
            'pickup_location' => 'required|string|max:120',
            'delivery_location' => 'required|string|max:120',
            'pickup_date' => 'nullable|date|after_or_equal:today',
            'weight' => 'nullable|integer|min:1|max:80000',
            'equipment' => ['required', Rule::in(cms_options('equipment'))],
            'full_name' => 'required|string|max:120',
            'company' => 'nullable|string|max:120',
            'email' => 'required|email|max:160',
            'phone' => 'nullable|string|max:40',
            'notes' => 'nullable|string|max:2000',
        ], [], [
            'pickup_location' => 'pickup city & state',
            'delivery_location' => 'delivery city & state',
            'full_name' => 'full name',
        ]);

        $quote = QuoteRequest::create($data + ['ip_address' => $request->ip()]);

        $this->notify('New quote request: ' . $quote->pickup_location . ' → ' . $quote->delivery_location, [
            'Lane' => $quote->pickup_location . ' → ' . $quote->delivery_location,
            'Pickup date' => $quote->pickup_date?->format('m/d/Y'),
            'Weight (lbs)' => $quote->weight ? number_format($quote->weight) : null,
            'Equipment' => $quote->equipment,
            'Name' => $quote->full_name,
            'Company' => $quote->company,
            'Email' => $quote->email,
            'Phone' => $quote->phone,
            'Notes' => $quote->notes,
        ], route('admin_quote_request_show', $quote->id));

        return $this->thanks($request, 'quote');
    }

    public function application_submit(Request $request)
    {
        if ($this->isBot($request)) {
            return $this->thanks($request, 'application');
        }

        $data = $this->validateForm($request, 'application', [
            'first_name' => 'required|string|max:80',
            'last_name' => 'required|string|max:80',
            'phone' => 'required|string|max:40',
            'email' => 'required|email|max:160',
            'state' => ['required', Rule::in(array_keys(DriverApplication::STATES))],
            'experience' => ['required', Rule::in(cms_options('experience'))],
            'position' => ['required', Rule::in(cms_options('positions'))],
            'sms_consent' => 'nullable|boolean',
        ], [], [
            'first_name' => 'first name',
            'last_name' => 'last name',
            'phone' => 'mobile phone',
            'experience' => 'CDL-A experience',
        ]);

        $consent = $request->boolean('sms_consent');
        $application = DriverApplication::create([
            'sms_consent' => $consent,
            'sms_consent_text' => $consent ? self::smsConsentText() : null,
            'ip_address' => $request->ip(),
        ] + collect($data)->except('sms_consent')->all());

        $this->notify('New driver application: ' . $application->full_name . ' (' . $application->position . ')', [
            'Name' => $application->full_name,
            'Position' => $application->position,
            'CDL-A experience' => $application->experience,
            'State' => DriverApplication::STATES[$application->state] ?? $application->state,
            'Mobile' => $application->phone,
            'Email' => $application->email,
            'SMS consent' => $consent ? 'Yes' : 'No',
        ], route('admin_driver_application_show', $application->id));

        return $this->thanks($request, 'application');
    }

    /** Exact wording shown next to the SMS checkbox; stored with each consent. */
    public static function smsConsentText(): string
    {
        return cms_text(cms_global('forms')->field('sms_consent'));
    }

    /** Validate; on failure return to the form on the page it was sent from. */
    private function validateForm(Request $request, string $form, array $rules, array $messages, array $attributes): array
    {
        $validator = Validator::make($request->all(), $rules, $messages, $attributes);
        if ($validator->fails()) {
            throw (new ValidationException($validator))
                ->errorBag($form)
                ->redirectTo($this->formUrl($request, $form));
        }
        return $validator->validated();
    }

    /** The page the form was sent from (must be an existing page), anchored at the form itself. */
    private function formUrl(Request $request, string $form): string
    {
        $page = SitePage::where('key', (string) $request->input('_return'))->first()
            ?? SitePage::where('key', $form === 'quote' ? 'quote' : 'careers')->first();
        $url = $page ? $page->url() : route('home');
        return $url . ($form === 'quote' ? '#quote-form' : '#apply-form');
    }

    /** Hidden "website" field is invisible to people; bots fill it. */
    private function isBot(Request $request): bool
    {
        return filled($request->input('website'));
    }

    private function thanks(Request $request, string $form)
    {
        $forms = cms_global('forms');
        $message = $form === 'quote' ? $forms->field('quote_success') : $forms->field('app_success');
        return redirect()->to($this->formUrl($request, $form))->with($form . '_success', cms_text($message));
    }

    private function notify(string $subject, array $fields, string $adminUrl): void
    {
        $to = Setting::current()->notificationEmail();
        if (!$to) {
            return;
        }

        $rows = '';
        foreach ($fields as $label => $value) {
            if ($value === null || $value === '') {
                continue;
            }
            $rows .= '<tr><td style="padding:6px 12px;color:#555;vertical-align:top"><strong>' . e($label) . '</strong></td>'
                . '<td style="padding:6px 12px">' . nl2br(e($value)) . '</td></tr>';
        }
        $body = '<h2 style="font-family:Arial,sans-serif">' . e($subject) . '</h2>'
            . '<table style="font-family:Arial,sans-serif;font-size:14px;border-collapse:collapse">' . $rows . '</table>'
            . '<p style="font-family:Arial,sans-serif"><a href="' . e($adminUrl) . '">Open in admin panel</a></p>';

        try {
            Mail::to($to)->send(new Websitemail($subject, $body));
        } catch (\Throwable $e) {
            // The submission is already saved; a mail failure must not lose it.
            Log::warning('Notification email failed: ' . $e->getMessage());
        }
    }
}
