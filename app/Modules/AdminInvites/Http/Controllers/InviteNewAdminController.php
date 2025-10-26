<?php

namespace App\Modules\AdminInvites\Http\Controllers;

use Illuminate\Support\Str;
use App\Modules\Users\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Notification;
use App\Modules\AdminInvites\Models\AdminInvitation;
use App\Modules\Users\Exceptions\UserAlreadyAdminException;
use App\Modules\AdminInvites\Http\Requests\InviteNewAdminFormRequest;
use App\Modules\AdminInvites\Notifications\AdminInvitationNotification;
use App\Modules\AdminInvites\Exceptions\InvitationAlreadyExistsException;

class InviteNewAdminController extends Controller
{
    public function __invoke(InviteNewAdminFormRequest $request)
    {
        $email = $request->validated('email');

        if (User::whereEmail($email)->whereHas('roles', fn($q) => $q->where('name', 'admin'))->exists()) {
            throw new UserAlreadyAdminException();
        }

        $open = AdminInvitation::open()->where('email', $email)->first();
        if ($open) {
            throw new InvitationAlreadyExistsException();
        }

        $plainToken = Str::random(64);
        $hash = hash('sha256', $plainToken);
        $expiresAt = now()->addDays(3);

        $invitation = DB::transaction(function () use ($email, $hash, $expiresAt, $request) {
            return AdminInvitation::create([
                'email'      => $email,
                'invited_by' => Request::user()->id,
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
                inviterName: Request::user()->name
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
