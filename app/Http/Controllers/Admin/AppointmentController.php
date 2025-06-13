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
            $treatments_name = $this->supabase->fetchByQuery('treatments', [
            ], ['name','treatment_id']);


            return view('admin.appointments.index', [
                'dentists_name'=>$dentists_name,
                'patients_name'=>$patients_name,
                'appointments' => $appointments,
                'patients' => $patients,
                'dentists' => $dentists,
                'treatments' => $treatments,
                'treatments_name' => $treatments_name
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

            // Map form status values to database status values
            $statusMapping = [
                'Scheduled' => 'arranged',
                'Completed' => 'completed',
                'Cancelled' => 'cancelled',
                'Rescheduled' => 'arranged' // Rescheduled appointments are typically 'arranged' in the DB
            ];

            $data = [
                'patient_id' => $validatedData['patient_id'],
                'dentist_id' => $validatedData['dentist_id'],
                'treatment_id' => $validatedData['treatment_id'],
                'start_time' => $validatedData['start_time'],
                'end_time' => $validatedData['end_time'],
                'status' => $statusMapping[$validatedData['status']], // Map to correct DB status
                'notes' => $validatedData['notes'] ?? null,
                'cancellation_reason' => $validatedData['cancellation_reason'] ?? null,
                'reschedule_reason' => $validatedData['reschedule_reason'] ?? null,
                'created_by' => auth()->id() ?? $validatedData['patient_id'],
                'created_at' => now(),
                'updated_at' => null
            ];

            $this->supabase->insert('appointments', $data);

            return redirect()->route('admin.appointments.index')->with('success', 'Appointment created successfully');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Error creating appointment: ' . $e->getMessage());
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
            // First, check if the appointment exists
            $appointment = $this->supabase->fetchById('appointments', $id);
            if (empty($appointment)) {
                return back()->with('error', 'Appointment not found');
            }

            // Validate the form data from edit.blade.php
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

            // Log the incoming form data for debugging
            \Log::info('Appointment update form data received', [
                'appointment_id' => $id,
                'form_data' => $validatedData
            ]);

            // Validate that referenced records exist
            try {
                $patient = $this->supabase->fetchById('patients', $validatedData['patient_id']);
                if (empty($patient)) {
                    return back()->withInput()->with('error', 'Selected patient does not exist');
                }
            } catch (\Exception $e) {
                return back()->withInput()->with('error', 'Error validating patient: ' . $e->getMessage());
            }

            try {
                $dentist = $this->supabase->fetchById('dentists', $validatedData['dentist_id']);
                if (empty($dentist)) {
                    return back()->withInput()->with('error', 'Selected dentist does not exist');
                }
            } catch (\Exception $e) {
                return back()->withInput()->with('error', 'Error validating dentist: ' . $e->getMessage());
            }

            try {
                $treatment = $this->supabase->fetchById('treatments', $validatedData['treatment_id']);
                if (empty($treatment)) {
                    return back()->withInput()->with('error', 'Selected treatment does not exist');
                }
            } catch (\Exception $e) {
                return back()->withInput()->with('error', 'Error validating treatment: ' . $e->getMessage());
            }

            // Map form status values to database status values
            $statusMapping = [
                'Scheduled' => 'arranged',
                'Completed' => 'completed',
                'Cancelled' => 'cancelled',
                'Rescheduled' => 'arranged' // Rescheduled appointments are typically 'arranged' in the DB
            ];

            // Convert datetime-local format to proper ISO format
            $startTime = date('Y-m-d H:i:s', strtotime($validatedData['start_time']));
            $endTime = date('Y-m-d H:i:s', strtotime($validatedData['end_time']));

            // Prepare update data from form inputs
            $updateData = [
                'patient_id' => $validatedData['patient_id'],
                'dentist_id' => $validatedData['dentist_id'],
                'treatment_id' => $validatedData['treatment_id'],
                'start_time' => $startTime,
                'end_time' => $endTime,
                'status' => $statusMapping[$validatedData['status']], // Map to correct DB status
                'notes' => $validatedData['notes'] ?? null,
                'cancellation_reason' => $validatedData['cancellation_reason'] ?? null,
                'reschedule_reason' => $validatedData['reschedule_reason'] ?? null,
            ];

            \Log::info('Appointment update data prepared', [
                'appointment_id' => $id,
                'update_data' => $updateData
            ]);

            // Update the appointment using SupabaseService
            $result = $this->supabase->updateAppointment($id, $updateData);

            \Log::info('Appointment updated successfully', [
                'appointment_id' => $id,
                'result' => $result
            ]);

            return redirect()->route('admin.appointments.index')
                ->with('success', 'Appointment updated successfully');

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed for appointment update', [
                'appointment_id' => $id,
                'errors' => $e->errors()
            ]);
            return back()->withErrors($e->validator)->withInput()
                ->with('error', 'Validation failed. Please check your input.');
        } catch (\Exception $e) {
            \Log::error('Error updating appointment', [
                'appointment_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withInput()
                ->with('error', 'Error updating appointment: ' . $e->getMessage());
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