<?php

namespace App\Http\Controllers;

use App\Models\Tickets\Ticket;
use App\Http\Resources\TicketResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;

use Illuminate\Support\Facades\Gate;

class TicketController extends Controller
{
    /**
     * GET /tickets
     * Filtros: status_id, category_id, subcategory_id, assigned_to_id, requester_id
     * Busca: q (por subject ou ticket_number)
     * Data: date_from, date_to (em created_at)
     * Ordenação: sort=field ou sort=-field (default -created_at)
     * Includes: ?include=category,status,requester,openedBy,assignee,subcategory,comments.user,attachments
     * Paginação: per_page (1..100)
     */
    public function index(Request $request)
    {
        Gate::authorize('viewAny', Ticket::class);

        [$includes, $sortField, $sortDir] = [
            $this->resolveIncludes($request),
            $this->resolveSortField($request->string('sort')->toString()),
            $this->resolveSortDir($request->string('sort')->toString()),
        ];

        $perPage  = (int) max(1, min(100, (int) $request->integer('per_page', 15)));
        $query    = Ticket::query()->with($includes);

        // Filtros simples
        $query->when($request->filled('status_id'),      fn ($q) => $q->where('ticket_status_id', $request->string('status_id')));
        $query->when($request->filled('category_id'),    fn ($q) => $q->where('ticket_category_id', $request->string('category_id')));
        $query->when($request->filled('subcategory_id'), fn ($q) => $q->where('ticket_subcategory_id', $request->string('subcategory_id')));
        $query->when($request->filled('assigned_to_id'), fn ($q) => $q->where('assigned_to_id', $request->string('assigned_to_id')));
        $query->when($request->filled('requester_id'),   fn ($q) => $q->where('requester_id', $request->string('requester_id')));

        // Busca por subject / ticket_number
        $query->when($request->filled('q'), function ($q) use ($request) {
            $term = trim($request->string('q')->toString());
            $q->where(function ($qq) use ($term) {
                if (ctype_digit($term)) {
                    $qq->orWhere('ticket_number', (int) $term);
                }
                $qq->orWhere('subject', 'like', "%{$term}%");
            });
        });

        // Janela de datas (created_at)
        $query->when($request->filled('date_from'), fn ($q) => $q->whereDate('created_at', '>=', $request->date('date_from')->format('Y-m-d')));
        $query->when($request->filled('date_to'),   fn ($q) => $q->whereDate('created_at', '<=', $request->date('date_to')->format('Y-m-d')));

        // Ordenação
        $query->orderBy($sortField, $sortDir);

        $tickets = $query->paginate($perPage)->appends($request->query());

        return TicketResource::collection($tickets);
    }

    /**
     * POST /tickets
     */
    public function store(StoreTicketRequest $request)
    {
        Gate::authorize('create', Ticket::class);

        $data = $request->validated();
        // Aberto por quem está autenticado, a menos que já venha definido pela request/policy
        $data['opened_by_id'] = $data['opened_by_id'] ?? (string) auth()->id();

        // Se quiser que o requester padrão seja o próprio opened_by
        $data['requester_id'] = $data['requester_id'] ?? $data['opened_by_id'];

        $ticket = DB::transaction(function () use ($data) {
            /** @var \App\Models\Tickets\Ticket $ticket */
            $ticket = Ticket::create($data);

            // Caso você gere o ticket_number via Model/DB default, nada a fazer aqui.
            // Se preferir estratégia manual, mova a lógica para o Model/Service para evitar race condition.
            return $ticket;
        });

        $includes = $this->resolveIncludes(request()); // reaproveita ?include=
        return new TicketResource($ticket->load($includes));
    }

    /**
     * GET /tickets/{ticket}
     */
    public function show(Ticket $ticket, Request $request)
    {
        Gate::authorize('view', $ticket);

        $includes = $this->resolveIncludes($request);
        $ticket->load($includes);

        return new TicketResource($ticket);
    }

    /**
     * PUT/PATCH /tickets/{ticket}
     */
    public function update(UpdateTicketRequest $request, Ticket $ticket)
    {
        Gate::authorize('update', $ticket);

        $data = $request->validated();

        DB::transaction(function () use ($ticket, $data) {
            $ticket->fill($data);
            $ticket->save();
        });

        $includes = $this->resolveIncludes(request());
        return new TicketResource($ticket->load($includes));
    }

    /**
     * DELETE /tickets/{ticket}
     */
    public function destroy(Ticket $ticket)
    {
        Gate::authorize('delete', $ticket);

        $ticket->delete();

        return response()->noContent();
    }

    // ------------------------------------------------------------
    // Helpers
    // ------------------------------------------------------------

    /**
     * Converte ?include=a,b,c em uma lista segura de relações.
     */
    protected function resolveIncludes(Request $request): array
    {
        // Ajuste estes nomes para casar com os relationships do seu Model Ticket
        $allowed = [
            'category',
            'subcategory',
            'status',
            'requester',
            'openedBy',
            'assignee',        // ou 'assignedTo'
            'comments',
            'comments.user',
            'attachments',
        ];

        $raw = $request->string('include')->toString();
        if ($raw === '') {
            // Inclui o essencial por padrão
            return ['status', 'category', 'subcategory'];
        }

        $asked = array_filter(array_map('trim', explode(',', $raw)));
        return array_values(array_intersect($asked, $allowed));
    }

    /**
     * Campo de ordenação permitido.
     */
    protected function resolveSortField(?string $sort): string
    {
        $map = [
            'created_at'    => 'created_at',
            'updated_at'    => 'updated_at',
            'ticket_number' => 'ticket_number',
            'subject'       => 'subject',
            'priority'      => 'priority',   // se existir
        ];

        $key = ltrim((string) $sort, '-');
        return $map[$key] ?? 'created_at';
    }

    /**
     * Direção de ordenação a partir de "sort" (ex: -created_at = desc).
     */
    protected function resolveSortDir(?string $sort): string
    {
        return (is_string($sort) && str_starts_with($sort, '-')) ? 'desc' : 'asc';
    }
}
