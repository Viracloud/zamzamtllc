@extends('admin.layouts.master')
@section('content')
<div class="page-heading">
    <h1>Support &amp; improve</h1>
    <p>Need help with the website, or have an idea to make it better? Message us on WhatsApp or send an email.</p>
</div>

<div class="row">
    <div class="col-12 col-lg-6 col-xl-5">
        <div class="card-custom-p">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="icon-box bg-green-100 text-green-600"><i class="fa-brands fa-whatsapp"></i></div>
                <div>
                    <h2 class="section-title mb-0">Support</h2>
                    <p class="text-sm text-gray-500 mb-0">WhatsApp</p>
                </div>
            </div>

            @if($link)
                <p class="text-2xl fw-bold text-gray-900 mb-3" dir="ltr" style="text-align: left">{{ $number }}</p>
                <a href="{{ $link }}" target="_blank" rel="noopener" class="btn-admin-primary d-inline-block text-decoration-none">
                    <i class="fa-brands fa-whatsapp me-1"></i> Chat on WhatsApp
                </a>
            @else
                <p class="text-sm text-gray-500 mb-0">The support number has not been added yet.</p>
            @endif
        </div>
    </div>

    @if($email)
    <div class="col-12 col-lg-6 col-xl-5 mt-4 mt-lg-0">
        <div class="card-custom-p">
            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="icon-box bg-blue-100 text-primary-600"><i class="fa-solid fa-envelope"></i></div>
                <div>
                    <h2 class="section-title mb-0">Support</h2>
                    <p class="text-sm text-gray-500 mb-0">Email</p>
                </div>
            </div>
            <p class="fw-bold text-gray-900 mb-3" dir="ltr" style="text-align: left; font-size: 1.15rem; overflow-wrap: anywhere">{{ $email }}</p>
            <a href="mailto:{{ $email }}?subject={{ rawurlencode('Website support — ' . $global_setting->company_name) }}" class="btn-admin-primary d-inline-block text-decoration-none">
                <i class="fa-solid fa-paper-plane me-1"></i> Send an email
            </a>
        </div>
    </div>
    @endif
</div>
@endsection
