<?php

namespace App\Http\Controllers\Api;

use App\Models\ConsumedFood;
use Illuminate\Http\Request;
use App\Models\CaloriesBurned;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ConsumedFoodsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): JsonResponse
    {
        $foods = ConsumedFood::where('user_id', $request->user()->id)
            ->whereBetween('created_at', [$request->user()->startOfDayUtc(), $request->user()->endOfDayUtc()])
            ->get();

        $calories_burned = CaloriesBurned::where('user_id', $request->user()->id)
            ->whereBetween('created_at', [$request->user()->startOfDayUtc(), $request->user()->endOfDayUtc()])
            ->first();

        return response()->json([
            'foods' => $foods,
            'total_calories' => $foods->sum('total_calories'),
            'deficit' => $foods->sum('total_calories') - $calories_burned?->calories,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        $food = $request->user()->consumedFoods()->create([
            'servings' => $request->servings,
            'name' => $request->name,
            'calories' => $request->calories,
            'total_calories' => $request->calories * $request->servings,
        ]);

        return response()->json($food, 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  ConsumedFood  $consumed_food
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ConsumedFood $consumed_food): JsonResponse
    {
        $food = $consumed_food->update([
            'servings' => $request->servings,
            'name' => $request->name,
            'calories' => $request->calories,
            'total_calories' => $request->calories * $request->servings,
        ]);

        return response()->json($food);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  ConsumedFood  $consumed_food
     * @return \Illuminate\Http\Response
     */
    public function destroy(ConsumedFood $consumed_food): JsonResponse
    {
        $consumed_food->delete();

        return response()->json([], 204);
    }
}
