<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Htrans;
use App\Models\Membership;
use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    //
    public function laporanStok(Request $request) {
        $categories = Category::all();
        $query = Products::with('category');
    
        // Apply filters conditionally
        if ($request->filled('name')) {
            $query->where('namaBarang', 'LIKE', '%' . $request->name . '%');
        }

        if ($request->filled('price_min_small') && $request->filled('price_max_small')) {
            $query->whereBetween('hargaKecil', [$request->price_min_small, $request->price_max_small]);
        } elseif ($request->filled('price_min_small')) {
            $query->whereDate('hargaKecil', '>=', $request->price_min_small);
        } elseif ($request->filled('price_max_small')) {
            $query->whereDate('hargaKecil', '<=', $request->price_max_small);
        }


        if ($request->filled('price_min_big') && $request->filled('price_min_big')) {
            $query->whereBetween('hargaBesar', [$request->price_min_big, $request->price_max_big]);
        } elseif ($request->filled('price_min_big')) {
            $query->whereDate('hargaBesar', '>=', $request->price_min_big);
        } elseif ($request->filled('price_min_big')) {
            $query->whereDate('hargaBesar', '<=', $request->price_max_big);
        }

        
        if ($request->filled('stok_min') && $request->filled('stok_max')) {
            $query->whereBetween('totalQuantity', [$request->stok_min, $request->stok_max]);
        } elseif ($request->filled('stok_min')) {
            $query->whereDate('totalQuantity', '>=', $request->stok_min);
        } elseif ($request->filled('stok_max')) {
            $query->whereDate('totalQuantity', '<=', $request->stok_max);
        }

        if ($request->filled('stok_min_big')) {
            $query->whereRaw('totalQuantity / isiSatuanBesar >= ?', [$request->stok_min_big]);
        }
        if ($request->filled('stok_max_big')) {
            $query->whereRaw('totalQuantity / isiSatuanBesar <= ?', [$request->stok_max_big]);
        }
    
        if ($request->filled('category')) {
            $query->where('fk_kategori', $request->category);
        }
    
        $products = $query->get();
        return view('admin.laporan.laporanStok', compact('products', 'categories'));
    }

    public function laporanStatusPesanan(Request $request){
        $query = Htrans::with('user'); 

        if ($request->filled('kodeTrans')) {
            $query->where('kodeTrans', 'LIKE', '%' . $request->kodeTrans . '%');
        }

        if ($request->filled('namaPembeli')) {
            $query->where('namaPembeli', 'LIKE', '%' . $request->namaPembeli . '%');
        }
    
        if ($request->filled('alamatPembelian')) {
            $query->where('alamatPembelian', 'LIKE', '%' . $request->alamatPembelian . '%');
        }
    
        if ($request->filled('salesHeaderDate_start') && $request->filled('salesHeaderDate_end')) {
            $query->whereBetween(DB::raw('DATE(tanggalPembelian)'), [
                $request->salesHeaderDate_start, 
                $request->salesHeaderDate_end
            ]);
        } elseif ($request->filled('salesHeaderDate_start')) {
            $query->whereDate('tanggalPembelian', '>=', $request->salesHeaderDate_start);
        } elseif ($request->filled('salesHeaderDate_end')) {
            $query->whereDate('tanggalPembelian', '<=', $request->salesHeaderDate_end);
        }
    
        if ($request->filled('total_min') && $request->filled('total_max')) {
            $query->whereBetween('totalPembelian', [$request->total_min, $request->total_max]);
        } elseif ($request->filled('total_min')) {
            $query->where('totalPembelian', '>=', $request->total_min);
        } elseif ($request->filled('total_max')) {
            $query->where('totalPembelian', '<=', $request->total_max);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
    
        $orders = $query->get();
        return view('admin.laporan.laporanStatusTransaksi', compact('orders'));
    }

    public function laporanMembership(Request $request){
        $query = Membership::with(['user', 'points']);

        if ($request->filled('statusMembership')) {
            $query->where('statusMembership', $request->statusMembership);
        }

        if ($request->filled('tanggalMulai_min') && $request->filled('tanggalMulai_max')) {
            $query->whereBetween(DB::raw('DATE(tanggalDaftar)'), [
                $request->tanggalMulai_min, 
                $request->tanggalMulai_max
            ]);
        } elseif ($request->filled('tanggalMulai_min')) {
            $query->where('tanggalDaftar', '>=', $request->tanggalMulai_min);
        } elseif ($request->filled('tanggalMulai_max')) {
            $query->where('tanggalDaftar', '<=', $request->tanggalMulai_max);
        }

        if ($request->filled('saldo_min') && $request->filled('saldo_max')) {
            $query->whereBetween('saldoPoin', [$request->saldo_min, $request->saldo_max]);
        } elseif ($request->filled('saldo_min')) {
            $query->where('saldoPoin', '>=', $request->saldo_min);
        } elseif ($request->filled('saldo_max')) {
            $query->where('saldoPoin', '<=', $request->saldo_max);
        }
        
        $memberships = $query->get();

        return view('admin.laporan.laporanMembership', compact('memberships'));
    }

    public function laporanPendapatan(Request $request){
            // Retrieve date range from request
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');

        // If no date range is specified, show all transactions
        $query = Htrans::where('status', 3); // Assuming '1' indicates a completed transaction

        if ($startDate && $endDate) {
            $query->whereBetween(DB::raw('DATE(tanggalPembelian)'), [
                $startDate, 
                $endDate
            ]);
        }   

        // Fetch transactions based on query
        $transactions = $query->get();

        // Calculate Gross Revenue, Discounts, and Net Revenue
        $netRevenue = $transactions->sum('totalPembelian');
        $totalDiscount = $transactions->sum('discount');
        $grossRevenue = $netRevenue + $totalDiscount;

        // Pass data to the view
        return view('admin.laporan.laporanPendapatan', [
            'transactions' => $transactions,
            'grossRevenue' => $grossRevenue,
            'totalDiscount' => $totalDiscount,
            'netRevenue' => $netRevenue,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }
}
