<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteRequest extends Model
{
    public const EQUIPMENT = ['Dry Van', 'Full Truckload', 'Other'];
    public const STATUSES = ['New', 'Contacted', 'Quoted', 'Booked', 'Closed', 'Spam'];
    public const SPAM = 'Spam';

    /** Phrases that show up in vendor pitches (SEO, web design, marketing…) but never in a freight request. */
    public const SPAM_PHRASES = [
        'seo', 'search engine', 'google ranking', 'rank higher', 'ranking', 'first page', 'page 1 of google', 'top of google',
        'backlink', 'guest post', 'domain authority', 'organic traffic', 'website traffic', 'increase traffic', 'more traffic',
        'web design', 'website design', 'web development', 'website development', 'redesign', 'your website', 'your site',
        'wordpress', 'shopify', 'landing page', 'app development', 'mobile app', 'software development',
        'digital marketing', 'marketing agency', 'marketing services', 'social media', 'lead generation', 'generate leads',
        'more leads', 'ppc', 'google ads', 'facebook ads', 'email marketing', 'cold email', 'appointment setting',
        'outsourc', 'virtual assistant', 'offshore', 'white label', 'our services', 'our agency', 'our team can',
        'free audit', 'free consultation', 'proposal', 'portfolio', 'case studies', 'pricing plan', 'affordable price',
        'dispatch service', 'factoring', 'load board', 'crypto', 'bitcoin', 'loan', 'unsubscribe', 'opt out', 'opt-out',
    ];

    protected $guarded = ['id'];

    protected $casts = [
        'pickup_date' => 'date',
    ];

    public function scopeNotSpam($query)
    {
        return $query->where('status', '!=', self::SPAM);
    }

    /** True when the free-text fields read like a sales pitch rather than a shipment. */
    public static function looksLikeSpam(array $data): bool
    {
        $text = mb_strtolower(implode(' ', array_filter([
            $data['full_name'] ?? '', $data['company'] ?? '', $data['notes'] ?? '',
            $data['pickup_location'] ?? '', $data['delivery_location'] ?? '',
        ])));

        // Links are never needed to ask for a rate.
        if (preg_match('~https?://|www\.|\.(com|net|org|io|co|in|pk|biz)\b~', $text)) {
            return true;
        }

        foreach (self::SPAM_PHRASES as $phrase) {
            if (preg_match('~\b' . preg_quote($phrase, '~') . '~u', $text)) {
                return true;
            }
        }

        return false;
    }
}
