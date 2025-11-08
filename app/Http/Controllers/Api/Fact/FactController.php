<?php

namespace App\Http\Controllers\Api\Fact;

use App\Http\Controllers\Controller;
use App\Http\Resources\FactResource;
use App\Services\Fact\FactService;

class FactController extends Controller
{
    public function __construct(
        private readonly FactService $factService
    )
    {
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        $facts = $this->factService->getAll();

//        return FactResource::collection($facts);
        return response()->json([
            'success' => true,
            'result' => FactResource::collection($facts)->response()->getData(true)
        ]);
    }
}
