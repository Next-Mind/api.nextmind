<?php

namespace App\Modules\Contacts\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Modules\Contacts\Actions\Candidate\IndexCandidateUserAction;

class ContactCandidateController extends Controller
{
    public function index(Request $request, IndexCandidateUserAction $action)
    {
        Gate::authorize('viewAny', \App\Modules\Contacts\Models\Contact::class);
        $user = $request->user();
        if ($user->hasRole('student')) {
            abort_unless($user->can('contacts.candidates.browse.psychologists'), 403);
        } elseif ($user->hasRole('psychologist')) {
            abort_unless($user->can('contacts.candidates.browse.any'), 403);
        }
        $users = $action->execute($user, $request->query());
        return response()->json([
            'data' => $users->items(),
            'next_page_url' => $users->nextPageUrl(),
        ]);
    }
}
