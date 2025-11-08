<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FactLike extends Model
{
    protected $table = 'fact_likes';

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
