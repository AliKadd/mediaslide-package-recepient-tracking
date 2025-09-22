<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PackageRecipient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventsController extends Controller
{

    public function store(Request $request, $token) {
        $pkgRecipient = PackageRecipient::where('token', $token)
            ->where(function ($query) {
                $query->where('expires_at', '>', now())
                    ->orWhereNull('expires_at');
            })
            ->firstOrFail();

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
                'package_id' => $pkgRecipient->package_id,
                'package_version_id' => $pkgRecipient->package_version_id,
                'package_recipient_id' => $pkgRecipient->id,
                'recipient_id' => $pkgRecipient->recipient_id,
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
