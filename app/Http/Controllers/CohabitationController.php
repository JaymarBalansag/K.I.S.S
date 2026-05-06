<?php

namespace App\Http\Controllers;

use App\Models\Cohabitation;
use App\Models\Cohabitation_Partner;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CohabitationController extends Controller
{
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
    public function index()
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cohabitation $cohabitation)
    {
        //
    }
}
