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

        $cart = session()->get('cart', []);
        // Cek apakah produk sudah ada dalam keranjang dengan unit yang sama
        if (isset($cart[$productId])) {
            // Jika unit yang ditambahkan sama dengan unit yang sudah ada dalam keranjang
            if ($cart[$productId]['unitHidden'] == $unit) {
                // Tambahkan jumlah sesuai dengan yang diminta
                $cart[$productId]['quantity'] += $quantity;
            } else {
                // Jika unit berbeda, tambahkan produk baru dengan key unik berdasarkan unit
                $cart[$productId . '_' . $unit] = [
                    "id"=>$productId. '_' . $unit,
                    "name" => $product->namaBarang,
                    "quantity" => $quantity,
                    "unitHidden"=>$unit,
                    "unit" => $unit === 'small' ? $product->satuanTerkecil : $product->satuanBesar,
                    "price" => $unit === 'small' ? $product->hargaKecil : $product->hargaBesar,
                    "image" => $product->fotoPromosi,
                ];
            }
        } else {
            // Jika produk belum ada dalam keranjang, tambahkan sebagai item baru
            $cart[$productId] = [
                "id"=>$productId .'_' . $unit,
                "name" => $product->namaBarang,
                "quantity" => $quantity,
                "unitHidden"=>$unit,
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
        return view('customer.cart', compact('cart'));
    }
    public function addOne($id){
        $productId = $id;
        $cart = session()->get('cart', []);
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += 1;
        }
        session()->put('cart', $cart);
        // alert()->success('Success!','Berhasil menambahkan jumlah');
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
        // alert()->success('Success!','Berhasil mengurangi jumlah');
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

        // alert()->success('Success!','Berhasil');
        return back();
    }

    // public function update(Request $request)
    // {
    //     $productId = $request->input('product_id');
    //     $quantity = $request->input('quantity');

    //     $cart = session()->get('cart', []);
    //     if (isset($cart[$productId])) {
    //         $cart[$productId]['quantity'] = $quantity;
    //         session()->put('cart', $cart);
    //     }

    //     return response()->json(['message' => 'Cart updated successfully.']);
    // }
}
