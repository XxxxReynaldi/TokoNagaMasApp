<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Product;
use App\Models\ProductTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\Paginator;
use DataTables;
use Illuminate\Support\Facades\Session;

class ProductTransactionController extends Controller
{
    public function index(Request $request)
    {
        $productTransactions = ProductTransaction::with([
            'products' => function ($query) {
                $query->select('product_id', 'name', 'description', 'products.price', 'productPhotoPath');
            },
            'user' => function ($query) {
                $query->select('id', 'name', 'email', 'phone_number', 'address', 'profilePhotoPath');
            }
        ])->get();
        // dd($productTransactions);

        return view('pages.p-transactions.index', compact('productTransactions'));

        // if (request()->ajax()) {
        //     $products = Product::select(['id', 'name', 'category', 'price', 'description', 'status', 'productPhotoPath'])->get();
        //     return DataTables::of($products)
        //         ->addColumn('action', function ($product) {
        //             return view('products.action', compact('product'));
        //         })
        //         ->addColumn('status_label', function ($product) {
        //             return $product->status == 1 ? 'Aktif' : 'Tidak Aktif';
        //         })
        //         ->toJson();
        // }

        // return view('products.index');
    }


    public function update(Request $request, ProductTransaction $productTransaction)
    {
        $data = $request->all();
        $request->validate([
            'status' => 'required|string|max:255',
        ]);

        $productTransaction->update($data);
        // dd($productTransaction);
        return redirect()->route('product-transactions.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(ProductTransaction $productTransaction)
    {
        $transaction = $productTransaction;
        // $query2 = Product::select('products.id', 'products.name', 'products.price AS product_price', 'products.stock', 'products.description', 'products.productPhotoPath', 'transaction_details.product_transaction_id AS pivot_product_transaction_id', 'transaction_details.product_id AS pivot_product_id', 'transaction_details.quantity AS pivot_quantity', 'transaction_details.price AS pivot_price')
        //     ->join('transaction_details', 'products.id', '=', 'transaction_details.product_id')
        //     ->whereIn('transaction_details.product_transaction_id', [$id]);

        // $detailTransaction = $query2->get();

        $imagePath = $transaction->purchaseReceiptPath;
        $folder = $transaction->user_id;
        if ($imagePath) {
            $path = parse_url($transaction->purchaseReceiptPath, PHP_URL_PATH);
            $fileName = basename($path);
            $relativePath = 'public/img/purchaseReceipt/' . $folder . '/product/' . $fileName;

            if (Storage::exists($relativePath)) {
                Storage::delete($relativePath);
            }
        }

        $transaction->delete();
        return redirect()->route('product-transactions.index')
            ->with('success', 'Transaction deleted successfully');
    }
}
