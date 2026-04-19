<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\StockIn;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StockInController extends Controller
{
    protected StockService $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    public function index()
    {
        return view('stock.in.index');
    }

    public function show(StockIn $stockIn)
    {
        $stockIn->load(['barang', 'batch', 'user']);

        return view('stock.in.show', compact('stockIn'));
    }
}
