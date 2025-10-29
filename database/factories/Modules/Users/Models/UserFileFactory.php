<?php

namespace Database\Factories\Modules\Users\Models;

use App\Modules\Users\Models\User;
use App\Modules\Users\Models\UserFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFileFactory extends Factory
{
    protected $model = UserFile::class;

    public function definition(): array
    {
        return [
            'purpose'        => 'psychologist_doc',
            'mime_type'      => 'application/pdf',
            'original_name'  => $this->faker->randomElement([
                'crp_card.pdf',
                'id_front.pdf',
                'id_back.pdf',
                'proof_of_address.pdf',
            ]),
        ];
    }

    public function configure()
    {
        return $this
            ->afterMaking(function (UserFile $file) {

                $owner = $file->user ?? $file->relationLoaded('user') ? $file->user : null;

                if (!$owner) {
                    throw new \RuntimeException(
                        'UserFileFactory precisa de user associado (use ->forOwner($user)).'
                    );
                }

                if (!$file->original_name) {
                    $file->original_name = 'doc-' . Str::uuid() . '.pdf';
                }

                $file->path = 'uploads/' . $owner->getKey() . '/psychologist_doc/' . $file->original_name;
            })
            ->afterCreating(function (UserFile $file) {

                $owner = $file->user;
                $disk  = 'local';

                // garantir pasta
                Storage::disk($disk)->makeDirectory(
                    dirname($file->path)
                );

                $pdfBinary = $this->fakePdfForUser(
                    $owner->getKey(),
                    $owner->name,
                    $file->original_name
                );

                Storage::disk($disk)->put($file->path, $pdfBinary);
            });
    }

    public function forOwner(User $user): static
    {
        return $this->for($user, 'user');
    }

    public function pdfNamed(string $name): static
    {
        return $this->state(fn() => ['original_name' => $name]);
    }

    protected function fakePdfForUser(string $userId, string $userName, string $fileName): string
    {
        $textLines = [
            "Psychologist Document (TEST)",
            "User ID: {$userId}",
            "User Name: {$userName}",
            "File Name: {$fileName}",
            "Generated at: " . now()->toDateTimeString(),
        ];

        $y = 750;
        $contentStreamLines = [];
        foreach ($textLines as $line) {
            $contentStreamLines[] = "1 0 0 1 50 {$y} Tm ({$this->escapePdfText($line)}) Tj";
            $y -= 20;
        }
        $contentStream = "BT /F1 12 Tf\n" . implode("\n", $contentStreamLines) . "\nET";


        $obj1 = "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n";
        $obj2 = "2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n";
        $obj3 = "3 0 obj\n<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Contents 5 0 R /Resources << /Font << /F1 4 0 R >> >> >>\nendobj\n";
        $obj4 = "4 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\nendobj\n";

        $len = strlen($contentStream);
        $obj5 = "5 0 obj\n<< /Length {$len} >>\nstream\n{$contentStream}\nendstream\nendobj\n";

        $pdfBody = $obj1 . $obj2 . $obj3 . $obj4 . $obj5;

        $pdfHeader = "%PDF-1.4\n";
        $cursor = strlen($pdfHeader);

        $offsets = [];

        $pieces = [
            $obj1,
            $obj2,
            $obj3,
            $obj4,
            $obj5,
        ];

        foreach ($pieces as $piece) {
            $offsets[] = $cursor;
            $cursor += strlen($piece);
        }

        $xrefStart = $cursor;

        $xref = "xref\n0 6\n";
        $xref .= "0000000000 65535 f \n";
        for ($i = 0; $i < count($offsets); $i++) {
            $xref .= sprintf("%010d 00000 n \n", $offsets[$i]);
        }

        $trailer = "trailer\n<< /Size 6 /Root 1 0 R >>\nstartxref\n{$xrefStart}\n%%EOF\n";

        return $pdfHeader . $pdfBody . $xref . $trailer;
    }

    protected function escapePdfText(string $text): string
    {
        return str_replace(
            ['\\', '(', ')'],
            ['\\\\', '\(', '\)'],
            $text
        );
    }
}
