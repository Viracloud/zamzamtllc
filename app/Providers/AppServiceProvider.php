<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Http\Controllers\Front\FrontController;
use App\Models\DriverApplication;
use App\Models\QuoteRequest;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        require_once app_path('helpers.php');
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Skip until the settings table exists, so artisan migrate works on an empty database.
        try {
            $setting = Schema::hasTable('settings') ? Setting::current() : new Setting();
        } catch (\Throwable $e) {
            $setting = new Setting();
        }
        view()->share('global_setting', $setting);

        // Separate limits per form, so sending a quote doesn't block applying (and vice versa).
        foreach (['quote', 'application', 'admin-login'] as $name) {
            RateLimiter::for($name, fn (Request $request) => Limit::perMinute(5)->by($name . '|' . $request->ip()));
        }

        // The quote and application forms appear on several pages.
        View::composer('front.forms.quote', function ($view) {
            $view->with('equipment_options', QuoteRequest::EQUIPMENT);
        });
        View::composer('front.forms.application', function ($view) {
            $view->with([
                'states' => DriverApplication::STATES,
                'experience_options' => DriverApplication::EXPERIENCE,
                'positions' => DriverApplication::POSITIONS,
                'sms_consent_text' => FrontController::smsConsentText(),
            ]);
        });
    }
}
