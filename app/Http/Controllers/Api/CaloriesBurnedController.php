<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CaloriesBurnedRequest;
use App\Models\CaloriesBurned;
use Illuminate\Http\Request;

class CaloriesBurnedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $calorie = CaloriesBurned::where('user_id', $request->user()->id)
            ->whereDate('created_at', now()->timezone('Pacific/Honolulu')->toDateString())
            ->first();

        return response()->json($calorie, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CaloriesBurnedRequest $request)
    {
        $calorie = CaloriesBurned::where('user_id', $request->user()->id)
            ->whereDate('created_at', now())
            ->first();

        $calories = CaloriesBurned::updateOrCreate(
            ['user_id' => $request->user()->id, 'created_at' => $calorie->created_at ?? null ],
            ['calories' => $request->calories]
        );

        return response()->json($calories, 201);
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
