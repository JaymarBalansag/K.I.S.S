<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreManualMarriageLicenseApplicationRequest;
use App\Http\Resources\ManualMarriageLicenseApplicationResource;
use App\Models\ManualMarriageLicenseApplication;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ManualMarriageLicenseApplicationController extends Controller
{
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
        ]);

        $query = ManualMarriageLicenseApplication::query()->orderByDesc('id');

        if (!empty($validated['search'])) {
            $search = $validated['search'];
            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('control_number', 'like', "%{$search}%")
                    ->orWhere('groom_first_name', 'like', "%{$search}%")
                    ->orWhere('groom_last_name', 'like', "%{$search}%")
                    ->orWhere('bride_first_name', 'like', "%{$search}%")
                    ->orWhere('bride_last_name', 'like', "%{$search}%");
            });
        }

        return ManualMarriageLicenseApplicationResource::collection($query->get());
    }

    public function store(StoreManualMarriageLicenseApplicationRequest $request)
    {
        $validated = $request->validated();

        $record = ManualMarriageLicenseApplication::create([
            'control_number' => $this->generateControlNumber(),
            'staff_id' => $request->user()?->id,
            'phone_number' => $validated['phone_number'] ?? null,
            ...$this->flattenPersonData('groom', $validated['groom']),
            ...$this->flattenPersonData('bride', $validated['bride']),
        ]);

        return (new ManualMarriageLicenseApplicationResource($record))
            ->additional([
                'message' => 'Manual marriage license application stored successfully.',
            ])
            ->response()
            ->setStatusCode(201);
    }

    public function show(ManualMarriageLicenseApplication $manualMarriageLicenseApplication)
    {
        return new ManualMarriageLicenseApplicationResource($manualMarriageLicenseApplication);
    }

    public function previewPdf(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|integer',
            'control_number' => 'required|string',
        ]);

        $record = ManualMarriageLicenseApplication::query()
            ->where('id', $validated['id'])
            ->where('control_number', $validated['control_number'])
            ->first();

        if (!$record) {
            return response()->json([
                'message' => 'Manual application not found.',
            ], 404);
        }

        $pdf = Pdf::loadView('pdf.8x13', $this->buildFormData($record))
            ->setPaper([0, 0, 612, 936], 'portrait');

        return $pdf->stream('Manual_Marriage_8x13.pdf');
    }

    private function flattenPersonData(string $prefix, array $person): array
    {
        return [
            "{$prefix}_first_name" => $person['first_name'],
            "{$prefix}_middle_name" => $person['middle_name'] ?? null,
            "{$prefix}_last_name" => $person['last_name'],
            "{$prefix}_birth_date" => $person['birth_date'],
            "{$prefix}_age" => $person['age'],
            "{$prefix}_birth_city" => $person['birth_city'],
            "{$prefix}_birth_province" => $person['birth_province'],
            "{$prefix}_birth_country" => $person['birth_country'],
            "{$prefix}_sex" => $person['sex'],
            "{$prefix}_citizenship" => $person['citizenship'],
            "{$prefix}_religion" => $person['religion'],
            "{$prefix}_civil_status" => $person['civil_status'],
            "{$prefix}_residence_address" => $person['residence_address'],
            "{$prefix}_dissolution_details" => $person['dissolution_details'] ?? null,
            "{$prefix}_dissolution_place" => $person['dissolution_place'] ?? null,
            "{$prefix}_dissolution_date" => $person['dissolution_date'] ?? null,
            "{$prefix}_relationship_degree" => $person['relationship_degree'] ?? null,
            "{$prefix}_father_first_name" => $person['father_first_name'],
            "{$prefix}_father_middle_name" => $person['father_middle_name'] ?? null,
            "{$prefix}_father_last_name" => $person['father_last_name'],
            "{$prefix}_father_citizenship" => $person['father_citizenship'],
            "{$prefix}_father_residence" => $person['father_residence'],
            "{$prefix}_mother_first_name" => $person['mother_first_name'],
            "{$prefix}_mother_middle_name" => $person['mother_middle_name'] ?? null,
            "{$prefix}_mother_last_name" => $person['mother_last_name'],
            "{$prefix}_mother_citizenship" => $person['mother_citizenship'],
            "{$prefix}_mother_residence" => $person['mother_residence'],
            "{$prefix}_consent_name" => $person['consent_name'] ?? null,
            "{$prefix}_consent_relationship" => $person['consent_relationship'] ?? null,
            "{$prefix}_consent_citizenship" => $person['consent_citizenship'] ?? null,
            "{$prefix}_consent_residence" => $person['consent_residence'] ?? null,
            "{$prefix}_id_type" => $person['id_type'] ?? null,
            "{$prefix}_id_number" => $person['id_number'] ?? null,
        ];
    }

    private function generateControlNumber(): string
    {
        do {
            $controlNumber = 'MAN-' . now()->format('Ymd') . '-' . Str::upper(Str::random(5));
        } while (ManualMarriageLicenseApplication::query()->where('control_number', $controlNumber)->exists());

        return $controlNumber;
    }

    private function buildFormData(ManualMarriageLicenseApplication $record): array
    {
        $submittedDate = $record->created_at ? Carbon::parse($record->created_at) : now();
        $ordinalDay = $this->toOrdinalDay((int) $submittedDate->day);

        return [
            'meta' => [
                'province' => 'LEYTE',
                'municipality' => 'ABUYOG',
                'received_by' => 'MUNICIPAL CIVIL REGISTRAR',
                'date' => $submittedDate->format('M d, Y'),
            ],
            'groom' => $this->buildApplicantData($record, 'groom', $submittedDate, $ordinalDay),
            'bride' => $this->buildApplicantData($record, 'bride', $submittedDate, $ordinalDay),
        ];
    }

    private function buildApplicantData(ManualMarriageLicenseApplication $record, string $prefix, Carbon $submittedDate, string $ordinalDay): array
    {
        $birthDate = data_get($record, "{$prefix}_birth_date");
        $dissolutionDate = data_get($record, "{$prefix}_dissolution_date");
        $firstName = (string) data_get($record, "{$prefix}_first_name");
        $middleName = (string) (data_get($record, "{$prefix}_middle_name") ?? '');
        $lastName = (string) data_get($record, "{$prefix}_last_name");

        return [
            'full_name' => $this->formatName($firstName, $middleName, $lastName),
            'name_first' => $firstName,
            'name_middle' => $middleName,
            'name_last' => $lastName,
            'birth_date' => $birthDate ? Carbon::parse($birthDate)->format('d F Y') : '',
            'age' => data_get($record, "{$prefix}_age"),
            'birthplace' => $this->joinComma([
                data_get($record, "{$prefix}_birth_city"),
                data_get($record, "{$prefix}_birth_province"),
                data_get($record, "{$prefix}_birth_country"),
            ]),
            'sex' => data_get($record, "{$prefix}_sex"),
            'citizenship' => data_get($record, "{$prefix}_citizenship"),
            'residence' => data_get($record, "{$prefix}_residence_address"),
            'religion' => data_get($record, "{$prefix}_religion"),
            'civil_status' => data_get($record, "{$prefix}_civil_status"),
            'if_married' => data_get($record, "{$prefix}_dissolution_details") ?: 'NOT APPLICABLE',
            'place_dissolved' => data_get($record, "{$prefix}_dissolution_place") ?: 'NOT APPLICABLE',
            'date_dissolved' => $dissolutionDate ? Carbon::parse($dissolutionDate)->format('d F Y') : 'NOT APPLICABLE',
            'relationship' => data_get($record, "{$prefix}_relationship_degree") ?: 'NOT APPLICABLE',
            'father_name' => $this->formatName(
                data_get($record, "{$prefix}_father_first_name"),
                data_get($record, "{$prefix}_father_middle_name"),
                data_get($record, "{$prefix}_father_last_name")
            ),
            'father_citizenship' => data_get($record, "{$prefix}_father_citizenship"),
            'father_residence' => data_get($record, "{$prefix}_father_residence"),
            'mother_name' => $this->formatName(
                data_get($record, "{$prefix}_mother_first_name"),
                data_get($record, "{$prefix}_mother_middle_name"),
                data_get($record, "{$prefix}_mother_last_name")
            ),
            'mother_citizenship' => data_get($record, "{$prefix}_mother_citizenship"),
            'mother_residence' => data_get($record, "{$prefix}_mother_residence"),
            'give_consent' => data_get($record, "{$prefix}_consent_name") ?: 'NOT APPLICABLE',
            'give_consent_relationship' => data_get($record, "{$prefix}_consent_relationship") ?: 'NOT APPLICABLE',
            'give_consent_citizenship' => data_get($record, "{$prefix}_consent_citizenship") ?: 'NOT APPLICABLE',
            'give_consent_residence' => data_get($record, "{$prefix}_consent_residence") ?: 'NOT APPLICABLE',
            'fullname_signature' => $this->formatName($firstName, $middleName, $lastName),
            'day_today' => $ordinalDay,
            'month_today' => strtoupper($submittedDate->format('F')),
            'year_today' => $submittedDate->format('Y'),
            'place' => 'Abuyog, Leyte',
            'id' => $this->formatId(
                data_get($record, "{$prefix}_id_type"),
                data_get($record, "{$prefix}_id_number")
            ),
            'issued_on' => $submittedDate->format('F d, Y'),
            'issued_at' => 'Abuyog, Leyte',
            'civil_registrar' => 'Atty. Madilyn Madolin-Merano',
        ];
    }

    private function formatName(?string $first, ?string $middle, ?string $last): string
    {
        return trim(implode(' ', array_filter([$first, $middle, $last], fn ($value) => filled($value))));
    }

    private function joinComma(array $values): string
    {
        return trim(implode(', ', array_filter($values, fn ($value) => filled($value))));
    }

    private function formatId(?string $type, ?string $number): string
    {
        if (filled($type) && filled($number)) {
            return $type . ': ' . $number;
        }

        return filled($number) ? $number : 'N/A';
    }

    private function toOrdinalDay(int $day): string
    {
        if ($day % 100 >= 11 && $day % 100 <= 13) {
            return $day . 'th';
        }

        return match ($day % 10) {
            1 => $day . 'st',
            2 => $day . 'nd',
            3 => $day . 'rd',
            default => $day . 'th',
        };
    }
}
