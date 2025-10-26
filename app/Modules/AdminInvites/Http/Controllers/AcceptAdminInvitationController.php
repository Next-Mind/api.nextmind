<?php

namespace App\Modules\AdminInvites\Http\Controllers;

use App\Modules\Users\Models\User;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Modules\AdminInvites\Models\AdminInvitation;
use App\Modules\AdminInvites\Http\Requests\AdminInvitationAcceptFormRequest;


class AcceptAdminInvitationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(AdminInvitationAcceptFormRequest $request)
    {
        $token = $request->validated('token');
        $hash  = hash('sha256', $token);

        $inv = AdminInvitation::where('token', $hash)->first();

        if (!$inv || $inv->isExpired || $inv->isUsed) {
            return response()->json(['message' => 'Convite inválido ou expirado.'], 410);
        }

        $user = User::whereEmail($inv->email)->first();

        if (!$user) {
            $data = $request->validated();
            if (!isset($data['name'], $data['password'])) {
                return response()->json([
                    'message' => 'Informe name e password para criar sua conta.'
                ], 422);
            }

            $user = User::create([
                'name'     => $data['name'],
                'email'    => $inv->email,
                'password' => $data['password'],
            ]);
        }

        DB::transaction(function () use ($user, $inv) {
            $user->assignRole('admin');
            $inv->forceFill(['accepted_at' => now()])->save();

            AdminInvitation::open()
                ->where('email', $inv->email)
                ->where('id', '!=', $inv->id)
                ->update(['expires_at' => now()]);
        });


        $token = $user->createToken('desktop')->plainTextToken;

        return response()->json([
            'message' => 'Convite aceito. Perfil de administrador concedido.',
            'token' => $token,
        ]);
    }
}
