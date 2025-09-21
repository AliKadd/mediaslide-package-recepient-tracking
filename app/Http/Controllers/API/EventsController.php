<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventsController extends Controller
{
    
    public function store(Request $request) {
        $events = $request->input('events');
        if (!is_array($events)) {
            return response()->json([
                'message' => 'Events must be an array'
            ], 422);
        }

        $rows = [];
        $now = now();
        foreach ($events as $event) {
            $rows[] = [
                'package_id' => $event['package_id'] ?? null,
                'package_version_id' => $event['package_version_id'] ?? null,
                'package_recipient_id' => $event['package_recipient_id'] ?? null,
                'recipient_id' => $event['recipient_id'] ?? null,
                'model_id' => $event['model_id'] ?? null,
                'event_type' => $event['event_type'] ?? null,
                'data' => isset($event['data']) ? json_encode($event['data']) : null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('recipient_events')->insert($rows);

        return response()->json([
            'message'=> 'Events stored successfully',
            'inserted' => count($rows)
        ], 201);
    }
}
