<?php

namespace App\Http\Controllers\Fact;

use App\Http\Controllers\Controller;
use App\Services\Fact\FactService;
use Illuminate\Contracts\View\View;

class FactController extends Controller
{
    public function __construct(
        private readonly FactService $factService
    )
    {
    }

    public function index(): View  {
        $facts = $this->factService->getAll();

        return view('fact.index', compact('facts'));
    }
}
