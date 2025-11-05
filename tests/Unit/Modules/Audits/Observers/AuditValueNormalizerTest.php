<?php

namespace Tests\Unit\Modules\Audits\Observers;

use App\Modules\Audits\Observers\AuditValueNormalizer;
use DateTimeInterface;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\TestCase;

class AuditValueNormalizerTest extends TestCase
{
    public function testExtractValuesNormalizesDatesAndPreservesNulls(): void
    {
        $observer = new class {
            use AuditValueNormalizer;

            private const AUDITABLE_ATTRIBUTES = ['starts_at', 'name', 'notes'];
            private const DATE_ATTRIBUTES = ['starts_at'];

            public function extract(array $attributes): ?array
            {
                return $this->extractValues($attributes);
            }
        };

        $startsAt = Carbon::create(2024, 6, 1, 10, 30, 0, 'UTC');

        $values = $observer->extract([
            'starts_at' => $startsAt,
            'name' => 'Session',
            'notes' => null,
            'ignored' => 'value',
        ]);

        $this->assertSame([
            'starts_at' => $startsAt->toAtomString(),
            'name' => 'Session',
            'notes' => null,
        ], $values);
    }

    public function testNormalizeValueParsesDateStrings(): void
    {
        $observer = new class {
            use AuditValueNormalizer;

            private const AUDITABLE_ATTRIBUTES = [];
            private const DATE_ATTRIBUTES = ['starts_at'];

            public function normalize(string $key, mixed $value): mixed
            {
                return $this->normalizeValue($key, $value);
            }
        };

        $result = $observer->normalize('starts_at', '2024-06-01 10:30:00');

        $expected = Carbon::parse('2024-06-01 10:30:00')->format(DateTimeInterface::ATOM);

        $this->assertSame($expected, $result);
    }

    public function testNormalizeValuesReturnsNullWhenInputIsNull(): void
    {
        $observer = new class {
            use AuditValueNormalizer;

            private const AUDITABLE_ATTRIBUTES = [];
            private const DATE_ATTRIBUTES = [];

            public function normalize(?array $values): ?array
            {
                return $this->normalizeValues($values);
            }
        };

        $this->assertNull($observer->normalize(null));
    }
}
