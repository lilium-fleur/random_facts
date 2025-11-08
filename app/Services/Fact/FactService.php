<?php

namespace App\Services\Fact;

use App\Models\Fact;

class FactService
{
    public function getAll()
    {
        return Fact::paginate(config('facts.pagination.per_page'));
    }

}
