<?php

namespace Database\Seeders;

use App\Models\Section;
use App\Models\Service;
use App\Models\SitePage;
use Illuminate\Database\Seeder;

/**
 * Starter website content (from the design file). Everything here is editable
 * in Admin > Pages & Sections, Services and Header, Footer & Forms.
 * Skips itself when content already exists, so it never overwrites edits.
 */
class ContentSeeder extends Seeder
{
    public function run(): void
    {
        if (SitePage::exists()) {
            $this->command?->warn('Content already exists, skipping ContentSeeder.');
            return;
        }

        $this->services();
        $this->globals();
        $this->pages();
    }

    private function services(): void
    {
        $services = [
            [
                'slug' => 'full-truckload', 'title' => 'Full Truckload (FTL)', 'icon' => 'fa-truck-fast',
                'short' => 'Reliable FTL transportation across the United States.',
                'image' => 'assets/front/img/service-ftl.jpg', 'image_alt' => 'Aerial view of a semi truck on a highway',
                'meta_description' => 'Full truckload (FTL) dry van shipping across all 48 states. One truck, one load, direct from pickup to delivery. Request a rate today.',
                'intro' => "When your freight fills a trailer, or you simply need it moved without stops, our full truckload service dedicates one truck and one driver to your load from pickup to delivery.\n\nThere are no terminal transfers and no extra handling, which means faster transit times and less risk of damage. A dispatcher follows the load the entire way and keeps you updated.",
                'features' => "Dedicated truck and driver for your load\nDirect pickup to delivery with no transfers\n53' dry van, up to 26 standard pallets\nAppointment-based pickups and deliveries\nReal-time updates by text and email\nDigital BOL and POD after delivery",
                'ideal' => "Palletized consumer goods\nRetail and e-commerce replenishment\nManufacturing and industrial freight\nPaper, packaging and building supplies",
            ],
            [
                'slug' => 'dry-van', 'title' => 'Dry Van Transport', 'icon' => 'fa-trailer',
                'short' => 'Safe and secure shipping for a wide range of freight.',
                'image' => 'assets/front/img/service-dryvan.jpg', 'image_alt' => 'Semi truck pulling a dry van trailer',
                'meta_description' => "Dry van trucking for palletized and boxed freight. Enclosed, weather-protected 53' trailers with air-ride and logistics posts.",
                'intro' => "Dry vans are the backbone of American freight. Our enclosed 53' trailers keep your shipment protected from weather, road debris and theft from the moment it is loaded.\n\nEvery trailer is inspected before each trip and equipped with air-ride suspension and logistics posts so loads arrive secured and in the condition they left.",
                'features' => "Enclosed, weather-protected 53' trailers\nAir-ride suspension for gentle handling\nLogistics posts and straps to secure loads\nSealed trailers on request\nPre-trip trailer inspections\nFully insured cargo",
                'ideal' => "Non-perishable food and beverages\nBoxed and palletized dry goods\nElectronics and appliances\nFurniture and household goods",
            ],
            [
                'slug' => 'nationwide-coverage', 'title' => 'Nationwide Coverage', 'icon' => 'fa-map-location-dot',
                'short' => 'We deliver coast to coast, on time, every time.',
                'image' => 'assets/front/img/service-nationwide.jpg', 'image_alt' => 'Truck crossing an open highway in the American West',
                'meta_description' => 'Coast-to-coast truckload service across all 48 contiguous states, with 24/7 dispatch and GPS tracking on every truck.',
                'intro' => "From regional runs to cross-country lanes, we haul freight across all 48 contiguous states. Tell us the lane and we will tell you the rate the same business day.\n\nGPS tracking on every truck and a 24/7 dispatch team mean you always know where your freight is, no matter how far it travels.",
                'features' => "Service in all 48 contiguous states\nRegional and long-haul lanes\nGPS tracking on every truck\n24/7 dispatch support\nElectronic logging (ELD) for HOS compliance\nBroker and shipper friendly",
                'ideal' => "Brokers covering open lanes\nShippers with recurring lanes\nTime-sensitive cross-country loads\nSeasonal and surge capacity",
            ],
        ];
        foreach ($services as $i => $service) {
            Service::create($service + ['sort_order' => $i + 1]);
        }
    }

