<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    public function index()
    {
        return view('admin.keuangan.index');
    }
    
    public function billing()
    {
        return view('admin.keuangan.billing');
    }
}
