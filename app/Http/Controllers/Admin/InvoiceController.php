<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SupabaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    public function index()
    {
        try {
            $invoices = $this->supabase->fetchTable('invoices');
            
            // Fetch patient names for display
            $patients_name = $this->supabase->fetchByQuery('user_profiles', [
                'role' => 'patient'
            ], ['first_name', 'last_name','id']);
            
            return view('admin.invoices.index', [
                'invoices' => $invoices,
                'patients_name' => $patients_name
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Error fetching invoices: ' . $e->getMessage());
        }
    }

    public function create()
    {
        try {
            // Fetch patients with their names from user_profiles table
            // This query joins patients with user_profiles to get complete patient information
            $patients = $this->getPatients();
            
            // Fetch appointments (optional)
            $appointments = $this->getAppointments();
            
            return view('admin.invoices.create', compact('patients', 'appointments'));
            
        } catch (\Exception $e) {
            Log::error('Error fetching data for invoice creation: ' . $e->getMessage());
            
            return view('admin.invoices.create', [
                'patients' => [],
                'appointments' => [],
                'error' => 'Unable to load patient data. Please try again.'
            ]);
        }
    }

    public function store(Request $request)
    {
        // Validate the request
        $validated = $request->validate([
            'patient_id' => 'nullable|uuid',
            'appointment_id' => 'nullable|uuid',
            'total_amount' => 'required|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'payment_status' => 'required|in:paid,paying,unpaid',
            'notes' => 'nullable|string|max:1000',
        ]);

        Log::info('Invoice validation passed', [
            'validated_data' => $validated
        ]);

        try {
            // Set default tax to 0 if not provided
            $validated['tax'] = $validated['tax'] ?? 0;

            // Create the invoice in Supabase
            $invoiceId = $this->createInvoice($validated);
            
            return redirect()
                ->route('admin.invoices.show', $invoiceId)
                ->with('success', 'Invoice created successfully!');
                
        } catch (\Exception $e) {
            Log::error('Error creating invoice: ' . $e->getMessage());
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to create invoice. Please try again.']);
        }
    }

    /**
     * Fetch patients with their names from user_profiles table
     */
    private function getPatients()
    {
        try {
            // Use the existing SupabaseService to fetch patients
            $patients = $this->supabase->fetchTable('patients');
            $userProfiles = $this->supabase->fetchTable('user_profiles');
            
            // Create a map of user profiles by ID for quick lookup
            $profileMap = [];
            foreach ($userProfiles as $profile) {
                $profileMap[$profile['id']] = $profile;
            }
            
            // Combine patient data with user profile data
            $combinedPatients = [];
            foreach ($patients as $patient) {
                $profile = $profileMap[$patient['patient_id']] ?? [];
                
                // Create patient name
                $firstName = $profile['first_name'] ?? '';
                $lastName = $profile['last_name'] ?? '';
                $patientName = trim($firstName . ' ' . $lastName);
                
                if (empty($patientName)) {
                    $patientName = 'Patient #' . substr($patient['patient_id'], 0, 8);
                }
                
                $combinedPatients[] = (object) [
                    'patient_id' => $patient['patient_id'],
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $profile['email'] ?? '',
                    'phone' => $profile['phone'] ?? '',
                    'patient_name' => $patientName,
                    'date_of_birth' => $patient['date_of_birth'] ?? null,
                    'gender' => $patient['gender'] ?? '',
                    'address' => $patient['address'] ?? '',
                    'created_at' => $profile['created_at'] ?? null,
                ];
            }
            
            // Sort by patient name
            usort($combinedPatients, function($a, $b) {
                return strcmp($a->patient_name, $b->patient_name);
            });
            
            return $combinedPatients;
            
        } catch (\Exception $e) {
            Log::error('Error fetching patients: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Fetch appointments for the dropdown (optional)
     */
    private function getAppointments()
    {
        try {
            // Fetch appointments, treatments, and user profiles separately
            $appointments = $this->supabase->fetchTable('appointments');
            $treatments = $this->supabase->fetchTable('treatments');
            $userProfiles = $this->supabase->fetchTable('user_profiles');
            
            // Create maps for quick lookup
            $treatmentMap = [];
            foreach ($treatments as $treatment) {
                $treatmentMap[$treatment['treatment_id']] = $treatment;
            }
            
            $profileMap = [];
            foreach ($userProfiles as $profile) {
                $profileMap[$profile['id']] = $profile;
            }
            
            // Filter and combine appointments data
            $combinedAppointments = [];
            foreach ($appointments as $appointment) {
                // Only include arranged and in_appointment status
                if (!in_array($appointment['status'], ['arranged', 'in_appointment'])) {
                    continue;
                }
                
                $treatment = $treatmentMap[$appointment['treatment_id']] ?? [];
                $profile = $profileMap[$appointment['patient_id']] ?? [];
                
                $patientName = trim(($profile['first_name'] ?? '') . ' ' . ($profile['last_name'] ?? ''));
                if (empty($patientName)) {
                    $patientName = 'Unknown Patient';
                }
                
                $combinedAppointments[] = (object) [
                    'appointment_id' => $appointment['appointment_id'],
                    'start_time' => $appointment['start_time'],
                    'end_time' => $appointment['end_time'],
                    'status' => $appointment['status'],
                    'treatment_name' => $treatment['name'] ?? null,
                    'patient_name' => $patientName,
                ];
            }
            
            // Sort by start_time descending (most recent first)
            usort($combinedAppointments, function($a, $b) {
                return strtotime($b->start_time) - strtotime($a->start_time);
            });
            
            // Limit to 50 results
            return array_slice($combinedAppointments, 0, 50);
            
        } catch (\Exception $e) {
            Log::error('Error fetching appointments: ' . $e->getMessage());
            return [];
        }
    }

    /**
     * Create invoice in Supabase
     */
    private function createInvoice($data)
    {
        try {
            // Prepare the invoice data
            $invoiceData = [
                'patient_id' => $data['patient_id'] ?: null,
                'appointment_id' => $data['appointment_id'] ?: null,
                'total_amount' => $data['total_amount'],
                'tax' => $data['tax'],
                'payment_status' => $data['payment_status'],
                'notes' => $data['notes'] ?: null,
            ];
            
            // Remove null values to avoid sending empty fields
            $invoiceData = array_filter($invoiceData, function($value) {
                return $value !== null && $value !== '';
            });
            
            // Insert the invoice using SupabaseService
            $result = $this->supabase->insert('invoices', $invoiceData);
            
            Log::info('Invoice created successfully', [
                'result' => $result,
                'invoice_data' => $invoiceData
            ]);
            
            // Return the invoice_id (the service returns the created record)
            return $result['invoice_id'] ?? null;
            
        } catch (\Exception $e) {
            Log::error('Error creating invoice: ' . $e->getMessage());
            throw new \Exception('Failed to create invoice: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified invoice.
     */
    public function show(string $id)
    {
        try {
            // Fetch all invoices and find the one we need (workaround for fetchById issues)
            $allInvoices = $this->supabase->fetchTable('invoices');
            $invoice = null;
            
            foreach ($allInvoices as $inv) {
                if ($inv['invoice_id'] === $id) {
                    $invoice = $inv;
                    break;
                }
            }
            
            if (!$invoice) {
                return redirect()->route('admin.invoices.index')
                    ->with('error', 'Invoice not found.');
            }
            
            // Fetch related patient information if patient_id exists
            $patient = null;
            if (!empty($invoice['patient_id'])) {
                $patients = $this->getPatients();
                foreach ($patients as $p) {
                    if ($p->patient_id === $invoice['patient_id']) {
                        $patient = $p;
                        break;
                    }
                }
            }
            
            // Fetch related appointment information if appointment_id exists
            $appointment = null;
            if (!empty($invoice['appointment_id'])) {
                $allAppointments = $this->supabase->fetchTable('appointments');
                foreach ($allAppointments as $apt) {
                    if ($apt['appointment_id'] === $invoice['appointment_id']) {
                        $appointment = $apt;
                        break;
                    }
                }
            }
            
            return view('admin.invoices.show', compact('invoice', 'patient', 'appointment'));
            
        } catch (\Exception $e) {
            Log::error('Error fetching invoice: ' . $e->getMessage());
            return redirect()->route('admin.invoices.index')
                ->with('error', 'Error fetching invoice: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            // First, let's try to fetch all invoices and find the one we need
            // This is a workaround if fetchById is not working properly
            $allInvoices = $this->supabase->fetchTable('invoices');
            $invoice = null;
            
            foreach ($allInvoices as $inv) {
                if ($inv['invoice_id'] === $id) {
                    $invoice = $inv;
                    break;
                }
            }
            
            if (!$invoice) {
                return redirect()->route('admin.invoices.index')
                    ->with('error', 'Invoice not found.');
            }
            
            // Get patients and appointments for the dropdowns
            $patients = $this->getPatients();
            $appointments = $this->getAppointments();
            
            return view('admin.invoices.edit', compact('invoice', 'patients', 'appointments'));
        } catch (\Exception $e) {
            Log::error('Error fetching invoice for edit: ' . $e->getMessage());
            return redirect()->route('admin.invoices.index')
                ->with('error', 'Error fetching invoice: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        Log::info('Invoice update attempt', [
            'invoice_id' => $id,
            'request_data' => $request->all()
        ]);
        
        // Validate the request
        $validated = $request->validate([
            'patient_id' => 'nullable|uuid',
            'appointment_id' => 'nullable|uuid',
            'total_amount' => 'required|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'payment_status' => 'required|in:paid,paying,unpaid',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            // First, verify the invoice exists
            $allInvoices = $this->supabase->fetchTable('invoices');
            $existingInvoice = null;
            
            foreach ($allInvoices as $inv) {
                if ($inv['invoice_id'] === $id) {
                    $existingInvoice = $inv;
                    break;
                }
            }
            
            if (!$existingInvoice) {
                return redirect()->route('admin.invoices.index')
                    ->with('error', 'Invoice not found.');
            }

            // Set default tax to 0 if not provided
            $validated['tax'] = $validated['tax'] ?? 0;

            // Prepare the update data
            $updateData = [
                'patient_id' => $validated['patient_id'] ?: null,
                'appointment_id' => $validated['appointment_id'] ?: null,
                'total_amount' => $validated['total_amount'],
                'tax' => $validated['tax'],
                'payment_status' => $validated['payment_status'],
                'notes' => $validated['notes'] ?: null,
            ];

            // Remove null values to avoid sending empty fields
            $updateData = array_filter($updateData, function($value) {
                return $value !== null && $value !== '';
            });

            // Update the invoice using SupabaseService
            $result = $this->supabase->updateById('invoices', $id, $updateData);
            
            Log::info('Invoice updated successfully', [
                'invoice_id' => $id,
                'update_data' => $updateData,
                'result' => $result
            ]);
            
            return redirect()
                ->route('admin.invoices.show', $id)
                ->with('success', 'Invoice updated successfully!');
                
        } catch (\Exception $e) {
            Log::error('Error updating invoice: ' . $e->getMessage(), [
                'invoice_id' => $id,
                'request_data' => $validated ?? [],
                'exception_trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'Failed to update invoice: ' . $e->getMessage()]);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            $this->supabase->deleteById('invoices', $id);

            return redirect()->route('admin.invoices.index')->with('success', 'Invoice deleted successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting invoice: ' . $e->getMessage());
        }
    }
}