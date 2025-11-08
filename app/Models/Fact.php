<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

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

    private int $id;

    private array $embedding;

    private string $text;

    private string $source;

    private string $category;

    private Carbon $created_at;

    private Carbon $updated_at;


    public function likes(): HasMany
    {
        return $this->hasMany(FactLike::class);
    }

    public function views(): HasMany
    {
        return $this->hasMany(FactView::class);
    }
}
