<?php

namespace App\Http\Controllers\Api;

use Carbon\Carbon;
use App\Models\ConsumedFood;
use Illuminate\Http\Request;
use App\Models\CaloriesBurned;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

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
        $foods = ConsumedFood::select('user_id', 'total_calories', 'created_at')
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($food, $key) {
                $start = $food->created_at->timezone('Pacific/Honolulu')->startOfDay()->timezone('UTC');
                $end = $food->created_at->timezone('Pacific/Honolulu')->endOfDay()->timezone('UTC');

                if ($food->created_at->between($start, $end)) {
                    return $food->created_at->timezone('Pacific/Honolulu')->toDateString();
                }
            })
            ->map(function ($items) {
                return $items->map(function ($food) use ($items) {
                    $burned = CaloriesBurned::where('user_id', auth()->id())
                        ->whereBetween('created_at', request()->user()->nowStartAndEndUtc())
                        ->first();

                    return [
                        'date' => $food->created_at->timezone('Pacific/Honolulu')->toDateString(),
                        'deficit' => $items->sum('total_calories') - $burned?->calories,
                    ];
                })->unique();
            })->flatten(1);

        return response()->json($foods, 200);
    }
}
