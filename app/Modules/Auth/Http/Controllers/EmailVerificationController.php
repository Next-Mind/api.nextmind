<?php

namespace App\Modules\Auth\Http\Controllers;

use App\Modules\Users\Http\Resources\UserResource;
use App\Modules\Users\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class EmailVerificationController extends Controller
{
    public function send(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['verified' => true], 200);
        }

        $request->user()->sendEmailVerificationNotification();

        return response()->json(['sent' => true], 202);
    }

    // Confirma a verificação via link assinado
    public function verify($id, $hash)
    {
        $user = User::findOrFail($id);

        // Valida o hash do e-mail
        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403, 'Assinatura inválida.');
        }

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        return response()
            ->json(
                (new UserResource($user))
                    ->additional(['verified' => true]),
                200
            );
    }

    public function notice(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['verified' => true], 200);
        }
        return response()->json([
            'verified' => false,
            'message'  => 'E-mail ainda não verificado.'
        ], 409);
    }
}
