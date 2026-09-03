<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Showroom;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminShowroomController extends Controller
{
    public function index(): JsonResponse
    {
        $showrooms = Showroom::orderBy('id', 'asc')->get();

        return response()->json([
            'showrooms' => $showrooms,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'branch' => ['required', 'string', 'max:150'],
            'address' => ['required', 'string'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'postal_code' => ['required', 'string', 'max:20'],
            'phone' => ['nullable', 'string', 'max:50'],
            'whatsapp_number' => ['required', 'string', 'max:50'],
            'whatsapp_prefill_text' => ['nullable', 'string'],
            'map_embed_url' => ['nullable', 'string'],
            'opening_hours' => ['required', 'string', 'max:255'],
            'image' => ['nullable', 'string', 'url'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $showroom = Showroom::create($validated);

        return response()->json([
            'message' => 'Showroom added successfully',
            'showroom' => $showroom,
        ], 201);
    }

    public function show(int $id): JsonResponse
    {
        $showroom = Showroom::findOrFail($id);

        return response()->json([
            'showroom' => $showroom,
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $showroom = Showroom::findOrFail($id);

        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'branch' => ['sometimes', 'required', 'string', 'max:150'],
            'address' => ['sometimes', 'required', 'string'],
            'city' => ['sometimes', 'required', 'string', 'max:100'],
            'state' => ['sometimes', 'required', 'string', 'max:100'],
            'postal_code' => ['sometimes', 'required', 'string', 'max:20'],
            'phone' => ['nullable', 'string', 'max:50'],
            'whatsapp_number' => ['sometimes', 'required', 'string', 'max:50'],
            'whatsapp_prefill_text' => ['nullable', 'string'],
            'map_embed_url' => ['nullable', 'string'],
            'opening_hours' => ['sometimes', 'required', 'string', 'max:255'],
            'image' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $showroom->update($validated);

        return response()->json([
            'message' => 'Showroom updated successfully',
            'showroom' => $showroom,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $showroom = Showroom::findOrFail($id);
        $showroom->delete();

        return response()->json([
            'message' => 'Showroom deleted successfully',
        ]);
    }
}
