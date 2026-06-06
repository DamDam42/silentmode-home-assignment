<?php

namespace App\Http\Controllers;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function register(Request $request)
{
    Client::updateOrCreate(
        [
            'client_id' => $request->client_id
        ],
        [
            'last_seen' => now()
        ]
    );

    return response()->json([
        'message' => 'registered'
    ]);
}
}
