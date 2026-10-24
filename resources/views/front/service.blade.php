@extends('front.layouts.master')

@php($servicesPage = \App\Models\SitePage::where('key', 'services')->first())

@section('title', $service->meta_title ? cms_text($service->meta_title) : $service->title . ' | ' . $global_setting->company_name)
@section('description', cms_text($service->meta_description ?: $service->short))

@section('content')
    @include('front.sections.page_banner', [
        's' => new \App\Models\Section(['title' => $service->title, 'subtitle' => $service->short, 'image' => $service->image]),
        'crumbs' => $servicesPage ? [$servicesPage->name => $servicesPage->url()] : [],
    ])

    <section class="section">
        <div class="container service-detail">
            <div class="service-main reveal">
                @if($service->image)
                <img class="service-hero-img" src="{{ cms_image($service->image) }}" alt="{{ $service->image_alt }}" width="800" height="520">
                @endif

                @foreach($service->paragraphs() as $paragraph)
                    <p class="{{ $loop->first ? 'lead' : '' }}">{{ cms_text($paragraph) }}</p>
                @endforeach

                @if($service->featureList())
                <h2 class="detail-heading">{{ cms_text(cms_global('forms')->field('service_included', "What's Included")) }}</h2>
                <ul class="check-list">
                    @foreach($service->featureList() as $feature)
                    <li><i class="fa-solid fa-circle-check"></i> {{ cms_text($feature) }}</li>
                    @endforeach
                </ul>
                @endif

                @if($service->idealList())
                <h2 class="detail-heading">{{ cms_text(cms_global('forms')->field('service_ideal', 'Ideal For')) }}</h2>
                <ul class="bullet-list ideal-list">
                    @foreach($service->idealList() as $item)
                    <li>{{ cms_text($item) }}</li>
                    @endforeach
                </ul>
                @endif
            </div>

            <aside class="service-aside">
                @php($header = cms_global('header'))
                <div class="card aside-card aside-cta">
                    <h3>{{ cms_text(cms_global('forms')->field('service_cta_title', 'Get a rate for your lane')) }}</h3>
                    <p>{{ cms_text(cms_global('forms')->field('service_cta_text')) }}</p>
                    <a href="{{ cms_url($header->button_link ?: '/request-a-quote') }}" class="btn btn-primary btn-block">{{ cms_text($header->button_text ?: 'Request a Quote') }}</a>
                    <a href="{{ $global_setting->phoneHref() }}" class="aside-phone"><i class="fa-solid fa-phone"></i> {{ $global_setting->phone }}</a>
                </div>
                @if($others->isNotEmpty())
                <div class="card aside-card">
                    <h3>{{ cms_text(cms_global('forms')->field('service_others', 'Other Services')) }}</h3>
                    <ul class="aside-links">
                        @foreach($others as $other)
                        <li><a href="{{ $other->url() }}">@if($other->icon)<i class="fa-solid {{ $other->icon }}"></i>@endif {{ $other->title }} <i class="fa-solid fa-arrow-right"></i></a></li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </aside>
        </div>
    </section>

    @foreach($sections as $section)
        @includeIf('front.sections.' . $section->type, ['s' => $section, 'page' => null])
    @endforeach
@endsection