    private function globals(): void
    {
        $this->section('global', 'header', [
            'button_text' => 'Request a Quote', 'button_link' => '/request-a-quote',
        ], ['menu' => [
            ['title' => 'Home', 'link' => '/'],
            ['title' => 'About Us', 'link' => '/about'],
            ['title' => 'Services', 'link' => '/services', 'value' => 'services'],
            ['title' => 'Equipment', 'link' => '/equipment'],
            ['title' => 'Safety', 'link' => '/safety'],
            ['title' => 'Careers', 'link' => '/careers'],
            ['title' => 'Contact', 'link' => '/contact'],
        ]]);

        $this->section('global', 'footer', [
            'body' => 'Reliable, safe and on-time freight transportation across all 48 states.',
            'authority_line' => 'USDOT {usdot_number}  |  MC-{mc_number}',
            'col1_title' => 'Company', 'col2_title' => 'Services', 'col3_title' => 'Contact',
            'button_text' => 'Request a Quote', 'button_link' => '/request-a-quote',
            'subtitle' => '© {year} {company_name}. All rights reserved.',
        ], [
            'links' => [
                ['title' => 'About Us', 'link' => '/about'],
                ['title' => 'Equipment', 'link' => '/equipment'],
                ['title' => 'Safety', 'link' => '/safety'],
                ['title' => 'Careers', 'link' => '/careers'],
                ['title' => 'Contact', 'link' => '/contact'],
            ],
            'legal' => [
                ['title' => 'Privacy Policy', 'link' => '/privacy-policy'],
                ['title' => 'Terms of Use', 'link' => '/terms-of-use'],
                ['title' => 'SMS Terms', 'link' => '/sms-terms'],
            ],
        ]);

        $this->section('global', 'forms', [
            'quote_title' => 'Request a Quote',
            'quote_subtitle' => 'Takes about one minute.',
            'quote_button' => 'Send Quote Request',
            'quote_success' => 'Thank you! Your quote request was sent. Our dispatch team will reply with a rate the same business day.',
            'app_title' => 'Quick Application',
            'app_subtitle' => 'Two minutes. A recruiter calls you within one business day.',
            'app_button' => 'Send Application',
            'app_success' => 'Thank you for applying! A recruiter will call you within one business day.',
            'sms_consent' => 'I agree to receive recruiting text messages from {company_name}. Message frequency varies. Msg & data rates may apply. Reply STOP to opt out. Consent is not a condition of employment.',
            'service_included' => "What's Included",
            'service_ideal' => 'Ideal For',
            'service_cta_title' => 'Get a rate for your lane',
            'service_cta_text' => 'Send your lane and load details. Our dispatch team replies the same business day.',
            'service_others' => 'Other Services',
        ], [
            'equipment' => $this->titles(['Dry Van', 'Full Truckload', 'Other']),
            'experience' => $this->titles(['Less than 1 year', '1-2 years', '2-5 years', '5+ years']),
            'positions' => $this->titles(['Company Driver', 'Owner-Operator']),
        ]);
    }

