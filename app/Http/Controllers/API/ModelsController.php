<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ModelProfile;
use App\Models\Package;
use App\Models\PackageRecipient;
use App\Models\PackageVersion;
use App\Models\Recipient;
use App\Models\RecipientEvent;
use App\Models\Shortlist;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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
            $path = $request->file('image')->store('models');

            $model->image = $path;
            $model->save();
        }

        return response()->json([
            'message' => 'Model created successfully',
            'data'=> $model
        ], 201);
    }

    public function show(ModelProfile $modelProfile) {
        return response()->json([
            'message' => 'Retrieved Successfully',
            'data' => $modelProfile
        ]);
    }

    public function update(Request $request, ModelProfile $modelProfile) {
        $modelProfile->update($request->only([
            'name', 'about'
        ]));

        if ($request->filled('metadata')) {
            $modelProfile->metadata = json_encode($request->metadata);
            $modelProfile->save();
        }

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('models');

            $modelProfile->image = $path;
            $modelProfile->save();
        }
        
        return response()->json([
            'message' => 'Model updated successfully!',
            'data' => $modelProfile
        ]);
    }

    public function destroy(ModelProfile $modelProfile){
        $modelProfile->delete();

        return response()->json([
            'message' => 'Deleted Successfully'
        ]);
    }

    public function download($token, modelProfile $modelProfile) {
        $pkgRecipient = PackageRecipient::where('token', $token)
            ->where('expires_at', '>', now())
            ->firstOrFail();

        $pdf = Pdf::loadView('pdf.model-profile', compact('model'));

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
