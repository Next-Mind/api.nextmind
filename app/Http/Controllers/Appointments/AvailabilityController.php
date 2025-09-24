<?php

namespace App\Http\Controllers\Appointments;

use App\Models\User;
use DateTimeImmutable;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Request;
use App\Models\Appointments\Availability;
use Illuminate\Validation\ValidationException;
use App\Domain\Appointments\Enums\AvailabilityStatus;
use App\Http\Requests\Appointments\StoreAvailabilityRequest;
use App\Http\Requests\Appointments\UpdateAvailabilityRequest;
use App\Http\Requests\Appointments\IndexAvailabilitiesRequest;
use App\Http\Resources\Appointments\PsychoAvailabilityResource;

class AvailabilityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(IndexAvailabilitiesRequest $request,User $psychologist)
    {
        $input = $request->validated();
        $start_date = new DateTimeImmutable($input['start_date']);
        $end_date = new DateTimeImmutable($input['end_date']);

        //

        $availabilities = Availability::query()
            ->forPsychologist($psychologist->id)
            ->between($start_date, $end_date)
            ->orderBy('date_availability')
            ->get();

        return PsychoAvailabilityResource::collection($availabilities);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAvailabilityRequest $request)
    {
        $user = $request->user();
        $dates = $request->validated('dates');

        $saved = [];
        $skipped = [];

        foreach ($dates as $raw) {
            $dt = \Carbon\Carbon::parse($raw)->seconds(0);

            // tenta inserir (único): ignora duplicados
            try {
                Availability::query()->create([
                    'user_id' => $user->id,
                    'date_availability'            => $dt,
                    'status'          => AvailabilityStatus::Available,
                ]);
                $saved[] = $dt->toDateTimeString();
            } catch (\Throwable $e) {

                $skipped[] = $dt->toDateTimeString();
            }
        }

        $status = empty($skipped) ? 201 : 207;

        $message = empty($skipped) ? 'Availability created successfully' : 'Some availability dates were skipped';

        return response()->json([
            'message' => $message,
            'data'    => compact('saved', 'skipped'),
        ], $status);
    }

     public function updateStatus(UpdateAvailabilityRequest $request, Availability $availability)
    {
        $statusEnum = AvailabilityStatus::from( $request->validated('status'));
        $status = $request->validated('status');


        $availability->status = $status;
        $availability->save();

        $updated = $availability->refresh();

        return new PsychoAvailabilityResource($updated);
    }

    // POST /availabilities/{availability}/schedule
    public function schedule(Request $request,Availability $availability)
    {
        $user = $request->user();

        if ($availability->date->isPast()) {
            throw ValidationException::withMessages([
                'availability' => 'This availability is in the past and cannot be scheduled.',
            ]);
        }

        // Atualização atômica (evita race condition):
        $updated = DB::transaction(function () use ($availability, $user) {
            $affected = Availability::query()
                ->whereKey($availability->id)
                ->where('status', AvailabilityStatus::Available)
                ->update([
                    'status'       => AvailabilityStatus::Reserved,
                    'scheduled_by' => $user->id,
                    'updated_at'   => now(),
                ]);

            return $affected === 1;
        });

        if (! $updated) {
            throw ValidationException::withMessages([
                'availability' => 'This availability is no longer available.',
            ]);
        }

        $reserved = $availability->refresh();

        return new PsychoAvailabilityResource($reserved);
    }

    /**
     * Display the specified resource.
     */
    public function show(Availability $availability)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Availability $availability)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAvailabilityRequest $request, Availability $availability)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Availability $availability)
    {
        //
    }
}
