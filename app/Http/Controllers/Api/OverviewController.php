<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CaloriesBurned;
use App\Models\ConsumedFood;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OverviewController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $foods = ConsumedFood::select('user_id', 'total_calories', DB::raw('DATE(created_at) as date'))
            ->where('user_id', $request->user()->id)
            ->orderBy('date', 'desc')
            ->get()
            ->groupBy('date')
            ->map(function ($food, $key) {
                $burned = CaloriesBurned::where('user_id', auth()->id())
                    ->whereDate('created_at', Carbon::parse($key))
                    ->first();

                return [
                    'date' => Carbon::parse($key)->timezone('Pacific/Honolulu')->toFormattedDateString(),
                    'deficit' => $food->sum('total_calories') - $burned?->calories,
                ];
            });

        return response()->json($foods, 200);
    }
}
