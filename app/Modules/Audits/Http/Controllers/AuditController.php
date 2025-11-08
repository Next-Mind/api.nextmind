<?php

namespace App\Modules\Audits\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Appointments\Models\Appointment;
use App\Modules\Appointments\Models\Availability;
use App\Modules\Audits\Http\Resources\AuditResource;
use App\Modules\Audits\Models\Audit;
use App\Modules\Posts\Models\Post;
use App\Modules\Users\Models\User;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    private const DEFAULT_PER_PAGE = 15;
    private const MAX_PER_PAGE = 100;

    /**
     * Mapping between friendly type identifiers and auditable model classes.
     */
    private const AUDITABLE_TYPES = [
        'user' => User::class,
        'users' => User::class,
        'appointment' => Appointment::class,
        'appointments' => Appointment::class,
        'availability' => Availability::class,
        'availabilities' => Availability::class,
        'post' => Post::class,
        'posts' => Post::class,
    ];

    public function index(Request $request)
    {
        $perPage = $this->resolvePerPage($request->integer('per_page'));

        $audits = Audit::query()
            ->with('user')
            ->latest('created_at')
            ->paginate($perPage);

        return AuditResource::collection($audits);
    }

    public function history(Request $request, string $type, string $id)
    {
        $perPage = $this->resolvePerPage($request->integer('per_page'));

        $auditableTypes = $this->resolveAuditableType($type);

        $audits = Audit::query()
            ->with('user')
            ->where('auditable_type', $auditableType)
            ->where('auditable_id', $id)
            ->latest('created_at')
            ->paginate($perPage);

        return AuditResource::collection($audits);
    }

    private function resolvePerPage(?int $perPage): int
    {
        if ($perPage === null || $perPage <= 0) {
            return self::DEFAULT_PER_PAGE;
        }

        return min($perPage, self::MAX_PER_PAGE);
    }

    private function resolveAuditableType(string $type): array
    {
        $normalized = strtolower($type);
        $resolvedTypes = [];

        $appendResolvedTypes = static function (string $class, string ...$aliases) use (&$resolvedTypes): void {
            $resolvedTypes[] = $class;

            foreach ($aliases as $alias) {
                if ($alias !== '') {
                    $resolvedTypes[] = $alias;
                }
            }

            $morphAlias = Relation::getMorphAlias($class);
            if ($morphAlias !== null) {
                $resolvedTypes[] = $morphAlias;
            }
        };

        if (array_key_exists($normalized, self::AUDITABLE_TYPES)) {
            $class = self::AUDITABLE_TYPES[$normalized];
            $appendResolvedTypes($class, $normalized, $type !== $normalized ? $type : '');

            return array_values(array_unique($resolvedTypes));
        }

        $morphed = Relation::getMorphedModel($normalized);

        if ($morphed === null) {
            $morphed = Relation::getMorphedModel($type);
        }

        if ($morphed !== null) {
            $appendResolvedTypes($morphed, $normalized, $type !== $normalized ? $type : '');

            return array_values(array_unique($resolvedTypes));
        }

        if (class_exists($type)) {
            $appendResolvedTypes($type, $normalized, $type !== $normalized ? $type : '');

            return array_values(array_unique($resolvedTypes));
        }

        abort(404, 'Auditable type not supported.');
    }
}
