<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ModelProfile;
use App\Models\PackageRecipient;
use App\Models\RecipientEvent;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ModelsController extends Controller
{
    public function index(Request $request) {
        $perPage = $request->get("per_page",10);

        $models = ModelProfile::paginate($perPage);

        return response()->json([
            'message' => 'Success',
            'data' => $models->items(),
            'count' => $models->count(),
            'total' => $models->total(),
            'page' => $request->get('page',1),
            'last_page' => $models->lastPage()
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'image' => ['required', 'max:2048']
        ]);

        $model = ModelProfile::create([
            'name' => $request->name,
            'about' => $request->about,
            'metadata' => json_encode($request->metadata),
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('models', 'public');

            $model->image = $path;
            $model->save();
        }

        return response()->json([
            'message' => 'Model created successfully',
            'data'=> $model
        ], 201);
    }

    public function show($id) {
        $modelProfile = ModelProfile::findOrFail($id);

        return response()->json([
            'message' => 'Retrieved Successfully',
            'data' => $modelProfile
        ]);
    }

    public function update(Request $request, $id) {
        $modelProfile = ModelProfile::findOrFail($id);

        $modelProfile->update($request->only([
            'name', 'about'
        ]));

        if ($request->filled('metadata')) {
            $modelProfile->metadata = json_encode($request->metadata);
            $modelProfile->save();
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('models', 'public');

            $modelProfile->image = $path;
            $modelProfile->save();
        }

        return response()->json([
            'message' => 'Model updated successfully!',
            'data' => $modelProfile
        ]);
    }

    public function destroy($id){
        $modelProfile = ModelProfile::findOrFail($id);

        $modelProfile->delete();
        return response()->json([
            'message' => 'Deleted Successfully'
        ]);
    }

    public function download($token, ModelProfile $modelProfile) {
        $pkgRecipient = PackageRecipient::where('token', $token)
            ->where(function ($query) {
                $query->where('expires_at', '>', now())
                    ->orWhereNull('expires_at');
            })
            ->firstOrFail();

        $pdf = Pdf::loadView('pdfs.model-profile', compact('modelProfile'));

        RecipientEvent::create([
            'package_id' => $pkgRecipient->package_id,
            'package_version_id' => $pkgRecipient->package_version_id,
            'package_recipient_id' => $pkgRecipient->id,
            'recipient_id' => $pkgRecipient->recipient_id,
            'model_id' => $modelProfile->id,
            'event_type' => 'download',
            'data' => json_encode(['format' => 'pdf']),
        ]);

        return $pdf->download("model-{$modelProfile->id}.pdf");
    }
}
