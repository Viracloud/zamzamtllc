<?php

namespace App\Models;

use App\Support\SectionTypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Section extends Model
{
    /** Real columns; any other schema field is stored in the "extra" JSON. */
    public const COLUMNS = ['kicker', 'title', 'subtitle', 'body', 'button_text', 'button_link', 'button2_text', 'button2_link', 'image'];

    protected $guarded = ['id'];

    protected $casts = [
        'extra' => 'array',
        'is_active' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(SectionItem::class)->orderBy('sort_order')->orderBy('id');
    }

    /** Value of a schema field, whether it lives in a column or in "extra". */
    public function field(string $name, $default = null)
    {
        if (in_array($name, self::COLUMNS, true)) {
            return $this->{$name} ?? $default;
        }
        return data_get($this->extra, $name, $default);
    }

    public function setField(string $name, $value): void
    {
        if (in_array($name, self::COLUMNS, true)) {
            $this->{$name} = $value;
            return;
        }
        $extra = $this->extra ?? [];
        $extra[$name] = $value;
        $this->extra = $extra;
    }

    /** Active items of one group, in order. */
    public function group(string $group): Collection
    {
        return $this->items->where('group', $group)->where('is_active', true)->values();
    }

    public function typeDefinition(): array
    {
        return SectionTypes::get($this->type) ?? ['label' => $this->type, 'fields' => [], 'groups' => []];
    }

    public function adminLabel(): string
    {
        return $this->name ?: $this->typeDefinition()['label'];
    }
}
