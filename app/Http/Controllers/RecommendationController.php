<?php

namespace App\Http\Controllers;

use App\Services\RecommendationService;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    protected $recommendationService;

    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    public function onlyForYou(Request $request)
    {
        $user = auth()->user();

        $perPage = $request->input('per_page', 4);

        // Delegate all work to the Service
        $products = $this->recommendationService->getPersonalizedRecommendations($user, $perPage);

        if ($products->isEmpty()) {
            return response()->json([
                'status' => true,
                'message' => 'No recommendations available at the moment',
                'data' => [] 
            ], 200); // Using 200 to keep the JS from crashing
        }

        return response()->json([
            'status' => true,
            'data' => $products->values(), // values() ensures a clean JSON array
        ]);
    }
}