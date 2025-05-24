<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class TreatmentController extends Controller
{
    // Static list of treatments
    private $treatments = [
        [
            'id' => 1,
            'name' => 'Teeth Cleaning',
            'description' => 'A professional cleaning to remove plaque and tartar.',
            'cost' => 50.00,
        ],
        [
            'id' => 2,
            'name' => 'Cavity Filling',
            'description' => 'Filling cavities caused by tooth decay.',
            'cost' => 120.00,
        ],
        [
            'id' => 3,
            'name' => 'Root Canal',
            'description' => 'Treatment for infection at the center of a tooth.',
            'cost' => 300.00,
        ],
        // Add more treatments as needed
    ];

    public function index()
    {
        return view('admin.treatments.index', ['treatments' => $this->treatments]);
    }

    public function create()
    {
        return view('admin.treatments.create');
    }

    public function store()
    {
        $newTreatment = [
            'id' => count($this->treatments) + 1,
            'name' => request('name'),
            'description' => request('description'),
            'cost' => request('cost'),
        ];

        $this->treatments[] = $newTreatment;

        return redirect()->route('admin.treatments.index');
    }

    public function edit($id)
    {
        $treatment = collect($this->treatments)->firstWhere('id', $id);
        return view('admin.treatments.edit', compact('treatment'));
    }

    public function update($id)
    {
        $index = array_search($id, array_column($this->treatments, 'id'));
        if ($index === false) return redirect()->route('admin.treatments.index');

        $this->treatments[$index]['name'] = request('name');
        $this->treatments[$index]['description'] = request('description');
        $this->treatments[$index]['cost'] = request('cost');

        return redirect()->route('admin.treatments.index');
    }

    public function destroy($id)
    {
        $this->treatments = array_filter($this->treatments, fn($treatment) => $treatment['id'] != $id);
        return redirect()->route('admin.treatments.index');
    }
}
