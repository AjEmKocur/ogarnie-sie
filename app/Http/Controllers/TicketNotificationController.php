<?php

namespace App\Http\Controllers;

use App\Support\TicketNotificationCenter;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TicketNotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        return response()->json(TicketNotificationCenter::forUser($request->user()));
    }
}

