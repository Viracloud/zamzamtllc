<?php

namespace App\Support;

/**
 * Registry of section types for the page builder.
 *
 * Each type maps to the Blade partial resources/views/front/sections/{type}.blade.php
 * and declares:
 *  - fields:  editable section fields, name => [label, input type, help?]
 *             (names in Section::COLUMNS are columns, anything else is stored in "extra")
 *  - groups:  repeatable item lists, key => [label, item noun, fields => [...]]
 *             (item field names must be one of SectionItem::COLUMNS)
 *  - defaults / default_items: content used when the section is newly added.
 *
 * Input types: text, textarea, lines (one per line), html (rich text), icon, image, link, select, toggle.
 */
class SectionTypes
{
    public const TOKEN_HELP = 'You can use placeholders such as {company_name}, {phone}, {usdot_number}.';

    /** Small form & contact-box labels editable under Forms & Labels: key => [admin label, default text]. */
    public const LABELS = [
        'lbl_error'          => ['Both forms: error message', 'Please check the highlighted fields and try again.'],
        'lbl_q_pickup'       => ['Quote form: pickup label', 'Pickup city & state'],
        'lbl_q_pickup_ph'    => ['Quote form: pickup example', 'Chicago, IL'],
        'lbl_q_delivery'     => ['Quote form: delivery label', 'Delivery city & state'],
        'lbl_q_delivery_ph'  => ['Quote form: delivery example', 'Dallas, TX'],
        'lbl_q_date'         => ['Quote form: pickup date label', 'Pickup date'],
        'lbl_q_weight'       => ['Quote form: weight label', 'Weight (lbs)'],
        'lbl_q_equipment'    => ['Quote form: equipment label', 'Equipment'],
        'lbl_q_details'      => ['Quote form: "your details" heading', 'Your details'],
        'lbl_q_name'         => ['Quote form: name label', 'Full name'],
        'lbl_q_company'      => ['Quote form: company label', 'Company'],
        'lbl_q_email'        => ['Quote form: email label', 'Email'],
        'lbl_q_phone'        => ['Quote form: phone label', 'Phone'],
        'lbl_q_notes'        => ['Quote form: notes label', 'Commodity & notes'],
        'lbl_q_notes_ph'     => ['Quote form: notes example', 'Commodity, pallet count, appointment times, special requirements'],
        'lbl_q_notice'       => ['Quote form: note above the button', "This form is for shippers and brokers requesting freight rates only. Sales, marketing and service offers are filtered out and won't receive a reply."],
        'lbl_a_first'        => ['Application: first name label', 'First name'],
        'lbl_a_last'         => ['Application: last name label', 'Last name'],
        'lbl_a_phone'        => ['Application: mobile label', 'Mobile phone'],
        'lbl_a_email'        => ['Application: email label', 'Email'],
        'lbl_a_state'        => ['Application: state label', 'State'],
        'lbl_a_state_ph'     => ['Application: "choose a state"', 'Choose a state'],
        'lbl_a_exp'          => ['Application: experience label', 'CDL-A experience'],
        'lbl_a_exp_ph'       => ['Application: "choose one"', 'Choose one'],
        'lbl_a_position'     => ['Application: position label', 'Position'],
        'lbl_c_phone'        => ['Contact box: phone label', 'Dispatch 24/7'],
        'lbl_c_email'        => ['Contact box: email label', 'Email'],
        'lbl_c_office'       => ['Contact box: office label', 'Office'],
        'lbl_home'           => ['Breadcrumb: first link', 'Home'],
    ];

