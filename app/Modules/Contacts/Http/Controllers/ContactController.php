<?php

namespace App\Modules\Contacts\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Contacts\Models\Contact;
use App\Modules\Contacts\Http\Resources\ContactResource;
use App\Modules\Contacts\Http\Requests\StoreContactRequest;
use App\Modules\Contacts\Http\Requests\UpdateContactRequest;
use App\Modules\Contacts\Actions\Contact\IndexContactAction;
use App\Modules\Contacts\Actions\Contact\StoreContactAction;
use App\Modules\Contacts\Actions\Contact\DestroyContactAction;
use App\Modules\Users\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ContactController extends Controller
{
    public function index(Request $request, IndexContactAction $action)
    {
        Gate::authorize('viewAny', Contact::class);

        /**
         * @var User
         */
        $owner = $request->user();

        $contacts = $action->execute($owner);
        return ContactResource::collection($contacts);
    }

    public function show(Request $request, Contact $contact)
    {
        Gate::authorize('view', $contact);
        return new ContactResource($contact);
    }

    public function store(StoreContactRequest $request, StoreContactAction $action)
    {
        Gate::authorize('create', Contact::class);

        /**
         * @var User
         */
        $owner = $request->user();
        // Permission guard for browsing candidates according to role

        if ($owner->hasRole('student')) {
            abort_unless($owner->can('contacts.candidates.browse.psychologists'), 403);
        } elseif ($owner->hasRole('psychologist')) {
            abort_unless($owner->can('contacts.candidates.browse.any'), 403);
        }
        $data = $request->validated();
        $target = User::findOrFail($data['contact_id']);
        $contact = $action->execute($owner, $target);
        return new ContactResource($contact->load('contactUser'));
    }

    public function update(UpdateContactRequest $request, Contact $contact)
    {
        abort(405);
    }

    public function destroy(Request $request, Contact $contact, DestroyContactAction $action)
    {
        Gate::authorize('delete', $contact);

        /**
         * @var User
         */
        $owner = $request->user();

        $action->execute($owner, $contact);
        return response()->noContent();
    }
}
