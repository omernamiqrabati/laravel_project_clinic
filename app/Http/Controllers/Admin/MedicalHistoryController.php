<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MedicalHistoryController extends Controller
{
    private $medicalHistories = [
        [
            'id' => 1,
            'patient_name' => 'John Doe',
            'diagnosis' => 'Hypertension',
            'treatment' => 'Medication and lifestyle changes',
            'date' => '2025-03-15',
        ],
        [
            'id' => 2,
            'patient_name' => 'Jane Smith',
            'diagnosis' => 'Diabetes',
            'treatment' => 'Insulin therapy',
            'date' => '2025-04-10',
        ],
        // Add more records as needed
    ];

    public function index()
    {
        return view('admin.medical_histories.index', ['medicalHistories' => $this->medicalHistories]);
    }

    public function create()
    {
        return view('admin.medical_histories.create');
    }

    public function store(Request $request)
    {
        // Handle storing new medical history (for demonstration, we'll just redirect)
        return redirect()->route('admin.medical_histories.index');
    }

    public function edit($id)
    {
        $medicalHistory = collect($this->medicalHistories)->firstWhere('id', $id);
        return view('admin.medical_histories.edit', ['medicalHistory' => $medicalHistory]);
    }

    public function update(Request $request, $id)
    {
        // Handle updating medical history (for demonstration, we'll just redirect)
        return redirect()->route('admin.medical_histories.index');
    }

    public function destroy($id)
    {
        // Handle deleting medical history (for demonstration, we'll just redirect)
        return redirect()->route('admin.medical_histories.index');
    }
}
