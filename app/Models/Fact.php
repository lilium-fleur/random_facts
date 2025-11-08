<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fact extends Model
{
    protected $table = 'facts';

    protected $fillable = [
        'embedding',
        'text',
        'source',
        'category',
    ];

    protected $hidden = [
        'embedding',
    ];
}
