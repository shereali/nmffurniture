<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    /**
     * Store new customer inquiry or quotation lead.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['nullable', 'email', 'max:255'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'product_id' => ['nullable', 'exists:products,id'],
        ]);

        $inquiry = Inquiry::create([
            ...$validated,
            'status' => 'new',
        ]);

        return response()->json([
            'message' => 'Your inquiry has been submitted! Our furniture specialist will reach out shortly.',
            'inquiry' => $inquiry,
        ], 201);
    }
}
