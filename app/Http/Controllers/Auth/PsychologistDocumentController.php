<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Users\UserFile;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use App\Models\Users\PsychologistDocument;
use App\Exceptions\UnauthorizedActionException;
use App\Http\Requests\Auth\StorePsychologistDocumentRequest;
use App\Exceptions\PsychologistProfileNotEligibleForDocumentSubmissionException;

class PsychologistDocumentController extends Controller
{
    /**
     * Método responsável por realizar o upload dos documentos necessários para o cadastro de um novo psicólogo
     * @param \App\Http\Requests\Auth\StorePsychologistDocumentRequest $request
     * @throws \App\Exceptions\PsychologistProfileNotEligibleForDocumentSubmissionException
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StorePsychologistDocumentRequest $request)
    {
        //Obtém o usuário autenticado
        $user = Auth::user();
        $user->load('psychologistProfile');

        //Verifica se o usuário tem permissão de enviar os arquivos para análise
        //Somente usuários que possuem perfil de psicólogo como "pendente" ou
        //Rejeitado podem enviar.
        $allowed = Gate::allows("create",PsychologistDocument::class);

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
                    'original_name' => $type,
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

    /**
     * Método responsável por retornar um arquivo obrigatório de psicólogo
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Users\PsychologistDocument $document
     * @throws \App\Exceptions\UnauthorizedActionException
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function show(Request $request, PsychologistDocument $document)
    {
        $allowed = Gate::allows("view",$document);

        if(!$allowed) {
            throw new UnauthorizedActionException();
        }
        
        
        $file = $document->userFile;
        abort_unless($file->exists,404);
        

        $path = $file->path;
        abort_unless(Storage::disk('local')->exists($path),404);

        return Storage::disk('local')->download($path, $file->original_name ?? basename($path), [
            'Cache-Control' => 'no-store',
        ]);
    }
}
