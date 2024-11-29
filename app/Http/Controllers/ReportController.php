<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Dtrans;
use App\Models\Htrans;
use App\Models\Membership;
use App\Models\Products;
use App\Models\retur;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    //
    public function laporanStok(Request $request) {
        $categories = Category::all();
        $query = Products::with('category');
    
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

        $netRevenue = $transactions->sum('totalPembelian');
        $totalDiscount = $transactions->sum('discount');
        $grossRevenue = $netRevenue + $totalDiscount;

        $startDate = Carbon::parse($startDate)->format('d/m/Y');
        $endDate = Carbon::parse($endDate)->format('d/m/Y');
        
        return view('admin.laporan.laporanPendapatan', [
            'transactions' => $transactions,
            'grossRevenue' => $grossRevenue,
            'totalDiscount' => $totalDiscount,
            'netRevenue' => $netRevenue,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }

    public function laporanPenjualan(Request $request)
    {
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
    
        $startDateFormatted = $request->filled('start_date') 
            ? Carbon::parse($request->start_date)->format('d/m/Y') 
            : null;
    
        $endDateFormatted = $request->filled('end_date') 
            ? Carbon::parse($request->end_date)->format('d/m/Y') 
            : null;
    
        return view('admin.laporan.laporanPenjualan', compact('salesPerProduct', 'startDateFormatted', 'endDateFormatted'));
    }
    public function laporanAktif(Request $request){
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

            return $user;
        });

        return view('admin.laporan.laporanAktif', compact('customers'));
    }
    public function laporanRetur(Request $request){
        $query = retur::with(['dtrans.product', 'htrans']);
       if ($request->filled('start_date') && $request->filled('end_date')) {
                   $query->whereBetween(DB::raw('DATE(tanggalRetur)'), [
                       $request->start_date, 
                       $request->end_date
                   ]);
               } elseif ($request->filled('start_date')) {
                   $query->whereDate('tanggalRetur', '>=', $request->start_date);
               } elseif ($request->filled('end_date')) {
                   $query->whereDate('tanggalRetur', '<=', $request->end_date);
               }


       if ($request->has('status')) {
           $query->where('status', $request->status);
       }

       $returns = $query->get();
       $startDateFormatted = $request->filled('start_date') 
       ? Carbon::parse($request->start_date)->format('d/m/Y') 
       : null;

       $endDateFormatted = $request->filled('end_date') 
       ? Carbon::parse($request->end_date)->format('d/m/Y') 
       : null;
       return view('admin.laporan.laporanRetur', compact('returns','startDateFormatted', 'endDateFormatted'));
   }
}
