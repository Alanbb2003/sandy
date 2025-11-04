<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use Laravel\Ui\Presets\React;
use RealRashid\SweetAlert\Facades\Alert;
class CartController extends Controller
{
    public function add(Request $request)
    {
        $productId = $request->input('IDbarang');
        $quantity = $request->input('quantity', 1);
        $unit = $request->input('unit');

        $product = Products::find($productId);
        if (!$product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        $jumlahKecil = $product->totalQuantity;
        $jumlahBesar = $product->isiSatuanBesar != null ? $product->totalQuantity / $product->isiSatuanBesar : null;

        // Determine available quantity based on the unit
        $availableQuantity = $unit == "small" ? $jumlahKecil : $jumlahBesar;

        $cart = session()->get('cart', []);
        $currentSmallQuantity = 0;
        $currentLargeQuantity = 0;

        // Check current quantities in the cart for the same product
        foreach ($cart as $item) {
            if ($item['productID'] == $productId) {
                if ($item['unitHidden'] == 'small') {
                    $currentSmallQuantity += $item['quantity'];
                } elseif ($item['unitHidden'] == 'large') {
                    $currentLargeQuantity += $item['quantity'];
                }
            }
        }

        // Calculate the total stock used from both units
        $usedQuantityFromLarge = $currentLargeQuantity * ($product->isiSatuanBesar ?? 1);
        $totalUsedQuantity = $currentSmallQuantity + $usedQuantityFromLarge;

        // Calculate the stock that would be used by this addition
        $requestedQuantityInSmall = $unit == "small" 
            ? $quantity 
            : $quantity * ($product->isiSatuanBesar ?? 1);

        // Ensure the total does not exceed available stock
        if ($totalUsedQuantity + $requestedQuantityInSmall > $jumlahKecil) {
            $remainingStock = $jumlahKecil - $totalUsedQuantity;
            toast("Produk ini hanya tersisa $remainingStock unit yang bisa ditambahkan.", 'warning');
            return back();
        }

        // Add or update the cart
        if (isset($cart[$productId])) {
            if ($cart[$productId]['unitHidden'] == $unit) {
                $cart[$productId]['quantity'] += $quantity;
            } else {
                $cart[$productId . '_' . $unit] = [
                    "id" => $productId . '_' . $unit,
                    "productID" => $productId,
                    "name" => $product->namaBarang,
                    "quantity" => $quantity,
                    "unitHidden" => $unit,
                    "unit" => $unit === 'small' ? $product->satuanTerkecil : $product->satuanBesar,
                    "price" => $unit === 'small' ? $product->hargaKecil : $product->hargaBesar,
                    "image" => $product->fotoPromosi,
                ];
            }
        } else {
            $cart[$productId] = [
                "id" => $productId . '_' . $unit,
                "productID" => $productId,
                "name" => $product->namaBarang,
                "quantity" => $quantity,
                "unitHidden" => $unit,
                "unit" => $unit === 'small' ? $product->satuanTerkecil : $product->satuanBesar,
                "price" => $unit === 'small' ? $product->hargaKecil : $product->hargaBesar,
                "image" => $product->fotoPromosi,
            ];
        }

        session()->put('cart', $cart);
        alert()->success('Success!', 'Berhasil menambahkan ke keranjang');
        return back();
    }

    public function view()
    {
        $cart = session()->get('cart', []);
        $totalAmmount = 0;

        foreach ($cart as $item) {
            $totalAmmount += $item['price'] * $item['quantity'];
        }
        return view('customer.cart', compact('cart','totalAmmount'));
    }
    public function addOne($id){
        $productId = $id;
        $product = Products::where('id',$productId)->first();
        // echo($product);
        $productsmall = $product->totalQuantity;
        if($product->isiSatuanBesar != null){
            $productbig = $product->totalQuantity / $product->isiSatuanBesar;
        } 
        
        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            if($cart[$productId]['unitHidden'] == "small"){
                if($productsmall <= $cart[$productId]['quantity']){
                    toast("Beberapa Produk sedang kosong, produk ini hanya tersedia $productsmall",'warning');
                    return back();
                }else{
                    $cart[$productId]['quantity'] += 1;
                }
            }else{
                if($productbig <= $cart[$productId]['quantity']){
                    $showquantity = floor($productbig);
                    toast("Beberapa Produk sedang kosong, produk ini hanya tersedia $showquantity",'warning');
                    return back();
                }else{
                    $cart[$productId]['quantity'] += 1;
                }
            }
        }
        session()->put('cart', $cart);
        return back();
    }
    public function removeOne($id){
        $productId = $id;
        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            if($cart[$productId]['quantity'] == 1){
                unset($cart[$productId]);
            }else{
                $cart[$productId]['quantity'] -= 1;
            }
            
        }
        session()->put('cart', $cart);
        toast("Berhasil mengurangi produk",'info');
        return back();
    }
    public function remove($id)
    {
        $productId = $id;
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        toast("Berhasil menghilangkan produk",'info');
        return back();
    }
}
