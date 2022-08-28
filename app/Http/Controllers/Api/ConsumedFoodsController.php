<?php

namespace App\Http\Controllers\Api;

use App\Models\ConsumedFood;
use Illuminate\Http\Request;
use App\Models\CaloriesBurned;
use App\Http\Controllers\Controller;

class ConsumedFoodsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $foods = ConsumedFood::where('user_id', $request->user()->id)
            ->whereDate('created_at', now())
            ->get();

        $calories_burned = CaloriesBurned::where('user_id', $request->user()->id)
            ->whereDate('created_at', now())
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
    public function store(Request $request)
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
