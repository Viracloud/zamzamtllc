<?php

namespace Database\Seeders;

/** Starter legal text (a template; have it reviewed before going live). */
class LegalContent
{
    /** @return array<int, array{0: string, 1: string, 2: string}> [slug, title, html] */
    public static function pages(): array
    {
        return [
            ['privacy-policy', 'Privacy Policy', <<<'HTML'
<p>This Privacy Policy explains how {company_name} ("we", "us") collects and uses information submitted through this website.</p>
<h2>Information we collect</h2>
<p>When you request a quote or apply to drive with us, we collect the details you enter in the form, such as your name, company, email address, phone number, lane and load details, state and driving experience. We also record your IP address and the date of the submission to protect against spam.</p>
<h2>How we use it</h2>
<ul>
<li>To prepare and send freight quotes and to communicate about your shipments.</li>
<li>To review driver applications and contact applicants.</li>
<li>To send recruiting text messages, only if you gave consent.</li>
</ul>
<h2>Sharing</h2>
<p>We do not sell your personal information. Mobile phone numbers and SMS opt-in consent are never shared with third parties or affiliates for marketing purposes. We may share information with service providers who help us operate our business, or when required by law.</p>
<h2>Contact</h2>
<p>Questions about this policy can be sent to us using the contact details at the bottom of this page.</p>
HTML],
            ['terms-of-use', 'Terms of Use', <<<'HTML'
<p>By using this website you agree to these terms.</p>
<h2>Quotes</h2>
<p>Rates provided through this website are estimates based on the information you supply. A load is booked only after a rate confirmation is signed by both parties.</p>
<h2>Website content</h2>
<p>Content on this website is provided for general information. We work to keep it accurate but do not guarantee it is complete or current. Verify our operating authority directly with the FMCSA.</p>
<h2>Limitation of liability</h2>
<p>{company_name} is not liable for any damages arising from the use of this website. Transportation services are governed by the applicable rate confirmation, bill of lading and federal regulations.</p>
HTML],
            ['sms-terms', 'SMS Terms', <<<'HTML'
<p>{company_name} offers recruiting text messages to driver applicants who opt in on our application form.</p>
<ul>
<li><strong>Program:</strong> Recruiting updates about your driver application and open positions.</li>
<li><strong>Message frequency:</strong> Varies.</li>
<li><strong>Cost:</strong> Message and data rates may apply.</li>
<li><strong>Opt out:</strong> Reply <strong>STOP</strong> at any time to unsubscribe. Reply <strong>HELP</strong> for help.</li>
<li><strong>Consent:</strong> Consent to receive text messages is not a condition of employment.</li>
</ul>
<p>Carriers are not liable for delayed or undelivered messages. Mobile information is not shared with third parties or affiliates for marketing or promotional purposes. See our <a href="/privacy-policy">Privacy Policy</a>.</p>
HTML],
        ];
    }
}
