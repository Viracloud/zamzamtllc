<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\SectionItem;
use App\Models\SitePage;
use App\Support\SectionTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

/**
 * Page builder admin: pages, their sections, and the items inside sections.
 */
class AdminContentController extends Controller
{
    /** URL paths a custom page may not use. */
    private const RESERVED_SLUGS = ['admin', 'services', 'quote', 'apply', 'sitemap', 'assets', 'uploads', 'dist-admin', 'storage', 'up', 'index'];

    // ------------------------------------------------------------------ pages

    public function pages()
    {
        $pages = SitePage::withCount('sections')->orderBy('sort_order')->orderBy('id')->get();
        return view('admin.content.pages', compact('pages'));
    }

    public function page_store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:120',
            'slug' => $this->slugRules(),
        ]);

        $page = SitePage::create([
            'key' => 'page-' . Str::lower(Str::random(8)),
            'name' => $data['name'],
            'slug' => $data['slug'],
            'meta_title' => $data['name'] . ' | {company_name}',
            'sort_order' => (int) SitePage::max('sort_order') + 1,
        ]);
        $this->createSection($page->key, 'page_banner', ['title' => $data['name']]);

        return redirect()->route('admin_content_page_edit', $page->id)->with('success', 'Page created. Add sections below.');
    }

    public function page_edit($id)
    {
        $page = SitePage::findOrFail($id);
        $sections = $page->sections()->withCount('items')->get();
        $types = SectionTypes::addable();
        return view('admin.content.page', compact('page', 'sections', 'types'));
    }

    public function page_update(Request $request, $id)
    {
        $page = SitePage::findOrFail($id);
        $rules = [
            'name' => 'required|string|max:120',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
        ];
        $hasSlug = $page->slug !== null;
        if ($hasSlug) {
            $rules['slug'] = $this->slugRules($page->id);
        }
        $data = $request->validate($rules);
        $data['is_active'] = $page->key === 'home' ? true : $request->boolean('is_active');

        $page->update($data);
        return redirect()->back()->with('success', 'Page settings saved.');
    }

    public function page_delete($id)
    {
        $page = SitePage::findOrFail($id);
        if ($page->is_system) {
            return redirect()->back()->with('error', 'Built-in pages cannot be deleted; you can hide them instead.');
        }
        Section::where('page', $page->key)->get()->each->delete();
        $page->delete();
        return redirect()->route('admin_content_pages')->with('success', 'Page deleted.');
    }

    // ------------------------------------------------------------------ sections

    public function section_store(Request $request, $pageId)
    {
        $page = SitePage::findOrFail($pageId);
        $request->validate(['type' => ['required', Rule::in(array_keys(SectionTypes::addable()))]]);

        $section = $this->createSection($page->key, $request->type);
        return redirect()->route('admin_content_section_edit', $section->id)->with('success', 'Section added. Edit its content below.');
    }

    public function section_edit($id)
    {
        $section = Section::with('items')->findOrFail($id);
        $page = SitePage::where('key', $section->page)->first();
        return view('admin.content.section', [
            'section' => $section,
            'page' => $page,
            'type' => $section->typeDefinition(),
        ]);
    }

    /** Header, footer and forms. */
    public function global_edit(string $type)
    {
        abort_unless(in_array($type, ['header', 'footer', 'forms'], true), 404);
        $section = Section::firstOrCreate(['page' => 'global', 'type' => $type]);
        return $this->section_edit($section->id);
    }

    public function section_update(Request $request, $id)
    {
        $section = Section::findOrFail($id);
        $fields = $section->typeDefinition()['fields'];

        $request->validate($this->fieldRules($fields, 'fields') + [
            'name' => 'nullable|string|max:120',
            'image_upload' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        foreach ($fields as $name => $def) {
            if ($def[1] === 'image') {
                continue;
            }
            $value = $request->input("fields.$name");
            if ($def[1] === 'toggle') {
                $value = $request->boolean("fields.$name") ? '1' : '';
            }
            $section->setField($name, $value);
        }

        if (array_key_exists('image', $fields)) {
            if ($request->hasFile('image_upload')) {
                $this->deleteUpload($section->image);
                $section->image = $this->storeUpload($request->file('image_upload'), 'section');
            } elseif ($request->boolean('image_remove')) {
                $this->deleteUpload($section->image);
                $section->image = SectionTypes::get($section->type)['defaults']['image'] ?? null;
            }
        }

        if ($section->page !== 'global') {
            $section->name = $request->input('name');
            $section->is_active = $request->boolean('is_active');
        }
        $section->save();

        return redirect()->back()->with('success', 'Section saved.');
    }

    public function section_move($id, string $direction)
    {
        $section = Section::findOrFail($id);
        $this->move($section, Section::where('page', $section->page), $direction);
        return redirect()->back();
    }

    public function section_toggle($id)
    {
        $section = Section::findOrFail($id);
        $section->update(['is_active' => !$section->is_active]);
        return redirect()->back()->with('success', $section->is_active ? 'Section is visible.' : 'Section hidden.');
    }

    public function section_duplicate($id)
    {
        $section = Section::with('items')->findOrFail($id);
        abort_if($section->page === 'global', 404);

        $copy = $section->replicate();
        $copy->name = trim(($section->name ?: $section->typeDefinition()['label']) . ' (copy)');
        $copy->sort_order = $section->sort_order;
        $copy->save();
        foreach ($section->items as $item) {
            $copy->items()->create($item->replicate()->only(array_merge(SectionItem::COLUMNS, ['group', 'is_active', 'sort_order'])));
        }
        $this->normalize(Section::where('page', $section->page));

        return redirect()->back()->with('success', 'Section duplicated.');
    }

    public function section_delete($id)
    {
        $section = Section::with('items')->findOrFail($id);
        abort_if($section->page === 'global', 404);
        $page = SitePage::where('key', $section->page)->first();

        $this->deleteUpload($section->image);
        $section->items->each(fn ($item) => $this->deleteUpload($item->image));
        $section->delete();

        return $page
            ? redirect()->route('admin_content_page_edit', $page->id)->with('success', 'Section deleted.')
            : redirect()->route('admin_content_pages');
    }

    // ------------------------------------------------------------------ items

    public function item_store(Request $request, $sectionId)
    {
        $section = Section::findOrFail($sectionId);
        $groups = $section->typeDefinition()['groups'];
        $request->validate(['group' => ['required', Rule::in(array_keys($groups))]]);
        $group = $request->group;

        $data = $this->validatedItem($request, $groups[$group][2]);
        $section->items()->create($data + [
            'group' => $group,
            'sort_order' => (int) $section->items()->where('group', $group)->max('sort_order') + 1,
        ]);

        return redirect()->to(url()->previous() . '#group-' . $group)->with('success', ucfirst($groups[$group][1]) . ' added.');
    }

    public function item_update(Request $request, $id)
    {
        $item = SectionItem::with('section')->findOrFail($id);
        $groups = $item->section->typeDefinition()['groups'];
        abort_unless(isset($groups[$item->group]), 404);

        $item->update($this->validatedItem($request, $groups[$item->group][2]) + ['is_active' => $request->boolean('is_active')]);
        return redirect()->to(url()->previous() . '#item-' . $item->id)->with('success', 'Saved.');
    }

    public function item_move($id, string $direction)
    {
        $item = SectionItem::findOrFail($id);
        $this->move($item, SectionItem::where('section_id', $item->section_id)->where('group', $item->group), $direction);
        return redirect()->to(url()->previous() . '#item-' . $item->id);
    }

    public function item_delete($id)
    {
        $item = SectionItem::findOrFail($id);
        $this->deleteUpload($item->image);
        $group = $item->group;
        $item->delete();
        return redirect()->to(url()->previous() . '#group-' . $group)->with('success', 'Deleted.');
    }

    // ------------------------------------------------------------------ helpers

    private function createSection(string $pageKey, string $type, array $overrides = []): Section
    {
        $definition = SectionTypes::get($type);
        $section = new Section([
            'page' => $pageKey,
            'type' => $type,
            'sort_order' => (int) Section::where('page', $pageKey)->max('sort_order') + 1,
        ]);
        foreach (array_merge($definition['defaults'] ?? [], $overrides) as $name => $value) {
            $section->setField($name, $value);
        }
        $section->save();
        return $section;
    }

    /** Validation rules for schema fields under the given input prefix. */
    private function fieldRules(array $fields, string $prefix): array
    {
        $rules = [];
        foreach ($fields as $name => $def) {
            $key = "$prefix.$name";
            $rules[$key] = match ($def[1]) {
                'html' => 'nullable|string|max:200000',
                'textarea', 'lines' => 'nullable|string|max:10000',
                'link' => 'nullable|string|max:500',
                'icon' => ['nullable', 'regex:/^fa-[a-z0-9-]+$/'],
                'select' => ['nullable', Rule::in(array_map('strval', array_keys($def[3] ?? [])))],
                'toggle' => 'nullable',
                'image' => 'nullable',
                default => 'nullable|string|max:1000',
            };
        }
        return $rules;
    }

    private function validatedItem(Request $request, array $fields): array
    {
        $request->validate($this->fieldRules($fields, 'item'), [
            'item.icon.regex' => 'The icon must be a Font Awesome name like fa-truck.',
        ]);
        $data = [];
        foreach ($fields as $name => $def) {
            $data[$name] = $request->input("item.$name");
        }
        return $data;
    }

    private function slugRules(?int $ignoreId = null): array
    {
        return [
            'required', 'string', 'max:80', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
            Rule::notIn(self::RESERVED_SLUGS),
            Rule::unique('site_pages', 'slug')->ignore($ignoreId),
        ];
    }

    /** Swap a record with its neighbour inside an ordered set. */
    private function move(Model $record, $siblings, string $direction): void
    {
        $this->normalize(clone $siblings);
        $record->refresh();
        $neighbour = (clone $siblings)
            ->where('sort_order', $direction === 'up' ? '<' : '>', $record->sort_order)
            ->orderBy('sort_order', $direction === 'up' ? 'desc' : 'asc')
            ->first();
        if ($neighbour) {
            [$record->sort_order, $neighbour->sort_order] = [$neighbour->sort_order, $record->sort_order];
            $record->save();
            $neighbour->save();
        }
    }

    private function normalize($siblings): void
    {
        foreach ($siblings->orderBy('sort_order')->orderBy('id')->get() as $i => $row) {
            if ($row->sort_order !== $i + 1) {
                $row->sort_order = $i + 1;
                $row->save();
            }
        }
    }

    private function storeUpload($file, string $prefix): string
    {
        $name = $prefix . '_' . time() . '_' . Str::lower(Str::random(6)) . '.' . $file->extension();
        $file->move(public_path('uploads'), $name);
        return $name;
    }

    /** Only files in public/uploads are deleted; bundled "assets/..." images are kept. */
    private function deleteUpload(?string $file): void
    {
        if ($file && !str_starts_with($file, 'assets/') && file_exists(public_path('uploads/' . $file))) {
            unlink(public_path('uploads/' . $file));
        }
    }
}
