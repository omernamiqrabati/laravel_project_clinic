<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SupabaseService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $supabase;

    public function __construct(SupabaseService $supabase)
    {
        $this->supabase = $supabase;
    }

    public function index()
    {
        try {
            // Fetch data for dashboard statistics
            $patients = $this->supabase->fetchTable('patients');
            $dentists = $this->supabase->fetchTable('dentists');
            $appointments = $this->supabase->fetchTable('appointments');
            $treatments = $this->supabase->fetchTable('treatments');
            $invoices = $this->supabase->fetchTable('invoices');
            $payments = $this->supabase->fetchTable('payments');
            // Calculate statistics
            $stats = [
                'total_patients' => count($patients ?? []),
                'total_dentists' => count($dentists ?? []),
                'total_appointments' => count($appointments ?? []),
                'total_treatments' => count($treatments ?? []),
                'total_invoices' => count($invoices ?? []),
                'total_payments' => count($payments ?? []),
            ];

            // Get recent appointments (last 5)
            $recentAppointments = array_slice($appointments ?? [], -5);

            // Calculate revenue from payments
            $totalRevenue = 0;
            if ($payments) {
                foreach ($payments as $payment) {
                    $totalRevenue += floatval($payment['amount'] ?? 0);
                }
            }

            // Get pending appointments count
            $pendingAppointments = 0;
            if ($appointments) {
                foreach ($appointments as $appointment) {
                    if (($appointment['status'] ?? '') === 'Scheduled') {
                        $pendingAppointments++;
                    }
                }
            }

            return view('admin.dashboard', compact('stats', 'recentAppointments', 'totalRevenue', 'pendingAppointments'));
        } catch (\Exception $e) {
            // If there's an error, return with default values
            $stats = [
                'total_patients' => 0,
                'total_dentists' => 0,
                'total_appointments' => 0,
                'total_treatments' => 0,
                'total_invoices' => 0,
                'total_payments' => 0,
            ];
            
            return view('admin.dashboard', [
                'stats' => $stats,
                'recentAppointments' => [],
                'totalRevenue' => 0,
                'pendingAppointments' => 0,
                'error' => 'Error loading dashboard data: ' . $e->getMessage()
            ]);
        }
    }
}
