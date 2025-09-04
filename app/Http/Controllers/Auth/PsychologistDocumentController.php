<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\PsychologistProfileNotEligibleForDocumentSubmissionException;
use Illuminate\Support\Str;
use App\Models\Users\UserFile;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Users\PsychologistDocument;
use App\Http\Requests\Auth\StorePsychologistDocumentRequest;

class PsychologistDocumentController extends Controller
{
    public function store(StorePsychologistDocumentRequest $request)
    {
        //Obtém o usuário autenticado
        $user = Auth::user();

        //Verifica se o usuário tem permissão de enviar os arquivos para análise
        //Somente usuários que possuem perfil de psicólogo como "pendente" ou
        //Rejeitado podem enviar.
        $allowed = Gate::allows("create",$user);

        if(!$allowed) {
            throw new PsychologistProfileNotEligibleForDocumentSubmissionException();
        }

        //Validamos os arquivos enviados pela requisição
        $files = $request->validated();

        //Perfil de psicólogo é carregado para utilizar a relação documents() presente
        $user->load('psychologistProfile');

        //Iteração sobre os documentos enviados
        foreach ($files as $type => $file) {
            //Criamos um nome criptografado para o arquivo
            $name = $file->hashName();

            //Armazenamento do arquivo e obtenção do caminho para registro em banco
            $path = $file->storeAs("uploads/{$user->id}/psychologist_doc",$name,'local');

            //Registro do arquivo no cabeçalho de arquivos de usuário
            $userFileId = UserFile::create(
                [
                    'user_id'=> $user->id,
                    'purpose'=> 'psychologist_doc',
                    'path' => $path,
                    'mime_type' => $file->getClientMimeType(),
                ]
            )->getKey();

            //Registro do documento em tabela específica para controle de aprovação ou reprovação dos documentos
            $user->psychologistProfile->documents()->create([
                'id' => Str::uuid(),
                'user_file_id' => $userFileId,
                'type' => $type,
                'status' => 'pending',
            ]);
        }

        //Reset do status do usuário para 'pending', caso esteja como 'rejected'
        if($user->psychologistProfile->status === 'rejected') {
            $user->psychologistProfile->update([
                'status'=> 'pending',
                'submitted_at' => now(),
                'rejected_at' => null,
                'rejection_reason' => null,
            ]);
        }

        return response()->json('Success',201);
    }
}
