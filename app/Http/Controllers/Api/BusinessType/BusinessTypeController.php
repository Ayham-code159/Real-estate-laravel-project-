<?php

namespace App\Http\Controllers\Api\BusinessType;

use App\Models\BusinessType;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class BusinessTypeController extends Controller
{
    public function index(): JsonResponse
    {
        $businessTypes = BusinessType::query()
            ->orderBy('name')
            ->get();

        return response()->json([
            'business_types' => $businessTypes,
        ]);
    }
}
