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
            
            // Try to fetch receptionists, but handle if table doesn't exist
            $receptionists = [];
            try {
                $receptionists = $this->supabase->fetchTable('receptionists');
            } catch (\Exception $e) {
                // Log the error but don't fail the dashboard
                \Log::info('Receptionists table not accessible: ' . $e->getMessage());
            }
            
            $appointments = $this->supabase->fetchTable('appointments');
            $treatments = $this->supabase->fetchTable('treatments');
            $invoices = $this->supabase->fetchTable('invoices');
            $payments = $this->supabase->fetchTable('payments');
            // Calculate payment amount sum for current month
            $totalPaymentAmount = 0;
            $currentMonth = date('Y-m');
            $currentMonthPaymentCount = 0;
            
            if ($payments) {
                foreach ($payments as $payment) {
                    $paymentDate = $payment['date_of_payment'] ?? '';
                    if ($paymentDate && strpos($paymentDate, $currentMonth) === 0) {
                        $totalPaymentAmount += (float)($payment['amount_paid'] ?? 0);
                        $currentMonthPaymentCount++;
                    }
                }
            }

            // Calculate statistics
            $stats = [
                'total_patients' => count($patients ?? []),
                'total_dentists' => count($dentists ?? []),
                'total_receptionists' => count($receptionists ?? []),
                'total_appointments' => count($appointments ?? []),
                'total_treatments' => count($treatments ?? []),
                'total_invoices' => count($invoices ?? []),
                'total_payments' => count($payments ?? []),
                'total_payment_amount' => $totalPaymentAmount,
                'current_month_payments' => $currentMonthPaymentCount,
                'current_month' => date('F Y'), // e.g., "December 2024"
            ];

            // Get recent appointments (last 5)
            $recentAppointments = array_slice($appointments ?? [], -5);



            // Get pending appointments count
            $pendingAppointments = 0;
            if ($appointments) {
                foreach ($appointments as $appointment) {
                    if (($appointment['status'] ?? '') === 'Scheduled') {
                        $pendingAppointments++;
                    }
                }
            }

            return view('admin.dashboard', compact('stats', 'recentAppointments', 'pendingAppointments'));
        } catch (\Exception $e) {
            // If there's an error, return with default values
            $stats = [
                'total_patients' => 0,
                'total_dentists' => 0,
                'total_receptionists' => 0,
                'total_appointments' => 0,
                'total_treatments' => 0,
                'total_invoices' => 0,
                'total_payments' => 0,
                'total_payment_amount' => 0,
                'current_month_payments' => 0,
                'current_month' => date('F Y'),
            ];
            
            return view('admin.dashboard', [
                'stats' => $stats,
                'recentAppointments' => [],
                'pendingAppointments' => 0,
                'error' => 'Error loading dashboard data: ' . $e->getMessage()
            ]);
        }
    }
}
