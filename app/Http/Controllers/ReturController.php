<?php

namespace App\Http\Controllers;

use App\Models\Dretur;
use App\Models\Dtrans;
use App\Models\Hretur;
use App\Models\Htrans;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReturController extends Controller
{
    public function index()
    {
        $returs = Hretur::with('Dretur', 'htrans')
                ->where('UserID', auth()->id())
                ->get();
        return view('customer.retur.returIndex', compact('returs'));
    }
    public function getCompletedTransactions()
    {
        $transactions = Htrans::where('status', 3)
        ->where('fkUserID', auth()->id()) // Filter by the current logged-in user
        ->with('dtrans.product') // Include the dtrans relationship with products
        ->get();

        return response()->json($transactions);
    }

    // Method to fetch Dtrans details for a specific Htrans
    public function getTransactionItems($id)
    {
        $transaction = Htrans::with('dtrans.product')->find($id);
        
        if ($transaction) {
            return response()->json([
                'dtrans' => $transaction->dtrans,
            ]);
        }

        return response()->json([], 404); 
    }
    public function create($kodeTrans)
    {
        $transaction = Htrans::where('kodeTrans', $kodeTrans)->first();
        $dtransItems = Dtrans::where('fkHtransID', $transaction->id)
        ->with('product')  // Eager load the related product
        ->get();

        return view('customer.retur.returadd', compact('transaction', 'dtransItems'));
    }

    public function store(Request $request)
    {
        // Validate the incoming data
        $request->validate([
            'kodeTrans' => 'required',
            'TipePengembalian' => 'required|in:Pengembalian dana,Penukaran barang',
            'items' => 'required|array',
            'items.*.quantity' => 'nullable|integer|min:0',
            'items.*.reason' => 'nullable|string|max:500',
            'items.*.image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:2048',
        ]);
        // Get the transaction using the kodeTrans
        $transaction = Htrans::where('kodeTrans', $request->kodeTrans)->first();
        DB::beginTransaction();
        try { 
            $retur = new Hretur();
            $retur->HtransID = $transaction->id;
            $retur->userID = auth()->id();
            $retur->TanggalRetur = now();
            $retur->TotalHargaRetur = 0; 
            $retur->jumlahBarangRetur = 0;
            $retur->discount = 0; 
            $retur->Status = 0; 
            $retur->TipePengembalian = $request->TipePengembalian; 
            $retur->save();

            $totalHargaRetur = 0;
            $jumlahBarangRetur = 0;
            foreach ($request->items as $salesDetailID => $item) {
                // Skip if the item is not selected for return
                if (!isset($item['include']) || $item['include'] != 1) {
                    continue;
                }
            
                // Skip if quantity is not valid
                if (!isset($item['quantity']) || $item['quantity'] <= 0) {
                    continue;
                }
            
                if ($request->hasFile("items.$salesDetailID.image")) {
                    $image = $request->file("items.$salesDetailID.image");
                    $imageName = 'retur_' . time() . '_' . $salesDetailID . '.webp';
                    $imagePath = public_path('images/userUpload/' . $imageName);
            
                    if (!file_exists(public_path('images/userUpload'))) {
                        mkdir(public_path('images/userUpload'), 0755, true);
                    }
                    $this->convertToWebP($image, $imagePath);
                    $imageUrl = 'images/userUpload/' . $imageName;
                } else {
                    $imageUrl = null;
                }
            
                $dretur = new Dretur();
                $dretur->HreturID = $retur->HReturID;
                $dretur->DtransID = $salesDetailID;
                $dretur->namaBarang = $item['namaBarang'];
                $dretur->Jumlah = $item['quantity'];
                $dretur->Satuan = $item['satuan'];
                $dretur->fotobarang = $imageUrl;
                $dretur->harga = $item['price'];
                $dretur->Alasan = $item['reason'];
                $dretur->save();
            
                $totalHargaRetur += $item['quantity'] * $item['price'];
                $jumlahBarangRetur += $item['quantity'];
            }
            if ($jumlahBarangRetur === 0) {
                $retur->delete();
                return back()->withErrors(['error' => 'Tidak ada barang yang dipilih untuk diretur.']);
            }
            $retur->TotalHargaRetur = $totalHargaRetur;
            $retur->jumlahBarangRetur = $jumlahBarangRetur;
            $retur->save();
            DB::commit();
            return redirect()->route('retur.index')->with('success', 'Retur berhasil diajukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            alert()->error('Error!', $e->getMessage());
            return back();
        }
    }
    public function cancelRetur($id)
    {
        $retur = Hretur::find($id);
        if ($retur && $retur->Status == 0) {
            $retur->Status = 3; // Mark as canceled by customer
            $retur->save();

            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false], 400);
    }
    public function getDetails($hreturID)
    {
        $retur = Hretur::with('Dretur.salesDetail.product')->findOrFail($hreturID);
        $response = [
            'HReturID' => $retur->HReturID,
            'TanggalRetur' => $retur->TanggalRetur,
            'jumlahBarangRetur' => $retur->jumlahBarangRetur,
            'TipePengembalian' => $retur->TipePengembalian,
            'TotalHargaRetur' => $retur->TotalHargaRetur,
            'Dretur' => $retur->Dretur->map(function ($item) {
                return [
                    'namaBarang' => $item->salesDetail->product->namaBarang, 
                    'Jumlah' => $item->Jumlah,
                    'Satuan' => $item->satuan,
                    'harga' => $item->harga,
                    'alasan' => $item->alasan,
                   'fotobarang' => asset($item->fotobarang),
                ];
            }),
        ];

        return response()->json($response);
    }

    public function updateReturnType(Request $request, $returID)
    {
        $retur = Hretur::findOrFail($returID);
    
        // Pastikan hanya bisa mengubah tipe pengembalian jika status adalah 0
        if ($retur->Status == 0) {
            $retur->TipePengembalian = $request->tipePengembalian;
            $retur->save();

            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false]);
        }
    }
     /**
     * Convert an image to WebP format using GD library.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param string $outputWebPPath Path where the WebP image will be saved
     * @return void
     */
    private function convertToWebP($file, $outputWebPPath){
        $extension = $file->extension();
            $image = null;
        
            switch ($extension) {
                case 'jpeg':
                case 'jpg':
                    $image = imagecreatefromjpeg($file->getPathname());
                    break;
                case 'png':
                    $image = imagecreatefrompng($file->getPathname());
                    break;
                case 'gif':
                    $image = imagecreatefromgif($file->getPathname());
                    break;
                case 'webp':
                    $file->move(dirname($outputWebPPath), basename($outputWebPPath));
                    return;
                default:
                    alert()->error('Error!', 'Unsupported image format');
                    return back();
            }
        
            if (!$image) {
                alert()->error('Error!', 'Image creation failed');
                return back();
            }
        
            // Ensure the image is in true color
            $trueColorImage = imagecreatetruecolor(imagesx($image), imagesy($image));
            imagealphablending($trueColorImage, false);
            imagesavealpha($trueColorImage, true);
        
            // Copy the original image into the true color image
            imagecopy($trueColorImage, $image, 0, 0, 0, 0, imagesx($image), imagesy($image));
        
            // Convert to WebP and save
            imagewebp($trueColorImage, $outputWebPPath, 75);
        
            // Free memory
            imagedestroy($image);
            imagedestroy($trueColorImage);
        }
}
