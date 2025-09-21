<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\PackageRecipient;
use App\Models\PackageVersion;
use App\Models\Recipient;
use App\Models\RecipientEvent;
use App\Models\Shortlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RecipientController extends Controller
{

    public function sendPackage(Request $request, Package $package) {
        $request->validate([
            'email'=>'required|email',
            'package_version_id'=>'nullable|integer',
            'recipient_name'=>'nullable|string'
        ]);

        DB::transaction(function() use ($request, $package, &$packageRecepient){
            $recipient = Recipient::firstOrCreate(['email'=>$request->email], [
                'name' => $request->recipient_name ?? null,
                'phone' => $request->phone ?? null
            ]);

            if ($request->filled('package_version_id')) {
                $version = PackageVersion::findOrFail($request->package_version_id);
            } else {
                $lastVersion = $package->versions()->latest('version')->first();
                $versionNb = $lastVersion ? $lastVersion->version + 1 : 1;
                $version = PackageVersion::create([
                    'package_id' => $package->id,
                    'version' => $versionNb,
                    'created_by' => $request->user()->id,
                    'notes' => $request->notes ?? null
                ]);

                foreach ($package->models as $m) {
                    $version->models()->create([
                        'model_id' => $m->id,
                        'model_snapshot' => [
                            'about' => $m->about,
                            'image' => $m->image,
                            'metadata' => $m->metadata,
                        ],
                    ]);
                }
            }

            $packageRecepient = PackageRecipient::create([
                'package_id' => $package->id,
                'package_version_id' => $version->id,
                'recipient_id' => $recipient->id,
                'sent_by' => $request->user()->id,
                'token' => Str::random(40),
                'expires_at' => $request->expires_at ? now()->addSeconds(intval($request->expires_at)) : null
            ]);

            $recipient->recipient->notify(new SendPackageNotification($recipient));
        });

        return response()->json([
            'message' => 'Package sent successfully!',
            'data' => $packageRecepient->load('recipient')
        ], 201);
    }
    
}
