<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SitePage extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_system' => 'boolean',
        'is_active' => 'boolean',
        'noindex' => 'boolean',
    ];

    public function sections()
    {
        return $this->hasMany(Section::class, 'page', 'key')->orderBy('sort_order')->orderBy('id');
    }

    public function url(): string
    {
        return $this->slug ? url($this->slug) : route('home');
    }
}
