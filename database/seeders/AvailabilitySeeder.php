<?php
use App\Models\Appointments\Availability;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AvailabilitySeeder extends Seeder
{
    public function run(): void
    {
        $psychologists = User::query()
            ->whereHas('roles', fn ($q) => $q->where('name', 'psychologist'))
            ->get();

        $daysAhead = 14;
        $startHour = 9;
        $endHour   = 17; 

        foreach ($psychologists as $psy) {
            $slots = [];
            $set = [];

            for ($d = 1; $d <= $daysAhead; $d++) {
                $day = Carbon::now()->addDays($d)->startOfDay();

                $period = CarbonPeriod::create(
                    $day->copy()->setTime($startHour, 0, 0),
                    '1 hour',
                    $day->copy()->setTime($endHour, 0, 0)
                );

                foreach ($period as $dt) {
                    $dt = $dt->copy()->minute(0)->second(0);
                    $key = $psy->id.'|'.$dt->toDateTimeString();
                    if (isset($set[$key])) continue;
                    $set[$key] = true;

                    if (mt_rand(1, 100) > 80) continue;

                    $slots[] = [
                        'id' => (string) Str::uuid(),
                        'user_id' => $psy->id,
                        'reserved_by' => null,
                        'date_availability' => $dt,
                        'status' => 'available',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }

            if (!empty($slots)) {
                Availability::upsert(
                    $slots,
                    ['user_id', 'date_availability'],
                    ['status', 'reserved_by', 'updated_at']
                );
            }
        }
    }
}