    public static function all(): array
    {
        $labelFields = [];
        foreach (self::LABELS as $key => [$label, $default]) {
            $labelFields[$key] = [$label, str_ends_with($key, '_notice') ? 'textarea' : 'text', 'Leave empty to use: ' . $default];
        }

        $background = ['Background', 'select', null, ['white' => 'White', 'gray' => 'Light gray', 'dark' => 'Dark']];

        return [
            // ---------------------------------------------------------------- page content
            'hero' => [
                'label' => 'Hero (large banner)',
                'description' => 'Full-width photo banner with headline, two buttons and a feature strip.',
                'fields' => [
                    'kicker' => ['Small line above the title', 'text'],
                    'title' => ['Title (line 1, white)', 'text'],
                    'subtitle' => ['Title (line 2, accent colour)', 'text'],
                    'body' => ['Tagline', 'textarea'],
                    'button_text' => ['Button 1 text', 'text'],
                    'button_link' => ['Button 1 link', 'link'],
                    'button2_text' => ['Button 2 text', 'text'],
                    'button2_link' => ['Button 2 link', 'link', 'Use {phone_link} to call the dispatch phone.'],
                    'image' => ['Background photo', 'image', 'At least 1920×1080.'],
                ],
                'groups' => [
                    'features' => ['Feature strip', 'feature', ['icon' => ['Icon', 'icon'], 'title' => ['Title', 'text'], 'subtitle' => ['Subtitle', 'text']]],
                ],
                'defaults' => ['kicker' => 'Reliable · Safe · On Time', 'title' => 'ZAM ZAM', 'subtitle' => 'TRUCKING LLC', 'body' => 'Your Trusted Transportation Partner Across the United States', 'button_text' => 'Request a Quote', 'button_link' => '/request-a-quote', 'button2_text' => 'Contact Dispatch', 'button2_link' => '{phone_link}', 'image' => 'assets/front/img/hero.jpg'],
            ],

            'page_banner' => [
                'label' => 'Page banner',
                'description' => 'Title banner at the top of an inner page, with breadcrumb.',
                'fields' => [
                    'title' => ['Title', 'text'],
                    'subtitle' => ['Subtitle', 'textarea'],
                    'image' => ['Background photo', 'image', 'Leave empty to use the home hero photo.'],
                ],
                'groups' => [],
                'defaults' => ['title' => 'New Page'],
            ],

            'services_intro' => [
                'label' => 'Services teaser',
                'description' => 'Intro text with the service cards (cards come from Services).',
                'fields' => [
                    'kicker' => ['Kicker', 'text'],
                    'title' => ['Title', 'text'],
                    'body' => ['Text', 'textarea', self::TOKEN_HELP],
                    'button_text' => ['Button text', 'text'],
                    'button_link' => ['Button link', 'link'],
                    'card_link_text' => ['Card link text', 'text'],
                ],
                'groups' => [],
                'defaults' => ['kicker' => 'Our Services', 'title' => 'Moving America Forward', 'body' => '{company_name} provides reliable, efficient, and cost-effective freight transportation solutions.', 'button_text' => 'Learn More About Our Services', 'button_link' => '/services', 'card_link_text' => 'Learn More'],
            ],

            'services_list' => [
                'label' => 'Services list (detailed)',
                'description' => 'Every service as a large row with photo, text and highlights (content from Services).',
                'fields' => [
                    'kicker' => ['Small label above each service', 'text'],
                    'button_text' => ['Button text', 'text'],
                    'feature_count' => ['Highlights shown per service', 'select', null, ['2' => '2', '4' => '4', '6' => '6']],
                ],
                'groups' => [],
                'defaults' => ['kicker' => 'Service', 'button_text' => 'Learn More', 'feature_count' => '4'],
            ],

            'stats' => [
                'label' => 'Stats band',
                'description' => 'Dark photo band with key numbers.',
                'fields' => [
                    'image' => ['Background photo', 'image'],
                ],
                'groups' => [
                    'stats' => ['Stats', 'stat', ['icon' => ['Icon', 'icon'], 'value' => ['Number / word', 'text', 'e.g. {fleet_size} or 100%'], 'subtitle' => ['Label', 'text']]],
                ],
                'defaults' => ['image' => 'assets/front/img/stats.jpg'],
            ],

            'about' => [
                'label' => 'About (photo + text)',
                'description' => 'Photo with a badge, text and a checklist.',
                'fields' => [
                    'kicker' => ['Kicker', 'text'],
                    'title' => ['Title', 'text'],
                    'body' => ['Text', 'textarea', 'Separate paragraphs with an empty line. ' . self::TOKEN_HELP],
                    'image' => ['Photo', 'image'],
                    'badge_title' => ['Badge title', 'text', 'e.g. Since {since_year}'],
                    'badge_text' => ['Badge text', 'text'],
                    'button_text' => ['Button text (optional)', 'text'],
                    'button_link' => ['Button link', 'link'],
                ],
                'groups' => [
                    'checklist' => ['Checklist', 'point', ['title' => ['Text', 'text']]],
                ],
                'defaults' => ['kicker' => 'About Us', 'title' => 'Driven for a Better Tomorrow', 'image' => 'assets/front/img/about.jpg', 'badge_title' => 'Since {since_year}', 'badge_text' => 'Serving shippers nationwide'],
            ],

            'text_image' => [
                'label' => 'Text with image',
                'description' => 'General content block: text and a photo side by side.',
                'fields' => [
                    'kicker' => ['Kicker', 'text'],
                    'title' => ['Title', 'text'],
                    'body' => ['Text', 'textarea', 'Separate paragraphs with an empty line. ' . self::TOKEN_HELP],
                    'image' => ['Photo', 'image'],
                    'image_position' => ['Photo position', 'select', null, ['left' => 'Left', 'right' => 'Right']],
                    'background' => $background,
                    'button_text' => ['Button text (optional)', 'text'],
                    'button_link' => ['Button link', 'link'],
                ],
                'groups' => [
                    'checklist' => ['Checklist (optional)', 'point', ['title' => ['Text', 'text']]],
                ],
                'defaults' => ['kicker' => 'Kicker', 'title' => 'New Section', 'body' => 'Write your text here.', 'image' => 'assets/front/img/about.jpg', 'image_position' => 'left', 'background' => 'white'],
            ],

            'cards' => [
                'label' => 'Cards grid',
                'description' => 'Heading with a grid of icon cards (optionally linked).',
                'fields' => [
                    'kicker' => ['Kicker', 'text'],
                    'title' => ['Title', 'text'],
                    'subtitle' => ['Intro text', 'textarea'],
                    'background' => $background,
                ],
                'groups' => [
                    'cards' => ['Cards', 'card', [
                        'icon' => ['Icon', 'icon'],
                        'subtitle' => ['Small label (optional)', 'text'],
                        'title' => ['Title', 'text'],
                        'body' => ['Text', 'textarea'],
                        'link' => ['Link (optional)', 'link'],
                        'value' => ['Link text', 'text'],
                    ]],
                ],
                'defaults' => ['kicker' => 'Kicker', 'title' => 'New Cards Section', 'background' => 'gray'],
            ],

            'equipment' => [
                'label' => 'Equipment / spec cards',
                'description' => 'Cards with a list of specifications.',
                'fields' => [
                    'kicker' => ['Kicker', 'text'],
                    'title' => ['Title', 'text'],
                    'subtitle' => ['Intro text', 'textarea'],
                    'background' => $background,
                ],
                'groups' => [
                    'cards' => ['Spec cards', 'card', [
                        'icon' => ['Icon', 'icon'],
                        'title' => ['Title', 'text'],
                        'body' => ['Specifications', 'lines', 'One per line as "Label | Value", e.g. Max payload | {max_payload}'],
                    ]],
                ],
                'defaults' => ['kicker' => 'Equipment', 'title' => 'Modern, Well-Maintained Fleet', 'background' => 'gray'],
            ],

            'safety' => [
                'label' => 'Safety & authority',
                'description' => 'Authority numbers and safety cards.',
                'fields' => [
                    'kicker' => ['Kicker', 'text'],
                    'title' => ['Title', 'text'],
                    'body' => ['Text', 'textarea'],
                    'button_text' => ['FMCSA link text', 'text', 'Shown only when a USDOT number or FMCSA URL is set in Company Settings.'],
                    'background' => $background,
                ],
                'groups' => [
                    'authority' => ['Authority boxes', 'box', ['title' => ['Label', 'text'], 'value' => ['Value', 'text', 'e.g. {usdot_number}']]],
                    'cards' => ['Safety cards', 'card', ['icon' => ['Icon', 'icon'], 'title' => ['Title', 'text'], 'body' => ['Text', 'textarea']]],
                ],
                'defaults' => ['kicker' => 'Safety & Compliance', 'title' => 'Licensed, Insured and Easy to Verify', 'button_text' => 'View our FMCSA SAFER profile', 'background' => 'white'],
            ],

            'quote' => [
                'label' => 'Quote form',
                'description' => 'Intro text, contact box and the "Request a Quote" form (form texts are in Header, Footer & Forms).',
                'fields' => [
                    'kicker' => ['Kicker', 'text'],
                    'title' => ['Title', 'text'],
                    'body' => ['Text', 'textarea'],
                    'show_contact' => ['Show contact box', 'toggle'],
                    'background' => $background,
                ],
                'groups' => [],
                'defaults' => ['kicker' => 'Request a Quote', 'title' => 'Get Your Freight Moving', 'body' => 'Send us your lane and load details. Our dispatch team replies with a competitive rate the same business day.', 'show_contact' => '1', 'background' => 'white'],
            ],

            'careers' => [
                'label' => 'Careers + application form',
                'description' => 'Pay cards, requirements and the driver application form.',
                'fields' => [
                    'kicker' => ['Kicker', 'text'],
                    'title' => ['Title', 'text'],
                    'body' => ['Text', 'textarea'],
                    'requirements_title' => ['Requirements heading', 'text'],
                ],
                'groups' => [
                    'pay' => ['Pay cards', 'pay card', [
                        'title' => ['Title', 'text'],
                        'value' => ['Pay', 'text', 'e.g. {driver_pay}'],
                        'subtitle' => ['Pay unit', 'text', 'e.g. / mile'],
                        'body' => ['Benefits', 'lines', 'One per line.'],
                    ]],
                    'requirements' => ['Requirements', 'requirement', ['title' => ['Text', 'text']]],
                ],
                'defaults' => ['kicker' => 'Careers', 'title' => 'Drive with Us', 'requirements_title' => 'Requirements'],
            ],

            'steps' => [
                'label' => 'Numbered steps',
                'description' => 'A row of numbered steps.',
                'fields' => [
                    'kicker' => ['Kicker (optional)', 'text'],
                    'title' => ['Title (optional)', 'text'],
                ],
                'groups' => [
                    'steps' => ['Steps', 'step', ['title' => ['Title', 'text'], 'body' => ['Text', 'textarea']]],
                ],
                'defaults' => [],
            ],

            'contact_cards' => [
                'label' => 'Contact cards',
                'description' => 'Row of contact cards (phone, email, office…).',
                'fields' => [],
                'groups' => [
                    'cards' => ['Cards', 'card', [
                        'icon' => ['Icon', 'icon'],
                        'subtitle' => ['Label', 'text'],
                        'title' => ['Value', 'textarea', 'e.g. {phone}'],
                        'link' => ['Link (optional)', 'link', 'e.g. {phone_link} or mailto:{email}'],
                    ]],
                ],
                'defaults' => [],
            ],

            'cta' => [
                'label' => 'Call-to-action band',
                'description' => 'Accent-coloured band with a headline and button.',
                'fields' => [
                    'title' => ['Title', 'text'],
                    'subtitle' => ['Text', 'text'],
                    'button_text' => ['Button text', 'text'],
                    'button_link' => ['Button link', 'link'],
                ],
                'groups' => [],
                'defaults' => ['title' => 'Ready to Move Your Freight?', 'subtitle' => "Tell us the lane and we'll send a rate today.", 'button_text' => 'Request a Quote', 'button_link' => '/request-a-quote'],
            ],

            'rich_text' => [
                'label' => 'Rich text',
                'description' => 'Free-form formatted text (headings, lists, links).',
                'fields' => [
                    'title' => ['Heading (optional)', 'text'],
                    'body' => ['Content', 'html'],
                ],
                'groups' => [],
                'defaults' => ['body' => '<p>Write your content here.</p>'],
            ],

            // ---------------------------------------------------------------- global (one each, not addable)
            'header' => [
                'label' => 'Header & menu',
                'global' => true,
                'fields' => [
                    'button_text' => ['Header button text', 'text'],
                    'button_link' => ['Header button link', 'link'],
                ],
                'groups' => [
                    'menu' => ['Menu links', 'link', [
                        'title' => ['Label', 'text'],
                        'link' => ['Link', 'link'],
                        'value' => ['Dropdown', 'select', null, ['' => 'None', 'services' => 'List of services']],
                    ]],
                ],
            ],

            'footer' => [
                'label' => 'Footer',
                'global' => true,
                'fields' => [
                    'body' => ['About text', 'textarea'],
                    'authority_line' => ['Authority line', 'text', self::TOKEN_HELP],
                    'col1_title' => ['Column 1 heading', 'text'],
                    'col2_title' => ['Column 2 heading (services)', 'text'],
                    'button_text' => ['Extra link under services', 'text'],
                    'button_link' => ['Extra link URL', 'link'],
                    'col3_title' => ['Column 3 heading (contact)', 'text'],
                    'subtitle' => ['Copyright line', 'text', 'Use {year} for the current year.'],
                ],
                'groups' => [
                    'links' => ['Column 1 links', 'link', ['title' => ['Label', 'text'], 'link' => ['Link', 'link']]],
                    'legal' => ['Bottom links', 'link', ['title' => ['Label', 'text'], 'link' => ['Link', 'link']]],
                ],
            ],

            'forms' => [
                'label' => 'Forms',
                'global' => true,
                'fields' => [
                    'quote_title' => ['Quote form: title', 'text'],
                    'quote_subtitle' => ['Quote form: subtitle', 'text'],
                    'quote_button' => ['Quote form: button', 'text'],
                    'quote_success' => ['Quote form: thank-you message', 'textarea'],
                    'app_title' => ['Application form: title', 'text'],
                    'app_subtitle' => ['Application form: subtitle', 'text'],
                    'app_button' => ['Application form: button', 'text'],
                    'app_success' => ['Application form: thank-you message', 'textarea'],
                    'sms_consent' => ['SMS consent text', 'textarea', 'Shown next to the opt-in checkbox and stored with each consent. ' . self::TOKEN_HELP],
                    'service_included' => ['Service pages: "included" heading', 'text'],
                    'service_ideal' => ['Service pages: "ideal for" heading', 'text'],
                    'service_cta_title' => ['Service pages: side box title', 'text'],
                    'service_cta_text' => ['Service pages: side box text', 'textarea'],
                    'service_others' => ['Service pages: "other services" heading', 'text'],
                ] + $labelFields,
                'groups' => [
                    'equipment' => ['Quote form: equipment options', 'option', ['title' => ['Option', 'text']]],
                    'experience' => ['Application: experience options', 'option', ['title' => ['Option', 'text']]],
                    'positions' => ['Application: positions', 'option', ['title' => ['Option', 'text']]],
                ],
            ],
        ];
    }

    public static function get(string $type): ?array
    {
        return self::all()[$type] ?? null;
    }

    /** Types an admin can add to a page. */
    public static function addable(): array
    {
        return array_filter(self::all(), fn ($t) => empty($t['global']));
    }
}
