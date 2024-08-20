<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
class CartController extends Controller
{
    public function add(Request $request)
    {
        $productId = $request->input('IDbarang');
        $quantity = $request->input('quantity', 1);
        $unit = $request->input('unit');
        echo $quantity;
        echo $unit;
        echo $productId;

        $product = Products::find($productId);
        if (!$product) {
            return response()->json(['message' => 'Product not found.'], 404);
        }

        $cart = session()->get('cart', []);

        // if (isset($cart[$productId])) {
        //     $cart[$productId]['quantity'] += $quantity;
        // } else {
        //     if($unit == "small"){
        //         $cart[$productId] = [
        //             "name" => $product->name,
        //             "quantity" => $quantity,
        //             "unit"=>$product->satuanTerkecil,
        //             "price" => $product->hargaKecil,
        //             "image" => $product->fotoPromosi
        //         ];
        //     }
            
        // }

        // session()->put('cart', $cart);
        if (session()->has('cart')) {
            echo "ada";
        }else{
            echo "tidak";
        }

        $cart = session()->get('cart', []);
        // Cek apakah produk sudah ada dalam keranjang dengan unit yang sama
        if (isset($cart[$productId])) {
            // Jika unit yang ditambahkan sama dengan unit yang sudah ada dalam keranjang
            if ($cart[$productId]['unit'] == $unit) {
                // Tambahkan jumlah sesuai dengan yang diminta
                $cart[$productId]['quantity'] += $quantity;
            } else {
                // Jika unit berbeda, tambahkan produk baru dengan key unik berdasarkan unit
                $cart[$productId . '_' . $unit] = [
                    "name" => $product->namaBarang,
                    "quantity" => $quantity,
                    "unit" => $unit === 'small' ? $product->satuanTerkecil : $product->satuanBesar,
                    "price" => $unit === 'small' ? $product->hargaKecil : $product->hargaBesar,
                    "image" => $product->fotoPromosi,
                ];
            }
        } else {
            // Jika produk belum ada dalam keranjang, tambahkan sebagai item baru
            $cart[$productId] = [
                "name" => $product->namaBarang,
                "quantity" => $quantity,
                "unit" => $unit === 'small' ? $product->satuanTerkecil : $product->satuanBesar,
                "price" => $unit === 'small' ? $product->hargaKecil : $product->hargaBesar,
                "image" => $product->fotoPromosi,
            ];
        }

        // Simpan cart ke session
        session()->put('cart', $cart);
        alert()->success('Success!','Berhasil menambahkan ke keranjang');
        return back();

        // return response()->json(['message' => 'Item added to cart successfully.']);
    }
    public function view()
    {
        $cart = session()->get('cart', []);
        return view('cart.index', compact('cart'));
    }

    public function remove(Request $request)
    {
        $productId = $request->input('product_id');
        $cart = session()->get('cart', []);

        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
        }

        return response()->json(['message' => 'Item removed from cart successfully.']);
    }

    public function update(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity');

        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] = $quantity;
            session()->put('cart', $cart);
        }

        return response()->json(['message' => 'Cart updated successfully.']);
    }
}
