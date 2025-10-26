<?php

namespace App\Modules\AdminInvites\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\AdminInvites\Models\AdminInvitation;
use App\Modules\AdminInvites\Exceptions\InvalidInviteException;
use App\Modules\AdminInvites\Http\Requests\AdminInvitationDeclineFormRequest;


class DeclineAdminInvitationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(AdminInvitationDeclineFormRequest $request)
    {
        $hash = hash('sha256', $request->validated('token'));
        $inv = AdminInvitation::where('token_hash', $hash)->first();

        if (!$inv || $inv->isExpired || $inv->isUsed) {
            throw new InvalidInviteException();
        }

        $inv->forceFill(['declined_at' => now()])->save();

        return response()->json(['message' => 'Convite recusado.']);
    }
}
