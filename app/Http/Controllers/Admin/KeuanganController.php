<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    /**
     * Display the billing/invoice list
     */
    public function index()
    {
        // Get all payments with related data
        $payments = Payment::with(['subscription.toko', 'subscription.plan'])
            ->latest()
            ->get();

        // Calculate summary statistics
        $totalPendapatan = Payment::where('status', 'success')->sum('amount');
        
        $menungguPembayaran = Payment::where('status', 'pending')
            ->where('created_at', '>=', now()->subDays(3))
            ->count();
        
        $jatuhTempo = Payment::where('status', 'pending')
            ->where('created_at', '<', now()->subDays(3))
            ->count();

        return view('admin.keuangan.index', compact(
            'payments',
            'totalPendapatan',
            'menungguPembayaran',
            'jatuhTempo'
        ));
    }

    /**
     * Display the billing overview
     */
    public function billing()
    {
        return view('admin.keuangan.billing');
    }

    /**
     * Show invoice detail
     */
    public function show(Payment $payment)
    {
        $payment->load(['subscription.toko', 'subscription.plan']);
        
        return view('admin.keuangan.show', compact('payment'));
    }
}