    private function pages(): void
    {
        $stats = [
            ['icon' => 'fa-truck-front', 'value' => '{fleet_size}', 'subtitle' => 'Trucks in Fleet'],
            ['icon' => 'fa-clock', 'value' => '{on_time_rate}', 'subtitle' => 'On-Time Delivery'],
            ['icon' => 'fa-shield-halved', 'value' => 'Compliant', 'subtitle' => 'USDOT / MC'],
            ['icon' => 'fa-handshake', 'value' => 'Trusted', 'subtitle' => 'by Brokers & Shippers'],
        ];
        $about = [
            'kicker' => 'About Us', 'title' => 'Driven for a Better Tomorrow',
            'body' => "{company_name} is a family-owned carrier based in {base_location}. We run late-model trucks with experienced drivers, and every load is handled by a dispatcher who knows your freight and answers your calls.\n\nOur mission is simple: pick up on time, deliver safely, and keep our partners informed every mile of the way.",
            'image' => 'assets/front/img/about.jpg', 'badge_title' => 'Since {since_year}', 'badge_text' => 'Serving shippers nationwide',
        ];
        $aboutChecklist = ['checklist' => $this->titles(['24/7 dispatch support', 'Real-time load updates', 'Fully insured freight', 'Broker and shipper friendly'])];
        $cta = ['cta', ['title' => 'Ready to Move Your Freight?', 'subtitle' => "Tell us the lane and we'll send a rate today.", 'button_text' => 'Request a Quote', 'button_link' => '/request-a-quote']];
        $quote = ['kicker' => 'Request a Quote', 'title' => 'Get Your Freight Moving', 'body' => 'Send us your lane and load details. Our dispatch team replies with a competitive rate the same business day.', 'show_contact' => '1'];

        $this->page('home', 'Home', null, '{company_name} | Dry Van & Full Truckload Carrier', '{company_name} provides reliable dry van and full truckload transportation across all 48 states. Request a quote or apply to drive.', [
            ['hero', [
                'kicker' => 'Reliable · Safe · On Time', 'title' => 'ZAM ZAM', 'subtitle' => 'TRUCKING LLC',
                'body' => 'Your Trusted Transportation Partner Across the United States',
                'button_text' => 'Request a Quote', 'button_link' => '/request-a-quote',
                'button2_text' => 'Contact Dispatch', 'button2_link' => '{phone_link}',
                'image' => 'assets/front/img/hero.jpg',
            ], ['features' => [
                ['icon' => 'fa-trailer', 'title' => 'Dry Van', 'subtitle' => 'FTL Solutions'],
                ['icon' => 'fa-map-location-dot', 'title' => 'Nationwide', 'subtitle' => 'All 48 States'],
                ['icon' => 'fa-shield-halved', 'title' => 'Safe & Compliant', 'subtitle' => 'USDOT / MC Authority'],
                ['icon' => 'fa-user-tie', 'title' => 'Professional Drivers', 'subtitle' => 'Committed to Excellence'],
            ]]],
            ['services_intro', [
                'kicker' => 'Our Services', 'title' => 'Moving America Forward',
                'body' => '{company_name} provides reliable, efficient, and cost-effective freight transportation solutions. We work with brokers, shippers, and business partners to keep your supply chain moving.',
                'button_text' => 'Learn More About Our Services', 'button_link' => '/services', 'card_link_text' => 'Learn More',
            ]],
            ['stats', ['image' => 'assets/front/img/stats.jpg'], ['stats' => $stats]],
            ['about', $about + ['button_text' => 'More About Us', 'button_link' => '/about'], $aboutChecklist],
            ['cards', ['kicker' => 'Why Zam Zam', 'title' => 'A Carrier You Can Count On', 'subtitle' => 'Experienced drivers, dependable equipment and a dispatch team that answers your calls.', 'background' => 'gray'], ['cards' => [
                ['icon' => 'fa-truck-front', 'subtitle' => 'Equipment', 'title' => 'Modern, Well-Maintained Fleet', 'body' => "Late-model sleeper tractors and 53' air-ride dry vans, inspected before every trip.", 'link' => '/equipment', 'value' => 'See our equipment'],
                ['icon' => 'fa-shield-halved', 'subtitle' => 'Safety & Compliance', 'title' => 'Licensed, Insured and Easy to Verify', 'body' => 'USDOT / MC authority, full insurance, ELD logs and a DOT drug & alcohol program.', 'link' => '/safety', 'value' => 'View our credentials'],
                ['icon' => 'fa-id-card', 'subtitle' => 'Careers', 'title' => 'Drive with Zam Zam', 'body' => 'Now hiring CDL-A company drivers and owner-operators. Weekly pay and reliable home time.', 'link' => '/careers', 'value' => 'Apply in two minutes'],
            ]]],
            ['quote', $quote + ['background' => 'white']],
        ]);

        $this->page('about', 'About Us', 'about', 'About Us | {company_name}', 'Family-owned dry van carrier based in {base_location}. Late-model trucks, experienced drivers and 24/7 dispatch.', [
            ['page_banner', ['title' => 'About Us', 'subtitle' => 'A family-owned carrier built on on-time pickups, safe deliveries and honest communication.']],
            ['about', $about, $aboutChecklist],
            ['stats', ['image' => 'assets/front/img/stats.jpg'], ['stats' => $stats]],
            ['cards', ['kicker' => 'What We Stand For', 'title' => 'Our Commitments', 'background' => 'gray'], ['cards' => [
                ['icon' => 'fa-clock', 'title' => 'On Time, Every Time', 'body' => 'We plan every load around your appointment times and communicate early if anything changes on the road.'],
                ['icon' => 'fa-shield-halved', 'title' => 'Safety First', 'body' => 'Qualified drivers, inspected equipment and full DOT compliance protect your freight and everyone on the road.'],
                ['icon' => 'fa-headset', 'title' => 'Real People, Real Answers', 'body' => 'A dispatcher who knows your freight answers your calls 24/7 and sends updates by text and email.'],
            ]]],
            $cta,
        ]);

        $this->page('services', 'Services', 'services', 'Trucking Services: FTL, Dry Van & Nationwide | {company_name}', 'Full truckload and dry van freight transportation across all 48 states. Same-day rates from a carrier brokers and shippers trust.', [
            ['page_banner', ['title' => 'Our Services', 'subtitle' => 'Reliable, efficient and cost-effective freight transportation for brokers, shippers and business partners.']],
            ['services_list', ['kicker' => 'Service', 'button_text' => 'Learn More', 'feature_count' => '4']],
            $cta,
        ]);

        $this->page('service', 'Service pages (below every service)', null, null, null, [$cta]);

        $this->page('equipment', 'Equipment', 'equipment', 'Equipment & Fleet | {company_name}', "Late-model sleeper tractors and 53' air-ride dry van trailers with GPS tracking and electronic logging on every truck.", [
            ['page_banner', ['title' => 'Equipment', 'subtitle' => "Late-model tractors and 53' dry van trailers, inspected before every trip and serviced on schedule."]],
            ['equipment', ['kicker' => 'Equipment', 'title' => 'Modern, Well-Maintained Fleet', 'subtitle' => "Late-model tractors and 53' dry van trailers, inspected before every trip and serviced on schedule.", 'background' => 'gray'], ['cards' => [
                ['icon' => 'fa-trailer', 'title' => "53' Dry Van Trailers", 'body' => "Length | 53 ft\nMax payload | {max_payload}\nPallet capacity | 26 standard\nFeatures | Air-ride, logistics posts"],
                ['icon' => 'fa-truck-front', 'title' => 'Sleeper Tractors', 'body' => "Models | {tractor_models}\nModel years | {model_years}\nConfiguration | Sleeper cab\nService | Mileage-based PM"],
                ['icon' => 'fa-satellite-dish', 'title' => 'Technology & Tracking', 'body' => "ELD | {eld_provider}\nGPS tracking | Every truck\nLoad updates | Text & email\nDocuments | Digital BOL / POD"],
            ]]],
            ['stats', ['image' => 'assets/front/img/stats.jpg'], ['stats' => $stats]],
            $cta,
        ]);

        $this->page('safety', 'Safety', 'safety', 'Safety & Compliance | {company_name}', 'Verify our USDOT and MC operating authority and insurance. DOT drug & alcohol program, ELD hours of service and preventive maintenance.', [
            ['page_banner', ['title' => 'Safety & Compliance', 'subtitle' => 'Safety is part of every load we move. Verify our authority and safety record directly with the FMCSA.']],
            ['safety', [
                'kicker' => 'Safety & Compliance', 'title' => 'Licensed, Insured and Easy to Verify',
                'body' => 'Safety is part of every load we move. Verify our operating authority and safety record directly with the FMCSA.',
                'button_text' => 'View our FMCSA SAFER profile',
            ], [
                'authority' => [
                    ['title' => 'USDOT Number', 'value' => '{usdot_number}'],
                    ['title' => 'MC Number', 'value' => 'MC-{mc_number}'],
                    ['title' => 'Auto Liability', 'value' => '{auto_liability}'],
                    ['title' => 'Cargo Insurance', 'value' => '{cargo_insurance}'],
                ],
                'cards' => [
                    ['icon' => 'fa-flask-vial', 'title' => 'Drug & Alcohol Program', 'body' => 'DOT pre-employment and random testing, with FMCSA Clearinghouse checks.'],
                    ['icon' => 'fa-clipboard-check', 'title' => 'ELD & Hours of Service', 'body' => 'Every truck logs hours electronically for full HOS compliance.'],
                    ['icon' => 'fa-screwdriver-wrench', 'title' => 'Preventive Maintenance', 'body' => 'Pre-trip and post-trip inspections plus scheduled service.'],
                    ['icon' => 'fa-id-card', 'title' => 'Qualified Drivers', 'body' => 'Full qualification files, MVR reviews and road tests for every driver.'],
                ],
            ]],
            $cta,
        ]);

        $this->page('careers', 'Careers', 'careers', 'CDL-A Driver Jobs | {company_name}', 'Now hiring CDL-A company drivers and owner-operators. Weekly pay, late-model trucks, {owner_operator_percentage} of line haul for owner-operators. Apply in two minutes.', [
            ['page_banner', ['title' => 'Careers', 'subtitle' => 'Now hiring CDL-A company drivers and owner-operators. Clear pay, respect and reliable home time.']],
            ['careers', ['kicker' => 'Careers', 'title' => 'Drive with Zam Zam', 'body' => 'Now hiring CDL-A company drivers and owner-operators. Clear pay, respect and reliable home time.', 'requirements_title' => 'Requirements'], [
                'pay' => [
                    ['title' => 'Company Driver', 'value' => '{driver_pay}', 'subtitle' => '/ mile', 'body' => "Home time {driver_home_time}\nLate-model assigned truck\nWeekly pay, direct deposit"],
                    ['title' => 'Owner-Operator', 'value' => '{owner_operator_percentage}', 'subtitle' => 'of line haul', 'body' => "100% fuel surcharge\nNo forced dispatch\nWeekly settlements"],
                ],
                'requirements' => $this->titles(['Valid Class A CDL', '{min_experience}+ year verifiable tractor-trailer experience', 'Current DOT medical card', 'Clean MVR and DOT drug screen']),
            ]],
        ]);

        $this->page('quote', 'Request a Quote', 'request-a-quote', 'Request a Freight Quote | {company_name}', 'Send your lane and load details and get a competitive dry van / full truckload rate the same business day.', [
            ['page_banner', ['title' => 'Request a Quote', 'subtitle' => "Tell us the lane and we'll send a rate today."]],
            ['steps', [], ['steps' => [
                ['title' => 'Send your load details', 'body' => 'Lane, pickup date, weight and any special requirements.'],
                ['title' => 'Get a rate the same day', 'body' => 'Our dispatch team replies with a competitive rate the same business day.'],
                ['title' => 'Book & track', 'body' => 'Confirm the rate and follow your load with real-time updates.'],
            ]]],
            ['quote', $quote + ['background' => 'gray']],
        ]);

        $this->page('contact', 'Contact', 'contact', 'Contact Us | {company_name}', 'Call our 24/7 dispatch at {phone} or email {email}. Office: {address_street}, {address_city}.', [
            ['page_banner', ['title' => 'Contact Us', 'subtitle' => 'Our dispatch team is available 24/7. Call, email or send your load details below.']],
            ['contact_cards', [], ['cards' => [
                ['icon' => 'fa-phone', 'subtitle' => 'Dispatch 24/7', 'title' => '{phone}', 'link' => '{phone_link}'],
                ['icon' => 'fa-envelope', 'subtitle' => 'Email', 'title' => '{email}', 'link' => 'mailto:{email}'],
                ['icon' => 'fa-location-dot', 'subtitle' => 'Office', 'title' => "{address_street}\n{address_city}"],
                ['icon' => 'fa-id-card', 'subtitle' => 'Drivers', 'title' => 'Apply to drive with us', 'link' => '/careers'],
            ]]],
            ['quote', $quote + ['background' => 'gray']],
        ]);

        foreach (LegalContent::pages() as $i => [$slug, $title, $html]) {
            $this->page($slug, $title, $slug, $title . ' | {company_name}', $title . ' for {company_name}.', [
                ['page_banner', ['title' => $title]],
                ['rich_text', ['body' => $html]],
            ], false, 100 + $i);
        }
    }

    private function page(string $key, string $name, ?string $slug, ?string $metaTitle, ?string $metaDescription, array $sections, bool $system = true, int $order = 0): void
    {
        static $sort = 0;
        SitePage::create([
            'key' => $key, 'name' => $name, 'slug' => $slug,
            'meta_title' => $metaTitle, 'meta_description' => $metaDescription,
            'is_system' => $system, 'sort_order' => $order ?: ++$sort,
        ]);
        foreach ($sections as $i => $section) {
            $this->section($key, $section[0], $section[1] ?? [], $section[2] ?? [], $i + 1);
        }
    }

    private function section(string $page, string $type, array $fields, array $groups = [], int $order = 1): void
    {
        $section = new Section(['page' => $page, 'type' => $type, 'sort_order' => $order]);
        foreach ($fields as $name => $value) {
            $section->setField($name, $value);
        }
        $section->save();

        foreach ($groups as $group => $items) {
            foreach ($items as $i => $item) {
                $section->items()->create($item + ['group' => $group, 'sort_order' => $i + 1]);
            }
        }
    }

    private function titles(array $titles): array
    {
        return array_map(fn ($t) => ['title' => $t], $titles);
    }
}
