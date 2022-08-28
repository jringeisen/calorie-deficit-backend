<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\ConsumedFood;
use Illuminate\Http\Request;
use App\Models\CaloriesBurned;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

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
        $burned = CaloriesBurned::where('user_id', $request->user()->id)
            ->whereDate('created_at', now()->timezone('Pacific/Honolulu'))
            ->first();

        $foods = ConsumedFood::select('user_id', 'total_calories', DB::raw('DATE(created_at) as date'))
            ->where('user_id', $request->user()->id)
            ->orderBy('date', 'desc')
            ->get()
            ->groupBy('date')
            ->map(function ($food, $key) use ($burned) {
                return [
                    'date' => Carbon::parse($key)->toFormattedDateString(),
                    'deficit' => $food->sum('total_calories') - $burned?->calories,
                ];
            });

        return response()->json($foods, 200);
    }
}
