<?php

namespace App\Http\Controllers\Api;

use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class TimezoneController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request): array
    {
        return [
            'user_timezone' => $request->user()->timezone,
            'timezones' => collect(DateTimeZone::listIdentifiers(DateTimeZone::ALL, 'US'))
                ->map(function ($timezone, $index) {
                    return [
                        'id' => $index,
                        'name' => $timezone
                    ];
                })
            ];
    }

    /**
     * Updates the timezone attribute on a user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->user()->update([
            'timezone' => $request->timezone,
        ]);

        return response()->json($request->user());
    }
}
