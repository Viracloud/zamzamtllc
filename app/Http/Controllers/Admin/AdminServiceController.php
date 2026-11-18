<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminServiceController extends Controller
{
    public function index()
    {
        $services = Service::orderBy('sort_order')->orderBy('id')->get();
        return view('admin.service.index', compact('services'));
    }

    public function create()
    {
        return view('admin.service.form', ['service' => new Service(['is_active' => true])]);
    }

    public function store(Request $request)
    {
        $service = new Service(['sort_order' => (int) Service::max('sort_order') + 1]);
        $this->fill($request, $service);
        return redirect()->route('admin_service_edit', $service->id)->with('success', 'Service created.');
    }

    public function edit($id)
    {
        return view('admin.service.form', ['service' => Service::findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        $this->fill($request, Service::findOrFail($id));
        return redirect()->back()->with('success', 'Service saved.');
    }

    public function move($id, string $direction)
    {
        $services = Service::orderBy('sort_order')->orderBy('id')->get()->values();
        $index = $services->search(fn ($s) => $s->id == $id);
        $swap = $direction === 'up' ? $index - 1 : $index + 1;
        if ($index !== false && isset($services[$swap])) {
            $ordered = $services->all();
            [$ordered[$index], $ordered[$swap]] = [$ordered[$swap], $ordered[$index]];
            foreach ($ordered as $i => $service) {
                $service->update(['sort_order' => $i + 1]);
            }
        }
        return redirect()->back();
    }

    public function delete($id)
    {
        $service = Service::findOrFail($id);
        $this->deleteUpload($service->image);
        $service->delete();
        return redirect()->route('admin_service_index')->with('success', 'Service deleted.');
    }

    private function fill(Request $request, Service $service): void
    {
        $request->merge(['slug' => Str::slug($request->input('slug') ?: $request->input('title'))]);
        $data = $request->validate([
            'title' => 'required|string|max:120',
            'slug' => ['required', 'string', 'max:80', Rule::unique('services', 'slug')->ignore($service->id)],
            'short' => 'nullable|string|max:255',
            'icon' => ['nullable', 'regex:/^fa-[a-z0-9-]+$/'],
            'image_alt' => 'nullable|string|max:255',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'intro' => 'nullable|string|max:10000',
            'features' => 'nullable|string|max:5000',
            'ideal' => 'nullable|string|max:5000',
            'image_upload' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], ['icon.regex' => 'The icon must be a Font Awesome name like fa-truck.']);

        $service->fill(collect($data)->except('image_upload')->all());
        $service->is_active = $request->boolean('is_active');
        $service->noindex = $request->boolean('noindex');

        if ($request->hasFile('image_upload')) {
            $this->deleteUpload($service->image);
            $name = 'service_' . time() . '_' . Str::lower(Str::random(6)) . '.' . $request->file('image_upload')->extension();
            $request->file('image_upload')->move(public_path('uploads'), $name);
            \App\Support\ImageOptimizer::makeWebp(public_path('uploads/' . $name));
            $service->image = $name;
        }
        $service->save();
    }

    private function deleteUpload(?string $file): void
    {
        if ($file && !str_starts_with($file, 'assets/') && file_exists(public_path('uploads/' . $file))) {
            unlink(public_path('uploads/' . $file));
        }
        if ($file && ($webp = \App\Support\ImageOptimizer::webpFor('uploads/' . $file))) {
            unlink(public_path($webp));
        }
    }
}
