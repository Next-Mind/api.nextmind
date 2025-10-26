<?php

namespace App\Modules\Psychologists\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Psychologists\Http\Requests\DisapprovePsychologistDocumentFormRequest;
use App\Modules\Psychologists\Models\PsychologistProfile;
use App\Modules\Psychologists\Models\PsychologistDocument;
use App\Modules\Psychologists\Http\Resources\PsychologistDocumentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
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
        $input = $request->only('rejection_reason');
        return DB::transaction(function () use ($document, $admin, $input) {
            $document->update([
                'status' => 'rejected',
                'reviewed_at' => now(),
                'reviewed_by' => $admin->id,
                'rejection_reason' => $input['rejection_reason']
            ]);

            $profile = $document->psychologistOwner();

            $profile->whereStatus('approved')->update([
                'status' => 'pending',
            ]);


            $document->load('reviewer');
            return new PsychologistDocumentResource($document);
        });
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
