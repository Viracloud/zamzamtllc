<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuoteRequest extends Model
{
    public const EQUIPMENT = ['Dry Van', 'Full Truckload', 'Other'];
    public const STATUSES = ['New', 'Contacted', 'Quoted', 'Booked', 'Closed'];

    protected $guarded = ['id'];

    protected $casts = [
        'pickup_date' => 'date',
    ];
}
