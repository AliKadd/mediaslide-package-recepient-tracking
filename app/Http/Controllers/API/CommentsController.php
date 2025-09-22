<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\PackageRecipient;
use App\Models\PackageVersion;
use App\Models\RecipientEvent;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function store(Request $request, $token) {
        $pkgRecipient = PackageRecipient::where('token', $token)
            ->where(function ($query) {
                $query->where('expires_at', '>', now())
                    ->orWhereNull('expires_at');
            })
            ->firstOrFail();

        $request->validate([
            'model_id'=>'nullable|exists:models,id',
            'comment'=>'required|string'
        ]);

        $comment = Comment::create([
            'package_recipient_id' => $pkgRecipient->id,
            'package_version_id' => $pkgRecipient->package_version_id,
            'model_id' => $request->model_id ?? null,
            'recipient_id' => $pkgRecipient->recipient_id ?? null,
            'comment' => $request->comment ?? null,
        ]);

        RecipientEvent::create([
            'package_id' => $request->package_id ?? null,
            'package_version_id' => $pkgRecipient->package_version_id,
            'package_recipient_id' => $pkgRecipient->id,
            'recipient_id' => $pkgRecipient->recipient_id,
            'model_id' => $request->model_id ?? null,
            'event_type' => 'comment',
            'data' => [
                'comment' => $request->comment
            ]
        ]);

        return response()->json([
            'message' => 'Comment added successfully',
            'data' => $comment
        ], 201);
    }

    public function list(Request $request, PackageVersion $packageVersion) {
        $model_id = $request->model_id ?? null;
        $date_from = $request->date_from ?? Carbon::now()->startOfMonth();
        $date_to = $request->date_to ?? Carbon::now()->endOfMonth();

        $comments = Comment::with(['recipient'])
            ->whereBetween('created_at', [$date_from, $date_to])
            ->where('package_version_id', $packageVersion->id);
        if ($model_id) {
            $comments->where('model_id', $model_id);
        }

        return response()->json([
            'message'=> 'Success',
            'data' => $comments->get()
        ]);
    }
}
