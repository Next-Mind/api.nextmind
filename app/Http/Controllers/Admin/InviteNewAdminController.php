<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\InvitationAlreadyExistsException;
use App\Exceptions\UserAlreadyAdminException;
use App\Models\User;
use Illuminate\Support\Str;
use App\Models\AdminInvitation;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Exceptions\UserNotFoundException;
use Illuminate\Support\Facades\Notification;
use App\Notifications\AdminInvitationNotification;
use App\Http\Requests\Admin\InviteNewAdminFormRequest;

class InviteNewAdminController extends Controller
{
    public function __invoke(InviteNewAdminFormRequest $request)
    {
        $email = $request->validated('email');

        if (User::whereEmail($email)->whereHas('roles', fn($q)=>$q->where('name','admin'))->exists()) {
            throw new UserAlreadyAdminException();
        }

        $open = AdminInvitation::open()->where('email',$email)->first();
        if ($open) {
            throw new InvitationAlreadyExistsException();
        }

        $plainToken = Str::random(64);
        $hash = hash('sha256', $plainToken);
        $expiresAt = now()->addDays(3);

        $invitation = DB::transaction(function () use ($email, $hash, $expiresAt, $request) {
            return AdminInvitation::create([
                'email'      => $email,
                'invited_by' => $request->user()->id,
                'token' => $hash,
                'expires_at' => $expiresAt,
            ]);
        });

        $frontend = config('services.frontend.url');
        $acceptUrl  = "{$frontend}/admin-invite/accept?token={$plainToken}";
        $declineUrl = "{$frontend}/admin-invite/decline?token={$plainToken}";

        Notification::route('mail', $email)
            ->notify((new AdminInvitationNotification(
                inviteeEmail: $email,
                acceptUrl: $acceptUrl,
                declineUrl: $declineUrl,
                inviterName: $request->user()->name
            ))->afterCommit());

        return response()->json([
            'message' => 'Convite enviado por e-mail.',
            'data' => [
                'email'      => $email,
                'expires_at' => $invitation->expires_at->toIsoString(),
            ],
        ], 201);
    }
}
