<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = [
        'title',
        'description',
        'image',
        'category',
        'event_date',
        'status',
        'sort_order',
        'created_by'
    ];

    protected $casts = [
        'event_date' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getImageUrlAttribute()
    {
        return asset('storage/galleries/' . $this->image);
    }
}