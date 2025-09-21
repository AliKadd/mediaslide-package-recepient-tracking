<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\PackageVersion;
use App\Models\RecipientEvent;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    public function store(Request $request) {
        $request->validate([ 
            'package_recipient_id'=>'nullable|exists:package_recipients,id',
            'package_version_id'=>'nullable|exists:package_versions,id',
            'model_id'=>'nullable|exists:models,id',
            'recipient_id'=>'required|exists:recipients,id',
            'comment'=>'required|string'
        ]);

        $comment = Comment::create($request->only([
            'package_recipient_id', 'package_version_id', 'model_id', 'recipient_id', 'comment'
        ]));

        RecipientEvent::create([
            'package_id' => $request->package_id ?? null,
            'package_version_id' => $request->package_version_id ?? null,
            'package_recipient_id' => $request->package_recipient_id ?? null,
            'recipient_id' => $request->recipient_id,
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

        $comments = Comment::where('package_version_id', $packageVersion->id);
        if ($model_id) {
            $comments->where('model_id', $model_id);
        }

        return response()->json([
            'message'=> 'Success',
            'data' => $comments->get()
        ]);
    }
}
