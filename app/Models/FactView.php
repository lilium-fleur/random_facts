<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FactView extends Model
{
    protected $table = 'fact_views';

    protected $fillable = ['user_id', 'fact_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function fact(): BelongsTo
    {
        return $this->belongsTo(Fact::class);
    }
}
