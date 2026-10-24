<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\FrontController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminQuoteRequestController;
use App\Http\Controllers\Admin\AdminDriverApplicationController;
use App\Http\Controllers\Admin\AdminSettingController;
use App\Http\Controllers\Admin\AdminContentController;
use App\Http\Controllers\Admin\AdminServiceController;

/*
|--------------------------------------------------------------------------
| Front (public) routes
|--------------------------------------------------------------------------
*/
Route::get('/', [FrontController::class, 'home'])->name('home');
Route::get('/services/{slug}', [FrontController::class, 'service'])->name('service');
Route::get('/sitemap.xml', [FrontController::class, 'sitemap'])->name('sitemap');

Route::post('/quote', [FrontController::class, 'quote_submit'])->middleware('throttle:quote')->name('quote_submit');
Route::post('/apply', [FrontController::class, 'application_submit'])->middleware('throttle:application')->name('application_submit');
// Builder pages (/about, /careers, custom pages…) are matched at the end of this file.

/*
|--------------------------------------------------------------------------
| Admin routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    Route::get('/', function () {return redirect('/admin/login');});
    Route::get('/login',[AdminController::class,'login'])->name('admin_login');
    Route::post('/login',[AdminController::class,'login_submit'])->middleware('throttle:admin-login')->name('admin_login_submit');
    Route::get('/logout',[AdminController::class,'logout'])->name('admin_logout');
    Route::get('/forget-password',[AdminController::class,'forget_password'])->name('admin_forget_password');
    Route::post('/forget_password_submit',[AdminController::class,'forget_password_submit'])->middleware('throttle:3,1')->name('admin_forget_password_submit');
    Route::get('/reset-password/{token}/{email}',[AdminController::class,'reset_password'])->name('admin_reset_password');
    Route::post('/reset-password/{token}/{email}',[AdminController::class,'reset_password_submit'])->name('admin_reset_password_submit');
});

Route::middleware('admin')->prefix('admin')->group(function () {
    Route::get('/dashboard',[AdminController::class,'dashboard'])->name('admin_dashboard');
    Route::get('/profile',[AdminController::class,'profile'])->name('admin_profile');
    Route::post('/profile',[AdminController::class,'profile_update'])->name('admin_profile_update');

    // Quote requests
    Route::get('/quote-requests',[AdminQuoteRequestController::class,'index'])->name('admin_quote_request_index');
    Route::get('/quote-requests/export',[AdminQuoteRequestController::class,'export'])->name('admin_quote_request_export');
    Route::get('/quote-requests/{id}',[AdminQuoteRequestController::class,'show'])->name('admin_quote_request_show');
    Route::post('/quote-requests/{id}/status',[AdminQuoteRequestController::class,'change_status'])->name('admin_quote_request_status');
    Route::post('/quote-requests/{id}/delete',[AdminQuoteRequestController::class,'delete'])->name('admin_quote_request_delete');

    // Driver applications
    Route::get('/driver-applications',[AdminDriverApplicationController::class,'index'])->name('admin_driver_application_index');
    Route::get('/driver-applications/export',[AdminDriverApplicationController::class,'export'])->name('admin_driver_application_export');
    Route::get('/driver-applications/{id}',[AdminDriverApplicationController::class,'show'])->name('admin_driver_application_show');
    Route::post('/driver-applications/{id}/status',[AdminDriverApplicationController::class,'change_status'])->name('admin_driver_application_status');
    Route::post('/driver-applications/{id}/delete',[AdminDriverApplicationController::class,'delete'])->name('admin_driver_application_delete');

    // Settings
    Route::get('/setting/company',[AdminSettingController::class,'company'])->name('admin_setting_company');
    Route::post('/setting/company',[AdminSettingController::class,'company_update'])->name('admin_setting_company_update');
    Route::get('/setting/theme',[AdminSettingController::class,'theme'])->name('admin_setting_theme');
    Route::post('/setting/theme',[AdminSettingController::class,'theme_update'])->name('admin_setting_theme_update');
    Route::get('/setting/images',[AdminSettingController::class,'images'])->name('admin_setting_images');
    Route::post('/setting/images',[AdminSettingController::class,'images_update'])->name('admin_setting_images_update');
    Route::post('/setting/images/{field}/reset',[AdminSettingController::class,'image_reset'])->name('admin_setting_image_reset');

    // Website content: pages, sections, items
    Route::get('/content/pages',[AdminContentController::class,'pages'])->name('admin_content_pages');
    Route::post('/content/pages',[AdminContentController::class,'page_store'])->name('admin_content_page_store');
    Route::get('/content/pages/{id}',[AdminContentController::class,'page_edit'])->name('admin_content_page_edit');
    Route::post('/content/pages/{id}',[AdminContentController::class,'page_update'])->name('admin_content_page_update');
    Route::post('/content/pages/{id}/delete',[AdminContentController::class,'page_delete'])->name('admin_content_page_delete');
    Route::post('/content/pages/{id}/sections',[AdminContentController::class,'section_store'])->name('admin_content_section_store');

    Route::get('/content/global/{type}',[AdminContentController::class,'global_edit'])->name('admin_content_global');
    Route::get('/content/sections/{id}',[AdminContentController::class,'section_edit'])->name('admin_content_section_edit');
    Route::post('/content/sections/{id}',[AdminContentController::class,'section_update'])->name('admin_content_section_update');
    Route::post('/content/sections/{id}/move/{direction}',[AdminContentController::class,'section_move'])->whereIn('direction', ['up', 'down'])->name('admin_content_section_move');
    Route::post('/content/sections/{id}/toggle',[AdminContentController::class,'section_toggle'])->name('admin_content_section_toggle');
    Route::post('/content/sections/{id}/duplicate',[AdminContentController::class,'section_duplicate'])->name('admin_content_section_duplicate');
    Route::post('/content/sections/{id}/delete',[AdminContentController::class,'section_delete'])->name('admin_content_section_delete');

    Route::post('/content/sections/{id}/items',[AdminContentController::class,'item_store'])->name('admin_content_item_store');
    Route::post('/content/items/{id}',[AdminContentController::class,'item_update'])->name('admin_content_item_update');
    Route::post('/content/items/{id}/move/{direction}',[AdminContentController::class,'item_move'])->whereIn('direction', ['up', 'down'])->name('admin_content_item_move');
    Route::post('/content/items/{id}/delete',[AdminContentController::class,'item_delete'])->name('admin_content_item_delete');

    // Services
    Route::get('/services',[AdminServiceController::class,'index'])->name('admin_service_index');
    Route::get('/services/create',[AdminServiceController::class,'create'])->name('admin_service_create');
    Route::post('/services',[AdminServiceController::class,'store'])->name('admin_service_store');
    Route::get('/services/{id}',[AdminServiceController::class,'edit'])->name('admin_service_edit');
    Route::post('/services/{id}',[AdminServiceController::class,'update'])->name('admin_service_update');
    Route::post('/services/{id}/move/{direction}',[AdminServiceController::class,'move'])->whereIn('direction', ['up', 'down'])->name('admin_service_move');
    Route::post('/services/{id}/delete',[AdminServiceController::class,'delete'])->name('admin_service_delete');
});

/*
|--------------------------------------------------------------------------
| Builder pages — keep last so they never shadow the routes above
|--------------------------------------------------------------------------
*/
Route::get('/{slug}', [FrontController::class, 'page'])->where('slug', '[a-z0-9]+(?:-[a-z0-9]+)*')->name('page');
