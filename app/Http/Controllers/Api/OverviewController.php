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
        Auth::loginUsingId(1);

        $foods = ConsumedFood::select('user_id', 'total_calories', 'created_at')
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($food) {
                $start = $food->created_at->timezone('Pacific/Honolulu')->startOfDay()->timezone('UTC');
                $end = $food->created_at->timezone('Pacific/Honolulu')->endOfDay()->timezone('UTC');

                if ($food->created_at->between($start, $end)) {
                    $burned = CaloriesBurned::where('user_id', auth()->id())
                        ->whereDate('created_at', Carbon::parse($food->created_at))
                        ->first();

                    return [
                        'date' => Carbon::parse($food->created_at, 'Pacific/Honolulu')->toDateString(),
                        'deficit' => $food->sum('total_calories') - $burned?->calories,
                    ];
                }
            })
            ->unique()
            ->groupBy('date')
            ->flatten(1);

        return response()->json($foods, 200);
    }
}
