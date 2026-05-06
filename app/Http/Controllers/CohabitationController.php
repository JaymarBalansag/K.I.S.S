<?php

namespace App\Http\Controllers;

use App\Models\Cohabitation;
use App\Models\Cohabitation_Partner;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CohabitationController extends Controller
{
    private function formatIndexCollection($paginated)
    {
        $paginated->setCollection(
            $paginated->getCollection()->map(function (Cohabitation $cohabitation) {
                $partners = $cohabitation->partners ?? collect();
                $groom = $partners->firstWhere('partner_type', 'groom');
                $bride = $partners->firstWhere('partner_type', 'bride');

                $formatName = function ($partner) {
                    if (!$partner) return '';
                    return trim(implode(' ', array_filter([
                        $partner->first_name,
                        $partner->middle_name,
                        $partner->last_name,
                        $partner->suffix,
                    ], fn ($value) => filled($value))));
                };

                return [
                    'id' => $cohabitation->id,
                    'control_number' => $cohabitation->control_number,
                    'groom_name' => $formatName($groom),
                    'bride_name' => $formatName($bride),
                    'cohabitation_start_date' => $cohabitation->cohabitation_start_date,
                    'created_at' => $cohabitation->created_at,
                    'deleted_at' => $cohabitation->deleted_at,
                ];
            })
        );

        return $paginated;
    }

    // Insert Cohabitation Information
    public function insertCohabitation(Request $request)
    {
        $validated = $request->validate([
            'residence' => 'required|string|max:2000',

            // From UI: <input type="date" />
            'cohabitation_start_date' => 'required|date',

            'form' => 'required|array',
            'form.groom' => 'required|array',
            'form.bride' => 'required|array',

            'form.groom.first_name' => 'required|string|max:255',
            'form.groom.middle_name' => 'nullable|string|max:255',
            'form.groom.last_name' => 'required|string|max:255',
            'form.groom.suffix' => 'nullable|string|max:255',
            'form.groom.id_type' => 'required|string|max:255',
            'form.groom.id_number' => 'required|string|max:255',
            'form.groom.issued_at' => 'required|string|max:255',
            'form.groom.issued_on' => 'required|date',

            'form.bride.first_name' => 'required|string|max:255',
            'form.bride.middle_name' => 'nullable|string|max:255',
            'form.bride.last_name' => 'required|string|max:255',
            'form.bride.suffix' => 'nullable|string|max:255',
            'form.bride.id_type' => 'required|string|max:255',
            'form.bride.id_number' => 'required|string|max:255',
            'form.bride.issued_at' => 'required|string|max:255',
            'form.bride.issued_on' => 'required|date',
        ]);

        $record = DB::transaction(function () use ($validated) {
            $cohabitation = Cohabitation::create([
                'control_number' => $this->generateControlNumber(),
                'residence' => $validated['residence'],
                'cohabitation_start_date' => Carbon::parse($validated['cohabitation_start_date'])->toDateString(),
            ]);

            Cohabitation_Partner::create([
                'cohabitation_id' => $cohabitation->id,
                'partner_type' => 'groom',
                'first_name' => $validated['form']['groom']['first_name'],
                'middle_name' => $validated['form']['groom']['middle_name'] ?? null,
                'suffix' => $validated['form']['groom']['suffix'] ?? null,
                'last_name' => $validated['form']['groom']['last_name'],
                'id_type' => $validated['form']['groom']['id_type'],
                'id_number' => $validated['form']['groom']['id_number'],
                'issued_at' => $validated['form']['groom']['issued_at'],
                'issued_on' => Carbon::parse($validated['form']['groom']['issued_on'])->toDateString(),

            ]);

            Cohabitation_Partner::create([
                'cohabitation_id' => $cohabitation->id,
                'partner_type' => 'bride',
                'first_name' => $validated['form']['bride']['first_name'],
                'middle_name' => $validated['form']['bride']['middle_name'] ?? null,
                'suffix' => $validated['form']['bride']['suffix'] ?? null,
                'last_name' => $validated['form']['bride']['last_name'],
                'id_type' => $validated['form']['bride']['id_type'],
                'id_number' => $validated['form']['bride']['id_number'],
                'issued_at' => $validated['form']['bride']['issued_at'],
                'issued_on' => Carbon::parse($validated['form']['bride']['issued_on'])->toDateString(),
            ]);

            return $cohabitation->load('partners');
        });

        return response()->json([
            'message' => 'Cohabitation submitted successfully.',
            'data' => $record,
        ], 201);
    }

    private function generateControlNumber(): string
    {
        do {
            $controlNumber = Str::upper(Str::random(6));
        } while (Cohabitation::query()->where('control_number', $controlNumber)->exists());

        return $controlNumber;
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'order' => ['nullable', Rule::in(['asc', 'desc'])],
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $order = $validated['order'] ?? 'desc';
        $perPage = $validated['per_page'] ?? 15;
        $search = $validated['search'] ?? null;

        $query = Cohabitation::query()
            ->with('partners')
            ->orderBy('id', $order);

        if (filled($search)) {
            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('control_number', 'like', "%{$search}%")
                    ->orWhere('residence', 'like', "%{$search}%")
                    ->orWhereHas('partners', function ($partnerQuery) use ($search) {
                        $partnerQuery
                            ->where('first_name', 'like', "%{$search}%")
                            ->orWhere('middle_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('id_number', 'like', "%{$search}%")
                            ->orWhere('issued_at', 'like', "%{$search}%");
                    });
            });
        }

        $paginated = $query->paginate($perPage);
        $paginated = $this->formatIndexCollection($paginated);

        return response()->json([
            'data' => $paginated,
        ]);
    }

    public function trash(Request $request)
    {
        $validated = $request->validate([
            'search' => 'nullable|string|max:255',
            'order' => ['nullable', Rule::in(['asc', 'desc'])],
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $order = $validated['order'] ?? 'desc';
        $perPage = $validated['per_page'] ?? 15;
        $search = $validated['search'] ?? null;

        $query = Cohabitation::onlyTrashed()
            ->with('partners')
            ->orderBy('id', $order);

        if (filled($search)) {
            $query->where(function ($builder) use ($search) {
                $builder
                    ->where('control_number', 'like', "%{$search}%")
                    ->orWhere('residence', 'like', "%{$search}%")
                    ->orWhereHas('partners', function ($partnerQuery) use ($search) {
                        $partnerQuery
                            ->where('first_name', 'like', "%{$search}%")
                            ->orWhere('middle_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('id_number', 'like', "%{$search}%")
                            ->orWhere('issued_at', 'like', "%{$search}%");
                    });
            });
        }

        $paginated = $query->paginate($perPage);
        $paginated = $this->formatIndexCollection($paginated);

        return response()->json([
            'data' => $paginated,
        ]);
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Cohabitation $cohabitation)
    {
        return response()->json([
            'data' => $cohabitation->load('partners'),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cohabitation $cohabitation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cohabitation $cohabitation)
    {
        $rules = [
            'residence' => 'required|string|max:2000',
            'form' => 'required|array',
            'form.groom' => 'required|array',
            'form.bride' => 'required|array',

            'form.groom.id_type' => 'required|string|max:255',
            'form.groom.id_number' => 'required|string|max:255',
            'form.groom.issued_at' => 'required|string|max:255',
            'form.groom.issued_on' => 'required|date',

            'form.bride.id_type' => 'required|string|max:255',
            'form.bride.id_number' => 'required|string|max:255',
            'form.bride.issued_at' => 'required|string|max:255',
            'form.bride.issued_on' => 'required|date',
        ];

        if ($request->user() && $request->user()->role === 'admin') {
            $rules['cohabitation_start_date'] = 'required|date';
        }

        $validated = $request->validate($rules);

        $record = DB::transaction(function () use ($validated, $cohabitation) {
            $updatePayload = [
                'residence' => $validated['residence'],
            ];

            if (array_key_exists('cohabitation_start_date', $validated)) {
                $updatePayload['cohabitation_start_date'] = Carbon::parse($validated['cohabitation_start_date'])->toDateString();
            }

            $cohabitation->update($updatePayload);

            foreach (['groom', 'bride'] as $type) {
                $partner = $cohabitation->partners()->where('partner_type', $type)->first();
                if (!$partner) {
                    abort(422, "Missing {$type} partner record for this cohabitation.");
                }

                $partner->update([
                    'id_type' => $validated['form'][$type]['id_type'],
                    'id_number' => $validated['form'][$type]['id_number'],
                    'issued_at' => $validated['form'][$type]['issued_at'],
                    'issued_on' => Carbon::parse($validated['form'][$type]['issued_on'])->toDateString(),
                ]);
            }

            return $cohabitation->load('partners');
        });

        return response()->json([
            'message' => 'Cohabitation updated successfully.',
            'data' => $record,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cohabitation $cohabitation)
    {
        $cohabitation->delete();

        return response()->json([
            'message' => 'Cohabitation moved to trash.',
        ]);
    }

    public function restore(string $id)
    {
        $cohabitation = Cohabitation::withTrashed()->findOrFail($id);
        $cohabitation->restore();

        return response()->json([
            'message' => 'Cohabitation restored successfully.',
        ]);
    }

    public function forceDestroy(string $id)
    {
        $cohabitation = Cohabitation::withTrashed()->findOrFail($id);
        $cohabitation->forceDelete();

        return response()->json([
            'message' => 'Cohabitation permanently deleted.',
        ]);
    }
}
