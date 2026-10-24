<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SectionItem extends Model
{
    public const COLUMNS = ['icon', 'title', 'subtitle', 'body', 'value', 'link', 'image'];

    protected $guarded = ['id'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
