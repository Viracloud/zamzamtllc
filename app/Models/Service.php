<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }

    public function url(): string
    {
        return route('service', $this->slug);
    }

    public function paragraphs(): array
    {
        return cms_paragraphs($this->intro);
    }

    public function featureList(): array
    {
        return cms_lines($this->features);
    }

    public function idealList(): array
    {
        return cms_lines($this->ideal);
    }
}
