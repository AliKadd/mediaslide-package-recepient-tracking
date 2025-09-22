<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ModelProfile;
use App\Models\Package;
use App\Models\PackageRecipient;
use App\Models\PackageVersion;
use App\Models\PackageVersionModel;
use App\Models\Recipient;
use App\Models\RecipientEvent;
use App\Models\Shortlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PackageController extends Controller
{
    public function index(Request $request) {
        $perPage = $request->get("per_page",10);

        $packages = Package::with('models')
            ->paginate($perPage);

        return response()->json([
            'message' => 'Success',
            'data' => $packages->items(),
            'count' => $packages->count(),
            'total' => $packages->total(),
            'page' => $request->get('page',1),
            'last_page' => $packages->lastPage()
        ]);
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required|string',
            'status' => ['nullable', Rule::in(['draft', 'sent', 'archived'])]
        ]);

        $package = Package::create([
            'title' => $request->title,
            'description' => $request->description,
            'status' => $request->status ?? 'draft',
            'created_by' => $request->user()->id,
        ]);

        if ($request->filled('model_ids') && is_array($request->model_ids)) {
            $package->models()->sync($request->model_ids);
        }

        return response()->json([
            'message' => 'Package created successfully',
            'data'=> $package->load('models')
        ], 201);
    }

    public function show(Package $package) {
        return response()->json([
            'message' => 'Retrieved Successfully',
            'data' => $package->load('models', 'versions')
        ]);
    }

    public function update(Request $request, Package $package) {
        $package->update($request->only([
            'title','description','status'
        ]));

        if ($request->filled('model_ids') && is_array($request->model_ids)){
            $package->models()->sync($request->model_ids);
        }

        return response()->json([
            'message' => 'Package updated successfully!',
            'data' => $package->load('models')
        ]);
    }

    public function destroy(Package $package){
        $package->delete();

        return response()->json([
            'message' => 'Deleted Successfully'
        ]);
    }

    public function getVersion(Request $request, PackageVersion $packageVersion) {
        return response()->json([
            'message'=> 'Retrieved Successfully',
            'data' => $packageVersion->load('models')
        ]);
    }

    public function showByToken(Request $request, $token) {
        $packageRecipient = PackageRecipient::where('token', $token)
            ->with(['version.models.model', 'package'])
            ->firstOrFail();

        if ($packageRecipient->expires_at && $packageRecipient->expires_at->isPast()) {
            return response()->json([
                'message' => 'Package link has expired'
            ], 410);
        }

        RecipientEvent::create([
            'package_id' => $packageRecipient->package_id,
            'package_version_id' => $packageRecipient->package_version_id,
            'package_recipient_id' => $packageRecipient->id,
            'recipient_id' => $packageRecipient->recipient_id,
            'event_type' => 'view_package',
            'data' => json_encode([
                'ip'=> $request->ip(),
                'user-agent' => $request->userAgent()
            ])
        ]);

        return response()->json([
            'message' => 'Success',
            'package' => $packageRecipient->package,
            'version' => $packageRecipient->version,
            'recipient' => $packageRecipient->recipient,
        ]);
    }

    public function shortlistModel($token, ModelProfile $modelProfile, Request $request) {
        $pkgRecipient = PackageRecipient::where('token', $token)
            ->where(function ($query) {
                $query->where('expires_at', '>', now())
                    ->orWhereNull('expires_at');
            })
            ->firstOrFail();

        $model = PackageVersionModel::where('package_version_id', $pkgRecipient->package_version_id)
            ->where('model_id', $modelProfile->id)
            ->firstOrFail();

        $model->shortlisted = true;
        $model->save();

        RecipientEvent::create([
            'package_id' => $pkgRecipient->package_id,
            'package_version_id' => $pkgRecipient->package_version_id,
            'package_recipient_id' => $pkgRecipient->id,
            'recipient_id' => $pkgRecipient->pkgRecipient,
            'model_id' => $model->id,
            'event_type' => 'shortlist',
            'data' => null,
        ]);

        return response()->json([
            'message' => 'Model shortlisted successfully'
        ]);
    }
}
