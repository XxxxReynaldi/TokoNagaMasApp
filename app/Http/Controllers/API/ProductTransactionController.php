<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\CartProduct;
use App\Models\Product;
use App\Models\ProductTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProductTransactionController extends Controller
{
    public function checkout(Request $request)
    {
        $data = $request->all();
        $user_id    = $request->input('user_id');

        $validator = Validator::make($data, [
            'user_id' => 'required|int|exists:users,id',
            'bank_account_name' => 'required|regex:/^[a-zA-Z\s]*$/',
            'purchaseReceiptPath' => 'required|image|mimes:jpeg,png,jpg|max:4096',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(['error' => $validator->errors()], 'Check out failed', 400);
        }

        $user = User::find($user_id);
        if (!$user) {
            return ResponseFormatter::error(['error' => 'User Not Found'], 'Check out failed', 404);
        }

        // $id = $request->input('id');
        $cartProducts = CartProduct::with([
            'product' => function ($query) {
                $query->select('id', 'name', 'price', 'stock', 'productPhotoPath');
            },
            'user' => function ($query) {
                $query->select('id', 'name', 'email', 'phone_number', 'address', 'profilePhotoPath');
            }
        ])->where('user_id', $user_id)->where('status_check', 1)->get();


        $product_id     = $cartProducts->pluck('product.id');
        $stock          = $cartProducts->pluck('product.stock');

        $status_check   = $cartProducts->pluck('status_check');
        $quantities     = $cartProducts->pluck('quantity');
        $prices         = $cartProducts->pluck('price');

        $products = Product::whereIn('id', $product_id);
        if ($products->get()->isEmpty()) {
            return ResponseFormatter::error(['error' => 'Product Not Found'], 'Check out failed', 404);
        }

        $missingProductId = collect($product_id)->diff($products->pluck('id'));

        if ($missingProductId->isNotEmpty()) {
            $missingProducts = $missingProductId->map(function ($product_id) {
                return [
                    'id' => $product_id,
                    'name' => 'Product ' . $product_id,
                    'description' => 'This product is not found in the database',
                ];
            })->values();

            return ResponseFormatter::error([
                'error' => 'Product Not Found',
                'products' => $missingProducts,
            ], 'Check out failed', 404);
        }

        if ($stock->contains(0) || $stock->contains(function ($value, $key) {
            return $value < 0;
        })) {
            $outOfStock = $products->where('stock', '<=', 0)->get();
            return ResponseFormatter::error([
                'error' => 'Product out of stock',
                'message' => 'Please pick another product',
                'products' => $outOfStock,
            ], 'Check out failed', 400);
        }

        foreach ($cartProducts as $cartProduct) {
            $product = $cartProduct->product;
            $quantity = $cartProduct->quantity;

            if ($quantity > $product->stock) {
                // Quantity melebihi jumlah stock produk
                return ResponseFormatter::error([
                    'error' => 'Quantity exceeds stock',
                    'message' => 'The requested product exceeds the available stock for product ' . $product->name,
                ], 'Check out failed', 400);
            }
        }

        $folder = $user_id;
        $image = $request->file('purchaseReceiptPath');
        $imageName = time() . '_' . $image->getClientOriginalName();

        $purchaseReceiptPath = $request->file('purchaseReceiptPath')->storeAs('public/img/purchaseReceipt/' . $folder . '/product', $imageName);
        $data['status'] = 'pending';
        $data['purchaseReceiptPath'] = url('') . Storage::url($purchaseReceiptPath);

        $detailData = $cartProducts->map(function ($cartProduct) {
            $product = Product::find($cartProduct->product_id);
            $product->stock -= $cartProduct->quantity;
            $product->save();

            return [
                'product_id' => $cartProduct->product_id,
                'quantity' => $cartProduct->quantity,
                'price' => $cartProduct->price
            ];
        })->toArray();

        $total_price = $cartProducts->sum('price');
        $data['total_price'] = $total_price;

        $transaction = ProductTransaction::create($data);

        // hubungkan detail product transaction dengan ProductTransaction yang baru saja dibuat
        $transaction->products()->attach($detailData);

        // hapus product dari semua cart product yang terkait
        CartProduct::where('user_id', $user_id)->whereIn('product_id', $product_id)->where('status_check', 1)->delete();

        // update status_check dari semua cart product yang terkait menjadi false
        // $cartProducts->each(function ($cartProduct) {
        //     $cartProduct->update(['status_check' => 0]);
        // });
        return ResponseFormatter::success(['transaction' => $transaction], 'Show transaction successfully');
    }


    public function destroy($id)
    {
        $transaction = ProductTransaction::find($id);
        // $query2 = Product::select('products.id', 'products.name', 'products.price AS product_price', 'products.stock', 'products.description', 'products.productPhotoPath', 'transaction_details.product_transaction_id AS pivot_product_transaction_id', 'transaction_details.product_id AS pivot_product_id', 'transaction_details.quantity AS pivot_quantity', 'transaction_details.price AS pivot_price')
        //     ->join('transaction_details', 'products.id', '=', 'transaction_details.product_id')
        //     ->whereIn('transaction_details.product_transaction_id', [$id]);

        // $detailTransaction = $query2->get();

        if (!$transaction) {
            return ResponseFormatter::error(['error' => 'Transaction Not Found'], 'Transaction Not Found', 404);
        }

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
        return ResponseFormatter::success(['transaction' => $transaction], 'Transaction deleted successfully');
    }

    public function getProdutTransaction(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 6);

        $user_id = $request->input('user_id');

        if ($id) {
            $productTransaction = ProductTransaction::with([
                'products' => function ($query) {
                    $query->select('product_id', 'name', 'description', 'products.price', 'productPhotoPath');
                },
                'user' => function ($query) {
                    $query->select('id', 'name', 'email', 'phone_number', 'address', 'profilePhotoPath');
                }
            ])->find($id);

            if ($productTransaction)
                return ResponseFormatter::success($productTransaction, 'Data product transaction retrieved successfully');
            else
                return ResponseFormatter::error(null, 'Data product transaction not found', 404);
        }

        $productTransaction = ProductTransaction::with([
            'products' => function ($query) {
                $query->select('product_id', 'name', 'description', 'products.price', 'productPhotoPath');
            },
            'user' => function ($query) {
                $query->select('id', 'name', 'email', 'phone_number', 'address', 'profilePhotoPath');
            }
        ]);

        if ($user_id)
            $productTransaction->where('user_id', $user_id);

        $payload = JWTAuth::parseToken()->getPayload();
        $role_id = $payload->get('user')['role_id'];

        /***
         * Jika role_id = 2
         * pastikan terdapat param user_id yang bersangkutan
         */
        if ($role_id == 2 && !$user_id) {
            return ResponseFormatter::error(null, 'Data product transaction not found', 404);
        }


        return ResponseFormatter::success(
            $productTransaction->paginate($limit),
            'Data list product transaction retrieved successfully'
        );
    }
}
