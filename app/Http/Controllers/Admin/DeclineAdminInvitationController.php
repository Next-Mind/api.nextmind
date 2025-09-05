<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\InvalidInviteException;
use App\Http\Requests\Admin\AdminInvitationDeclineFormRequest;
use Illuminate\Http\Request;
use App\Models\AdminInvitation;
use App\Http\Controllers\Controller;

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
