<?php

namespace App\Http\Controllers\Helpdesk;

use Illuminate\Http\Request;
use App\Models\Tickets\Ticket;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class TicketController extends Controller
{
    public function __construct()
    {
        
    }

    /**
     * GET /tickets
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // já está coberto pela policy->viewAny()
        Gate::authorize('viewAny', Ticket::class);

        $query = Ticket::query();

        // Se NÃO pode ver todos, limita aos próprios
        if (! $user->can('helpdesk.tickets.view.any')) {
            $query->where('user_id', $user->id);
        }

        // filtros básicos opcionais (status, categoria, etc.)
        if ($statusId = $request->query('status_id')) {
            $query->where('status_id', $statusId);
        }

        if ($categoryId = $request->query('category_id')) {
            $query->where('category_id', $categoryId);
        }

        $tickets = $query
            ->latest('created_at')
            ->paginate($request->query('per_page', 15));

        return response()->json($tickets);
    }

    /**
     * POST /tickets
     */
    public function store(Request $request)
    {
        Gate::authorize('create', Ticket::class);

        // validação básica (ajuste depois)
        $data = $request->validate([
            'title'            => ['required','string','max:255'],
            'description'      => ['required','string'],
            'category_id'      => ['required','integer','exists:ticket_categories,id'],
            'subcategory_id'   => ['nullable','integer','exists:ticket_subcategories,id'],
            'status_id'        => ['required','integer','exists:ticket_statuses,id'],
            // ... mais campos que existirem na sua migration tickets
        ]);

        $data['user_id'] = Auth::id();

        $ticket = Ticket::create($data);

        return response()->json($ticket, 201);
    }

    /**
     * GET /tickets/{ticket}
     */
    public function show(Ticket $ticket)
    {
        Gate::authorize('view', $ticket);
        return response()->json($ticket);
    }

    /**
     * PUT/PATCH /tickets/{ticket}
     */
    public function update(Request $request, Ticket $ticket)
    {
        Gate::authorize('update',$ticket);

        $data = $request->validate([
            'title'            => ['sometimes','string','max:255'],
            'description'      => ['sometimes','string'],
            'category_id'      => ['sometimes','integer','exists:ticket_categories,id'],
            'subcategory_id'   => ['sometimes','integer','exists:ticket_subcategories,id'],
            'status_id'        => ['sometimes','integer','exists:ticket_statuses,id'],
            // etc.
        ]);

        $ticket->fill($data)->save();

        return response()->json($ticket);
    }

    /**
     * DELETE /tickets/{ticket}
     */
    public function destroy(Ticket $ticket)
    {
        Gate::authorize('delete', $ticket);

        $ticket->delete();

        return response()->json([
            'message' => 'Ticket deleted',
        ]);
    }
}
