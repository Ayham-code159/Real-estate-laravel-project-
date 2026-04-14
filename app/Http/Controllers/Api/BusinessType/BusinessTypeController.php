<?php

namespace App\Http\Controllers\Api\BusinessType;

use App\Models\BusinessType;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class BusinessTypeController extends Controller
{
    public function index(): JsonResponse
    {
        $orderColumn = app()->getLocale() === 'ar' ? 'name_ar' : 'name_en';

        $businessTypes = BusinessType::query()
            ->orderBy($orderColumn)
            ->get();

        return response()->json([
            'business_types' => $businessTypes,
        ]);
    }
}
