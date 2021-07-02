<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Darryldecode\Cart\Cart;

class CartController extends Controller
{
    public function index()
    {
        return view('cart.index');
    }


    public function addToCart(Request $request)
    {
        $product = Product::where('id', $request->id)->first();

//        если нет куки, то добавляется куки
        if (!isset($_COOKIE['cart_id'])) setcookie('cart_id', uniqid());
        $cart_id = $_COOKIE['cart_id'];
        \Cart::session($cart_id);

        \Cart::add([
            'id' => $product->id,
            'name' => $product->title,
            'price' => $product->new_price ? $product->new_price : $product->price,
            'quantity' => (int)$request->qty,
            'attributes' => [
                'img' => isset($product->images[0]->img) ? $product->images[0]->img : 'no_image.jpg'
            ]
        ]);

        return response()->json(\Cart::getContent());
//        return response()->json(['data' => $request->id]);
    }
}
