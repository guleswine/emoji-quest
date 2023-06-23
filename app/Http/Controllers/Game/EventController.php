<?php

namespace App\Http\Controllers\Game;

use App\Http\Controllers\Controller;
use App\Services\EventService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function getCells(Request $request)
    {
        $data = EventService::getEvents(Auth::user());

        return response()->json($data);
    }
}
