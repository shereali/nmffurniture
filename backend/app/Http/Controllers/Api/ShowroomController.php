<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Showroom;
use Illuminate\Http\JsonResponse;

class ShowroomController extends Controller
{
    /**
     * Get all active showrooms.
     */
    public function index(): JsonResponse
    {
        $showrooms = Showroom::where('is_active', true)->get();

        return response()->json([
            'showrooms' => $showrooms,
        ]);
    }
}
