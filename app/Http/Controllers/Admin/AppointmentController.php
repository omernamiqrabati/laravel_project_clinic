<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SupabaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AppointmentController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    public function index()
    {
        try {
            $appointments = $this->supabase->fetchTable('appointments');
            
            $patients = $this->supabase->fetchTable('patients');
            $dentists = $this->supabase->fetchTable('dentists');
            $treatments = $this->supabase->fetchTable('treatments');

            // Create lookup arrays
            $patientNames = collect($patients)->pluck('patient_id', 'patient_id')->toArray();
            $dentistNames = collect($dentists)->pluck('specialization', 'dentist_id')->toArray();
            $treatmentNames = collect($treatments)->pluck('name', 'treatment_id')->toArray();


            $patients_name = $this->supabase->fetchByQuery('user_profiles', [
                'role' => 'patient'
            ], ['first_name', 'last_name','id']);
            $dentists_name = $this->supabase->fetchByQuery('user_profiles', [
                'role' => 'dentist'
            ], ['first_name', 'last_name','id']);

            // // Enrich appointments with names
            // $appointments = array_map(function($app) use ($patientNames, $dentistNames, $treatmentNames, $dentists_name) {
            //     $dentistFirstName = 'Unknown';
            //     foreach ($dentists_name as $dentist) {
            //         if (isset($dentist['id']) && $dentist['id'] == $app['dentist_id']) {
            //             $dentistFirstName = $dentist['first_name'] ?? 'Unknown';
            //             break;
            //         }
            //     }


            // }, $appointments);

            return view('admin.appointments.index', [
                'dentists_name'=>$dentists_name,
                'patients_name'=>$patients_name,
                'appointments' => $appointments,
                'patients' => $patients,
                'dentists' => $dentists,
                'treatments' => $treatments
            ]);

        } catch (\Exception $e) {
            return back()->with('error', 'Error fetching appointments: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            $patients = $this->supabase->fetchTable('patients');
            $dentists = $this->supabase->fetchTable('dentists');
            $treatments = $this->supabase->fetchTable('treatments');

            return view('admin.appointments.create', [
                'patients' => $patients,
                'dentists' => $dentists,
                'treatments' => $treatments,
                'statuses' => ['Scheduled', 'Completed', 'Cancelled', 'Rescheduled']
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error fetching data: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'patient_id' => 'required|uuid',
                'dentist_id' => 'required|uuid',
                'treatment_id' => 'required|uuid',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
                'status' => 'required|string|in:Scheduled,Completed,Cancelled,Rescheduled',
                'notes' => 'nullable|string',
                'cancellation_reason' => 'nullable|string|required_if:status,Cancelled',
                'reschedule_reason' => 'nullable|string|required_if:status,Rescheduled'
            ]);

            $appointmentData = [
                'appointment_id' => (string) Str::uuid(),
                'patient_id' => $validatedData['patient_id'],
                'dentist_id' => $validatedData['dentist_id'],
                'treatment_id' => $validatedData['treatment_id'],
                'start_time' => $validatedData['start_time'],
                'end_time' => $validatedData['end_time'],
                'status' => $validatedData['status'],
                'notes' => $validatedData['notes'] ?? null,
                'cancellation_reason' => $validatedData['cancellation_reason'] ?? null,
                'reschedule_reason' => $validatedData['reschedule_reason'] ?? null,
                'created_at' => now(),
                'updated_at' => now()
            ];

            $this->supabase->insert_table('appointments', $appointmentData);

            return redirect()->route('admin.appointments.index')->with('success', 'Appointment created successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error creating appointment: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $appointment = $this->supabase->fetchById('appointments', $id);
            $patients = $this->supabase->fetchTable('patients');
            $dentists = $this->supabase->fetchTable('dentists');
            $treatments = $this->supabase->fetchTable('treatments');
            $patients_name = $this->supabase->fetchByQuery('user_profiles', [
                'role' => 'patient'
            ], ['first_name', 'last_name','id']);
            $dentists_name = $this->supabase->fetchByQuery('user_profiles', [
                'role' => 'dentist'
            ], ['first_name', 'last_name','id']);

            return view('admin.appointments.edit', [
                'appointment' => $appointment,
                'patients' => $patients,
                'dentists' => $dentists,
                'treatments' => $treatments,
                'dentists_name'=>$dentists_name,
                'patients_name'=>$patients_name,
                'statuses' => ['Scheduled', 'Completed', 'Cancelled', 'Rescheduled']
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error fetching appointment: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validatedData = $request->validate([
                'patient_id' => 'required|uuid',
                'dentist_id' => 'required|uuid',
                'treatment_id' => 'required|uuid',
                'start_time' => 'required|date',
                'end_time' => 'required|date|after:start_time',
                'status' => 'required|string|in:Scheduled,Completed,Cancelled,Rescheduled',
                'notes' => 'nullable|string',
                'cancellation_reason' => 'nullable|string|required_if:status,Cancelled',
                'reschedule_reason' => 'nullable|string|required_if:status,Rescheduled'
            ]);

            $updateData = [
                'patient_id' => $validatedData['patient_id'],
                'dentist_id' => $validatedData['dentist_id'],
                'treatment_id' => $validatedData['treatment_id'],
                'start_time' => $validatedData['start_time'],
                'end_time' => $validatedData['end_time'],
                'status' => $validatedData['status'],
                'notes' => $validatedData['notes'] ?? null,
                'cancellation_reason' => $validatedData['cancellation_reason'] ?? null,
                'reschedule_reason' => $validatedData['reschedule_reason'] ?? null,
                'updated_at' => now()
            ];

            $this->supabase->updateById('appointments', $id, $updateData);
            
            return redirect()->route('admin.appointments.index')->with('success', 'Appointment updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating appointment: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $this->supabase->deleteById('appointments', $id);
            return redirect()->route('admin.appointments.index')->with('success', 'Appointment deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting appointment: ' . $e->getMessage());
        }
    }
}