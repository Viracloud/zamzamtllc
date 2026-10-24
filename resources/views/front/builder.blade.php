@extends('front.layouts.master')

@section('title', $page->meta_title ? cms_text($page->meta_title) : ($page->key === 'home' ? $global_setting->company_name : $page->name . ' | ' . $global_setting->company_name))
@section('description', cms_text($page->meta_description))

@section('content')
    @foreach($sections as $section)
        @includeIf('front.sections.' . $section->type, ['s' => $section, 'page' => $page])
    @endforeach
@endsection
