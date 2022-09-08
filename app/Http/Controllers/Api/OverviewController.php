<?php

namespace App\Http\Controllers\Api;

use App\Models\ConsumedFood;
use Illuminate\Http\Request;
use App\Models\CaloriesBurned;
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
        $foods = ConsumedFood::select('user_id', 'total_calories', 'created_at')
            ->where('user_id', $request->user()->id)
            ->whereBetween('created_at', $request->user()->lastThirtyDaysUtc())
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($food) {
                $start = request()->user()->timezone
                    ? $food->created_at->timezone(request()->user()->timezone)->startOfDay()->timezone('UTC')
                    : $food->created_at->startOfDay();

                $end = request()->user()->timezone
                    ? $food->created_at->timezone(request()->user()->timezone)->endOfDay()->timezone('UTC')
                    : $food->created_at->endOfDay();

                if ($food->created_at->between($start, $end)) {
                    return request()->user()->timezone
                        ? $food->created_at->timezone(request()->user()->timezone)->toDateString()
                        : $food->created_at->toDateString();
                }
            })
            ->map(function ($items) {
                return $items->map(function ($food) use ($items) {
                    $start = request()->user()->timezone
                        ? $food->created_at->timezone(request()->user()->timezone)->startOfDay()->timezone('UTC')
                        : $food->created_at->startOfDay();

                    $end = request()->user()->timezone
                        ? $food->created_at->timezone(request()->user()->timezone)->endOfDay()->timezone('UTC')
                        : $food->created_at->endOfDay();

                    $burned = CaloriesBurned::where('user_id', auth()->id())
                        ->whereBetween('created_at', [$start, $end])
                        ->first();

                    return [
                        'date' => request()->user()->timezone
                            ? $food->created_at->timezone(request()->user()->timezone)->toDateString()
                            : $food->created_at->toDateString(),
                        'consumed' => $items->sum('total_calories'),
                        'burned' => $burned?->calories ?? 2000,
                        'deficit' => $items->sum('total_calories') - $burned?->calories ?? 2000,
                    ];
                })->unique();
            })->flatten(1);

        return response()->json($foods, 200);
    }
}
