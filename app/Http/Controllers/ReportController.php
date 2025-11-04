<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Dtrans;
use App\Models\Hretur;
use App\Models\Htrans;
use App\Models\Membership;
use App\Models\Products;
use App\Models\User;
use App\Models\retur;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    //
    public function laporanStok(Request $request) {
        $categories = Category::all();
        $query = Products::with('category');
        $request->validate([
            'price_min_small' => 'nullable|numeric|gte:0',
            'price_max_small' => 'nullable|numeric|gte:0',
            'price_min_big' => 'nullable|numeric|gte:0',
            'price_max_big' => 'nullable|numeric|gte:0',
            'stok_min' => 'nullable|gte:0',
            'stok_max' => 'nullable|gte:0',
            'stok_min_big' => 'nullable|gte:0',
            'stok_max_big' => 'nullable|gte:0',
        ], [
            'price_min_small.gte' => 'Harga minimum tidak bisa kurang dari 0.',
            'price_max_small.gte' => 'Harga maksimum tidak bisa kurang dari 0.',
            'price_min_big.gte' => 'Harga minimum tidak bisa kurang dari 0.',
            'price_max_big.gte' => 'Harga maksimum tidak bisa kurang dari 0.',
            'stok_min.gte' => 'stok minimum tidak bisa kurang dari 0.',
            'stok_max.gte' => 'stok maksimum tidak bisa kurang dari 0.',
            'stok_min_big.gte' => 'stok minimum tidak bisa kurang dari 0.',
            'stok_max_big.gte' => 'stok maksimum tidak bisa kurang dari 0.',
        ]);
        if ($request->filled('stok_min') && $request->filled('stok_max') && $request->stok_min > $request->stok_max) {
            return back()->withErrors(['stok_min' => 'stok Minimum  tidak bisa lebih besar dari stok maksimum.']);
        }
    
        if ($request->filled('stok_min_big') && $request->filled('stok_max_big') && $request->stok_min_big > $request->stok_max_big) {
            return back()->withErrors(['stok_min_big' => 'stok Minimum tidak bisa lebih besar dari stok maksimum.']);
        }
        if ($request->filled('price_min_small') && $request->filled('price_max_small') && $request->price_min_small > $request->price_max_small) {
            return back()->withErrors(['price_min_small' => 'Harga minimum tidak bisa lebih besar dari harga maksimum.']);
        }
        
        if ($request->filled('price_min_big') && $request->filled('price_max_big') && $request->price_min_big > $request->price_max_big) {
            return back()->withErrors(['price_min_big' => 'Harga minimum tidak bisa lebih besar dari harga maksimum.']);
        }
        if ($request->filled('name')) {
            $query->where('namaBarang', 'LIKE', '%' . $request->name . '%');
        }

        if ($request->filled('price_min_small') && $request->filled('price_max_small')) {
            $query->whereBetween('hargaKecil', [$request->price_min_small, $request->price_max_small]);
        } elseif ($request->filled('price_min_small')) {
            $query->where('hargaKecil', '>=', $request->price_min_small);
        } elseif ($request->filled('price_max_small')) {
            $query->where('hargaKecil', '<=', $request->price_max_small);
        }


        if ($request->filled('price_min_big') && $request->filled('price_min_big')) {
            $query->whereBetween('hargaBesar', [$request->price_min_big, $request->price_max_big]);
        } elseif ($request->filled('price_min_big')) {
            $query->where('hargaBesar', '>=', $request->price_min_big);
        } elseif ($request->filled('price_min_big')) {
            $query->where('hargaBesar', '<=', $request->price_max_big);
        }

        if ($request->filled('stok_min') && $request->filled('stok_max')) {
            $query->whereBetween('totalQuantity', [$request->stok_min, $request->stok_max]);
        } elseif ($request->filled('stok_min')) {
            $query->where('totalQuantity', '>=', $request->stok_min);
        } elseif ($request->filled('stok_max')) {
            $query->where('totalQuantity', '<=', $request->stok_max);
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

        // Group by category and calculate total stock
        $stockPerCategory = $products->groupBy(function ($product) {
            return $product->category->nama_category; // Group by category name
        })->map(function ($categoryGroup) {
            return $categoryGroup->groupBy('satuanTerkecil')->map(function ($subGroup) {
                return $subGroup->sum('totalQuantity'); // Sum quantities for each unit size
            });
        });
        return view('admin.laporan.laporanStok', compact('products', 'categories','stockPerCategory'));
    }

    public function laporanStatusPesanan(Request $request){
        $query = Htrans::with('user'); 
        // Validation for Total Harga
        if ($request->filled('total_min') && $request->filled('total_max') && $request->total_min > $request->total_max) {
            return back()->withErrors(['total_min' => 'Total minimum tidak bisa lebih besar dari total maksimum.']);
        }

        // Validation for Tanggal Pemesanan
        if ($request->filled('salesHeaderDate_start') && $request->filled('salesHeaderDate_end') && $request->salesHeaderDate_start > $request->salesHeaderDate_end) {
            return back()->withErrors(['salesHeaderDate_start' => 'Tanggal mulai tidak bisa lebih besar dari tanggal akhir.']);
        }
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
        $this->validate($request, [
            'startDate' => 'date',
            'endDate' => 'date|after_or_equal:start_date'
        ], [
            'startDate.date' => 'The start date must be a valid date.',
            'endDate.date' => 'The end date must be a valid date.',
            'endDate.after_or_equal' => 'Tanggal mulai tidak bisa lebih besar dari tanggal akhir.'
        ]);
        
        $startDate = $request->input('startDate');
        $endDate = $request->input('endDate');
    
        $query = Htrans::where('status', 3);
    
        if ($startDate && $endDate) {
            $query->whereBetween(DB::raw('DATE(tanggalPembelian)'), [
                $startDate, 
                $endDate
            ]);
        }   
    
        $transactions = $query->get();
    
        $revenuePerMonth = $transactions->groupBy(function($date) {
            return Carbon::parse($date->tanggalPembelian)->format('Y-m'); // Format year-month
        });
    
        $monthlyRevenue = $revenuePerMonth->map(function($month) {
            $netRevenue = $month->sum('totalPembelian');
            $totalDiscount = $month->sum('discount');
            return $netRevenue + $totalDiscount; // Gross Revenue for the month
        });
    
        // Calculate total saldo (kredit and debit)
        $saldoKredit = $transactions->where('totalPembelian', '>', 0)->sum('totalPembelian');
        $saldoDebit = $transactions->where('discount', '>', 0)->sum('discount');
    
        $grossRevenue = $transactions->sum('totalPembelian');
        $totalDiscount = $transactions->sum('discount');
        $netRevenue = $grossRevenue - $totalDiscount;
        $startDateFormatted = $startDate ? Carbon::parse($startDate)->format('d/m/Y') : null;
        $endDateFormatted = $endDate ? Carbon::parse($endDate)->format('d/m/Y') : null;
    
        return view('admin.laporan.laporanPendapatan', [
            'transactions' => $transactions,
            'monthlyRevenue' => $monthlyRevenue,
            'grossRevenue' => $grossRevenue,
            'totalDiscount' => $totalDiscount,
            'saldoKredit' => $saldoKredit,
            'saldoDebit' => $saldoDebit,
            'startDate' => $startDateFormatted,
            'endDate' => $endDateFormatted
        ]);
    }
    public function laporanPenjualan(Request $request){
        $this->validate($request, [
            'start_date' => 'date',
            'end_date' => 'date|after_or_equal:start_date'
        ], [
            'start_date.date' => 'The start date must be a valid date.',
            'end_date.date' => 'The end date must be a valid date.',
            'end_date.after_or_equal' => 'Tanggal mulai tidak bisa lebih besar dari tanggal akhir.'
        ]);
        $query = Dtrans::with(['product:id,namaBarang'])
            ->whereHas('htrans', function ($query) use ($request) {
                $query->where('status', 3);

                if ($request->filled('start_date') && $request->filled('end_date')) {
                    $query->whereBetween(DB::raw('DATE(tanggalPembelian)'), [
                        $request->start_date, 
                        $request->end_date
                    ]);
                } elseif ($request->filled('start_date')) {
                    $query->where('tanggalPembelian', '>=', $request->start_date);
                } elseif ($request->filled('end_date')) {
                    $query->where('tanggalPembelian', '<=', $request->end_date);
                }
            });
    
        $salesPerProduct = $query
            ->select(
                'fkProductID',
                'satuanBarang as unit',
                DB::raw('SUM(totalJumlah) as total_quantity_sold'),
                DB::raw('SUM(totalJumlah * hargaSatuan) as total_income')
            )
            ->groupBy('fkProductID', 'unit')
            ->get()
            ->groupBy('product.namaBarang');
    
        $chartData = $query
        ->select(
            'fkProductID',
            'satuanBarang as unit',
            DB::raw('SUM(totalJumlah) as total_quantity_sold'),
            DB::raw('SUM(totalJumlah * hargaSatuan) as total_income')
        )
        ->groupBy('fkProductID', 'unit')
        ->with('product:id,namaBarang')
        ->orderByDesc('total_quantity_sold') // Urutkan dari yang paling banyak terjual
        ->limit(5) // Ambil hanya 5 produk teratas
        ->get();
        $startDateFormatted = $request->filled('start_date') 
            ? Carbon::parse($request->start_date)->format('d/m/Y') 
            : null;
    
        $endDateFormatted = $request->filled('end_date') 
            ? Carbon::parse($request->end_date)->format('d/m/Y') 
            : null;
    
        return view('admin.laporan.laporanPenjualan', compact('salesPerProduct', 'chartData','startDateFormatted', 'endDateFormatted'));
    }
    public function laporanAktif(Request $request){
        $request->validate([
            'minAmount' => 'nullable|numeric|gte:0',
            'maxAmount' => 'nullable|numeric|gte:0',
            'minTransactions' => 'nullable|numeric|gte:0',
            'maxTransactions' => 'nullable|numeric|gte:0',
        ], [
            'minAmount.gte' => 'Jumlah minimum tidak bisa kurang dari 0.',
            'maxAmount.gte' => 'Jumlah maksimum tidak bisa kurang dari 0.',
            'minTransactions.gte' => 'Total minimum tidak bisa kurang dari 0.',
            'maxTransactions.gte' => 'Total maksimum tidak bisa kurang dari 0.',
        ]);
        if ($request->filled('minTransactions') && $request->filled('maxTransactions') && $request->minTransactions > $request->maxTransactions) {
            return back()->withErrors(['minTransactions' => 'Jumlah transaksi minimum tidak bisa lebih besar dari jumlah transaksi maksimum.']);
        }
        
        if ($request->filled('minAmount') && $request->filled('maxAmount') && $request->minAmount > $request->maxAmount) {
            return back()->withErrors(['minAmount' => 'Total harga transaksi minimum tidak bisa lebih besar dari total harga transaksi maksimum.']);
        }
        $query = User::where('role', '!=', 1)->with(['htrans.dtrans.product']);

        if ($request->filled('minTransactions') || $request->filled('maxTransactions')) {
            $query->whereHas('htrans', function ($q) use ($request) {
                $q->select(DB::raw('COUNT(*) as completed_transactions'))
                ->where('status', 3);
                if ($request->filled('minTransactions')) {
                    $q->havingRaw('completed_transactions >= ?', [$request->minTransactions]);
                }
                if ($request->filled('maxTransactions')) {
                    $q->havingRaw('completed_transactions <= ?', [$request->maxTransactions]);
                }
            });
        }

        if ($request->filled('minAmount') || $request->filled('maxAmount')) {
            $query->whereHas('htrans', function ($q) use ($request) {
                $q->select(DB::raw('SUM(totalPembelian) as total_amount'))
                ->where('status', 3);
                if ($request->filled('minAmount')) {
                    $q->havingRaw('total_amount >= ?', [$request->minAmount]);
                }
                if ($request->filled('maxAmount')) {
                    $q->havingRaw('total_amount <= ?', [$request->maxAmount]);
                }
            });
        }

        $customers = $query->get()->map(function ($user) {
            $completedTransactions = $user->htrans->where('status', 3);
            $user->total_completed_transactions = $completedTransactions->count();
            $user->total_transaction_amount = $completedTransactions->sum('totalPembelian');
            $user->newest_transaction_date = $completedTransactions->max('tanggalPembelian');
            $user->full_name = $user->firstName . ' ' . $user->lastName; // Combine first and last name
            return $user;
        });
                // Fetch data grouped by month
                $topCustomersByMonth = Htrans::selectRaw('
                MONTH(tanggalPembelian) as month, 
                YEAR(tanggalPembelian) as year, 
                fkUserID, 
                COUNT(*) as transaction_count
            ')
            ->where('status', 3) // Only completed transactions
            ->groupBy('month', 'year', 'fkUserID')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->orderBy('transaction_count', 'desc')
            ->get()
            ->groupBy(fn($item) => $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT)) // Group by YYYY-MM
            ->map(fn($group) => $group->first()); // Get top customer per month

            // Prepare chart data
            $chartData = [
            'labels' => $topCustomersByMonth->keys()->toArray(), // Month labels (YYYY-MM)
            'data' => $topCustomersByMonth->map(fn($item) => $item->transaction_count)->toArray(), // Transaction counts
            'customers' => $topCustomersByMonth->map(fn($item) => User::find($item->fkUserID)?->firstName . ' ' . User::find($item->fkUserID)?->lastName)->toArray(), // Customer names
            ];

        return view('admin.laporan.laporanAktif', compact('customers','chartData'));
    }
    

    public function laporanRetur(Request $request){
        $query = Hretur::with(['dretur', 'htrans', 'user']);
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween(DB::raw('DATE(TanggalRetur)'), [
                $request->start_date, 
                $request->end_date
            ]);
        } elseif ($request->filled('start_date')) {
            $query->whereDate('TanggalRetur', '>=', $request->start_date);
        } elseif ($request->filled('end_date')) {
            $query->whereDate('TanggalRetur', '<=', $request->end_date);
        }
    
        if ($request->has('status')) {
            $query->where('Status', $request->status);
        }
    
        $returs = $query->get();
    
        $startDateFormatted = $request->filled('start_date') 
            ? Carbon::parse($request->start_date)->format('d/m/Y') 
            : null;
    
        $endDateFormatted = $request->filled('end_date') 
            ? Carbon::parse($request->end_date)->format('d/m/Y') 
            : null;
    
        return view('admin.laporan.laporanRetur', compact('returs','startDateFormatted', 'endDateFormatted'));
    }
}
