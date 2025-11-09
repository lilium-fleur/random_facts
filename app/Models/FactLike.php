<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

class FactLike extends Model
{
    protected $table = 'fact_likes';

    protected $fillable = ['user_id', 'fact_id'];

    private int $id;

    private int $user_id;

    private int $fact_id;

    private Carbon $created_at;

    private Carbon $updated_at;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function fact(): BelongsTo
    {
        return $this->belongsTo(Fact::class);
    }
}
