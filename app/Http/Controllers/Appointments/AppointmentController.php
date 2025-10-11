<?php

namespace App\Http\Controllers\Appointments;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use App\Models\Appointments\Appointment;
use App\Models\Appointments\Availability;
use App\Domain\Appointments\Enums\AvailabilityStatus;
use App\Http\Resources\Appointments\AppointmentResource;
use App\Http\Requests\Appointments\StoreAppointmentRequest;
use App\Http\Requests\Appointments\UpdateAppointmentRequest;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $queryParams = $request->query();
        
        $q = Appointment::query()
        ->with(['availability', 'patient', 'psychologist']);
        
        if ($user->can('viewAny', Appointment::class)) {
        } elseif ($user->hasPermissionTo('appointments.view.assigned')) {
            $q->where('psychologist_id', $user->id);
        } elseif ($user->hasPermissionTo('appointments.view.self')) {
            $q->where('user_id', $user->id);
        } else {
            abort(403);
        }
        
        if ($status = $queryParams['status']) {
            $q->where('status', $status);
        }
        if ($psychId = $request->string('psychologist_id')->toString()) {
            $q->where('psychologist_id', $psychId);
        }
        if ($patientId = $request->string('user_id')->toString()) {
            $q->where('user_id', $patientId);
        }
        
        $q->orderByDesc('created_at');
        
        $appointments = $q->simplePaginate($request->integer('per_page', 15));
        
        return AppointmentResource::collection($appointments);
    }
    
    public function store(StoreAppointmentRequest $request)
    {
        Gate::authorize('create', Appointment::class);
        
        $data = $request->validated();
        $data['user_id'] = $data['user_id'] ?? auth()->id();
        
        $appointment = DB::transaction(function() use($data) {
            $availability = Availability::whereKey($data['availability_id'])
            ->lockForUpdate()
            ->firstOrFail();
            
            if ($availability->status !== AvailabilityStatus::Available) {
                abort(422, 'Horário indisponível.');
            }
            
            $availability->status = AvailabilityStatus::Reserved; 
            
            $availability->save();
            
            return Appointment::create($data);
        } );
        
        return new AppointmentResource(
            $appointment->load(['availability','patient','psychologist'])
        );
    }
    
    public function show(Appointment $appointment)
    {
        $appointment->load(['availability', 'patient', 'psychologist']);
        return new AppointmentResource($appointment);
    }
    
    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        Gate::authorize('update', $appointment);
        
        $data = $request->validated();
        
        \DB::transaction(function () use ($appointment, $data) {
            $appointment->fill($data)->save();
        });
        
        return new AppointmentResource(
            $appointment->fresh()->load(['availability','patient','psychologist'])
        );
    }
    
    public function destroy(Appointment $appointment)
    {
        Gate::authorize('delete', $appointment);
        
        DB::transaction(function () use ($appointment) {
            // trava a availability durante a operação
            $availability = $appointment->availability()->lockForUpdate()->first();
            
            if ($availability) {
                $availability->free(); 
            }
            
            $appointment->delete();
        });
        
        return response()->json(['deleted' => true]);;
    }
    
    public function book(StoreAppointmentRequest $request)
    {
        Gate::authorize('book', Appointment::class);
        
        return $this->store($request);
    }
    
    public function cancel(Request $request, Appointment $appointment)
    {
        Gate::authorize('cancel', $appointment);
        
        if (!in_array($appointment->status, ['pending','scheduled'], true)) {
            abort(422, 'Only pending/scheduled appointments can be canceled.');
        }
        
        DB::transaction(function () use ($appointment) {
            $availability = $appointment->availability()->lockForUpdate()->firstOrFail();
            
            $now = now(); 
            $start = Carbon::parse($availability->date_availability);
            if ($start->diffInMinutes($now, false) > -30) {
                abort(422, 'The appointment cannot be canceled less than 30 minutes before it starts.');
            }
            
            // Atualiza estados
            $appointment->status = 'canceled';
            $appointment->save();

            $availability->free();
        });
        
        return new AppointmentResource(
            $appointment->fresh(['availability','patient','psychologist'])
        );
    }
    
    public function perform(Request $request, Appointment $appointment)
    {
        Gate::authorize('perform', $appointment);
        
        if ($appointment->status !== 'scheduled') {
            abort(422, 'Only scheduled appointments can be performed.');
        }
        
        $appointment->status = 'completed';
        $appointment->save();
        
        return new AppointmentResource($appointment->fresh(['availability','patient','psychologist']));
    }
}
