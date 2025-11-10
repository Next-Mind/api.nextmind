<?php

namespace App\Modules\Psychologists\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Psychologists\Http\Requests\BulkDisapprovePsychologistDocumentsFormRequest;
use App\Modules\Psychologists\Http\Requests\DisapprovePsychologistDocumentFormRequest;
use App\Modules\Psychologists\Models\PsychologistProfile;
use App\Modules\Psychologists\Models\PsychologistDocument;
use App\Modules\Psychologists\Http\Resources\PsychologistDocumentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class PsychologistApprovalController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->integer('per_page', 10);

        $status = $request->filled('status')
            ? array_filter(explode('|', $request->query('status')))
            : 'pending';

        return PsychologistProfile::with(['documents', 'documents.reviewer:id,name', 'psychologist:id,name'])
            ->whereIn('status', $status)
            ->orderByDesc('created_at')
            ->simplePaginate($perPage)
            ->toResourceCollection();
    }

    public function approveDocument(Request $request, PsychologistDocument $document)
    {
        $admin = Auth::user();
        Gate::authorize('update', $document);
        return DB::transaction(function () use ($document, $admin) {
            $document->update([
                'status' => 'approved',
                'reviewed_at' => now(),
                'reviewed_by' => $admin->id,
                'rejection_reason' => null
            ]);

            $profileId = $document->psychologist_profile_id;

            $approvedCount  = PsychologistDocument::where('psychologist_profile_id', $profileId)
                ->whereStatus('approved')
                ->whereIn('type', PsychologistDocument::REQUIRED_TYPES)
                ->selectRaw('COUNT(DISTINCT type) as c')
                ->value('c');

            $allApproved = ((int) $approvedCount) === count(PsychologistDocument::REQUIRED_TYPES);

            if ($allApproved) {
                $profile = PsychologistProfile::whereKey($profileId)->lockForUpdate()->first();

                if ($profile && $profile->status !== 'approved') {
                    $profile->forceFill([
                        'status' => 'approved',
                        'approved_at' => now(),
                        'approved_by' => $admin->id,
                        'rejected_at'      => null,
                        'rejection_reason' => null,
                        'verified_at'      => now(),
                    ])->save();
                }
            }

            $document->load('reviewer');
            return new PsychologistDocumentResource($document);
        });
    }

    public function disapproveDocument(DisapprovePsychologistDocumentFormRequest $request, PsychologistDocument $document)
    {
        $admin = Auth::user();
        Gate::authorize('update', $document);
        $input = $request->validated();
        return DB::transaction(function () use ($document, $admin, $input) {
            $document->update([
                'status' => 'rejected',
                'reviewed_at' => now(),
                'reviewed_by' => $admin->id,
                'rejection_reason' => $input['rejection_reason'],
            ]);

            $profile = PsychologistProfile::whereKey($document->psychologist_profile_id)
                ->lockForUpdate()
                ->first();

            if ($profile) {
                $this->updateProfileStatusAfterRejection($profile, $input['rejection_reason']);
            }

            $document->load('reviewer');

            return new PsychologistDocumentResource($document);
        });
    }

    public function disapproveDocuments(BulkDisapprovePsychologistDocumentsFormRequest $request)
    {
        $admin = Auth::user();

        $payload = $request->validated();
        $documentIds = $payload['documents'];
        $reason = $payload['rejection_reason'];

        return DB::transaction(function () use ($admin, $documentIds, $reason) {
            $documents = PsychologistDocument::query()
                ->whereIn('id', $documentIds)
                ->lockForUpdate()
                ->get();

            if ($documents->count() !== count($documentIds)) {
                abort(404, 'Alguns documentos não foram encontrados.');
            }

            $profileIds = $documents->pluck('psychologist_profile_id')->unique();

            if ($profileIds->count() !== 1) {
                abort(422, 'Todos os documentos devem pertencer ao mesmo psicólogo.');
            }

            $profile = PsychologistProfile::whereKey($profileIds->first())
                ->lockForUpdate()
                ->first();

            foreach ($documents as $document) {
                Gate::authorize('update', $document);

                $document->update([
                    'status' => 'rejected',
                    'reviewed_at' => now(),
                    'reviewed_by' => $admin->id,
                    'rejection_reason' => $reason,
                ]);

                $document->load('reviewer');
            }

            if ($profile) {
                $this->updateProfileStatusAfterRejection($profile, $reason);
            }

            return PsychologistDocumentResource::collection($documents);
        });
    }

    private function updateProfileStatusAfterRejection(PsychologistProfile $profile, string $reason): void
    {
        $hasNonRejectedDocuments = PsychologistDocument::query()
            ->where('psychologist_profile_id', $profile->getKey())
            ->where('status', '!=', 'rejected')
            ->exists();

        if ($hasNonRejectedDocuments) {
            if ($profile->status === 'approved') {
                $profile->forceFill([
                    'status' => 'pending',
                    'approved_at' => null,
                    'approved_by' => null,
                ])->save();
            }

            return;
        }

        $profile->forceFill([
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejection_reason' => $reason,
            'approved_at' => null,
            'approved_by' => null,
        ])->save();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
