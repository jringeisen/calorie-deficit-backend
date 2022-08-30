<?php

namespace App\Http\Controllers\Api\Actions;

use App\Models\ConsumedFood;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GetFoodListController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $foods = ConsumedFood::where('user_id', auth()->id())
            ->where('name', 'like', '%' . $request->name . '%')
            ->get();

        return response()->json($foods, 200);
    }
}
