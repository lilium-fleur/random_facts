<?php

namespace App\Http\Controllers\Api\Fact;

use App\Http\Controllers\Controller;
use App\Http\Resources\FactResource;
use App\Services\Fact\FactService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FactController extends Controller
{
    public function __construct(
        private readonly FactService $factService
    )
    {
    }

    public function index(): AnonymousResourceCollection
    {
        $facts = $this->factService->getAll();

        return FactResource::collection($facts);
    }
}
