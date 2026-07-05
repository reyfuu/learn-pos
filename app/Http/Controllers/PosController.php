<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Transaction;

class PosController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('pos.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cart' => 'required|array',
            'subtotal' => 'required|numeric',
            'tax' => 'required|numeric',
            'grand_total' => 'required|numeric',
        ]);

        $transaction = Transaction::create([
            'invoice_number' => 'INV-' . time(),
            'subtotal' => $request->subtotal,
            'tax' => $request->tax,
            'grand_total' => $request->grand_total,
        ]);

        foreach ($request->cart as $item) {
            $transaction->details()->create([
                'product_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
        }

        return response()->json(['success' => true, 'invoice_number' => $transaction->invoice_number]);
    }
}
