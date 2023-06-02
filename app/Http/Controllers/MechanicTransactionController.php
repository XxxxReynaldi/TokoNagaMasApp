<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Mechanic;
use App\Models\MechanicTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\Paginator;
use DataTables;
use Illuminate\Support\Facades\Session;

class MechanicTransactionController extends Controller
{
    public function index(Request $request)
    {
        $mechanicTransactions = MechanicTransaction::with([
            'mechanic' => function ($query) {
                $query->select('id', 'name', 'category', 'description', 'price', 'mechanicPhotoPath');
            },
            'user' => function ($query) {
                $query->select('id', 'name', 'email', 'phone_number', 'address', 'profilePhotoPath');
            }
        ])->get();
        // dd($mechanicTransactions);

        return view('pages.m-transactions.index', compact('mechanicTransactions'));

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

    public function update(Request $request, MechanicTransaction $mechanicTransaction)
    {
        $data = $request->all();
        $request->validate([
            'status' => 'required|string|max:255',
        ]);

        $mechanicTransaction->update($data);

        return redirect()->route('mechanic-transactions.index')
            ->with('success', 'Transaction updated successfully.');
    }

    public function destroy(MechanicTransaction $mechanicTransaction)
    {
        $transaction = $mechanicTransaction;

        $imagePath = $transaction->purchaseReceiptPath;
        $folder = $transaction->user_id;
        if ($imagePath) {
            $path = parse_url($transaction->purchaseReceiptPath, PHP_URL_PATH);
            $fileName = basename($path);
            $relativePath = 'public/img/purchaseReceipt/' . $folder . '/mechanic/' . $fileName;

            if (Storage::exists($relativePath)) {
                Storage::delete($relativePath);
            }
        }

        $transaction->delete();
        return redirect()->route('mechanic-transactions.index')
            ->with('success', 'Transaction deleted successfully.');
    }
}
