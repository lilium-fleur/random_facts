<?php

namespace App\Services\Fact;

use App\Models\Fact;
use Illuminate\Database\Eloquent\Collection;

class FactService
{

    public function getAll(): Collection
    {
        return Fact::all();
    }

}
