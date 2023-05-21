<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\CartProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CartProductController extends Controller
{
    public function index()
    {
        $cartProduct = CartProduct::with([
            'product' => function ($query) {
                $query->select('id', 'name', 'price', 'stock', 'productPhotoPath');
            },
            'user' => function ($query) {
                $query->select('id', 'name', 'email', 'phone_number', 'address', 'profilePhotoPath');
            }
        ])->get();

        return ResponseFormatter::success(['cartProduct' => $cartProduct], 'Cart product retrieved successfully');
    }

    public function filter(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 6);

        $user_id = $request->input('user_id');
        $product_id = $request->input('product_id');

        // $quantity = $request->input('quantity');
        // $price = $request->input('price');
        // $status_check = $request->input('status_check');

        if ($id) {
            $cartProduct = CartProduct::with([
                'product' => function ($query) {
                    $query->select('id', 'name', 'price', 'stock', 'productPhotoPath');
                },
                'user' => function ($query) {
                    $query->select('id', 'name', 'email', 'phone_number', 'address', 'profilePhotoPath');
                }
            ])->find($id);

            if ($cartProduct)
                return ResponseFormatter::success($cartProduct, 'Data cart product retrieved successfully');
            else
                return ResponseFormatter::error(null, 'Data cart product not found', 404);
        }

        $cartProduct = CartProduct::with([
            'product' => function ($query) {
                $query->select('id', 'name', 'price', 'stock', 'productPhotoPath');
            },
            'user' => function ($query) {
                $query->select('id', 'name', 'email', 'phone_number', 'address', 'profilePhotoPath');
            }
        ]);

        if ($user_id)
            $cartProduct->where('user_id', $user_id);

        if ($product_id)
            $cartProduct->where('product_id', $product_id);


        return ResponseFormatter::success(
            $cartProduct->paginate($limit),
            'Data list product retrieved successfully'
        );
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $user_id = $data['user_id'];
        $product_id = $data['product_id'];
        $validator = Validator::make($data, [
            'user_id' => 'required|int',
            'product_id' => 'required|int',
            'quantity' => 'required|int|min:1|max:99',
            // 'price' => 'required|int|min:0',
            'status_check' => 'required',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(['error' => $validator->errors()], 'Add cart product fails', 400);
        }

        $user = User::find($user_id);
        if (!$user) {
            return ResponseFormatter::error(['error' => $validator->errors()], 'Add cart product fails, User Not Found', 400);
        }

        $product = Product::find($product_id);
        if (!$product) {
            return ResponseFormatter::error(['error' => $validator->errors()], 'Add cart product fails, Product Not Found', 400);
        }

        $data['price'] = $data['quantity'] * $product->price;

        $cartProduct = CartProduct::updateOrCreate(
            [
                'user_id' => $user_id,
                'product_id' => $product_id
            ],
            $data
        );

        return ResponseFormatter::success(['cartProduct' => $cartProduct], 'Cart product inserted successfully');
    }

    public function massUpdate(Request $request, $user_id)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'product_id' => 'required|array',
            'product_id.*' => 'required|int|exists:products,id',
            'quantity' => 'required|array',
            'quantity.*' => 'required|integer|min:1|max:100',
            'status_check' => 'required|array',
            'status_check.*' => 'required|int|min:0|max:1',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(['error' => $validator->errors()], 'Update cart products failed', 400);
        }

        // dump($data);

        DB::beginTransaction();

        try {
            $productIds = $data['product_id'];
            $quantities = $data['quantity'];

            $products = Product::whereIn('id', $data['product_id'])->get();
            $prices = $products->map(function ($product, $index) use ($data) {
                $quantity = $data['quantity'][$index];
                return $quantity * $product['price'];
            })->toArray();

            $statusChecks = $data['status_check'];

            // Loop melalui data dan lakukan update pada setiap row
            foreach ($productIds as $index => $productId) {
                CartProduct::where('product_id', $productId)
                    ->where('user_id', $user_id)
                    ->update([
                        'quantity' => $quantities[$index],
                        'price' => $prices[$index],
                        'status_check' => $statusChecks[$index]
                    ]);
            }

            // Commit transaksi jika semua operasi berhasil
            DB::commit();

            // Ambil data CartProduct setelah update
            $cartProducts = CartProduct::where('user_id', $user_id)->whereIn('product_id', $productIds)->get();

            return ResponseFormatter::success(['cartProducts' => $cartProducts], 'Cart products updated successfully');
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();
            return ResponseFormatter::error(['error' => $e->getMessage()], 'Update cart products failed', 500);
        }

        // return ResponseFormatter::success(['cartProducts' => $cartProducts], 'Cart products updated successfully');
    }

    public function destroy($id)
    {
        $cartProduct = CartProduct::with([
            'product' => function ($query) {
                $query->select('id', 'name', 'price', 'stock', 'productPhotoPath');
            },
            'user' => function ($query) {
                $query->select('id', 'name', 'email', 'phone_number', 'address', 'profilePhotoPath');
            }
        ])->find($id);
        if (!$cartProduct) {
            return ResponseFormatter::error(['error' => 'Cart Product Not Found'], 'Cart Product Not Found', 404);
        }

        $cartProduct->delete();
        return ResponseFormatter::success(['cartProduct' => $cartProduct], 'Cart Product deleted successfully');
    }

    public function massDestroy(Request $request, $user_id)
    {

        $user = User::find($user_id);
        if (!$user) {
            return ResponseFormatter::error(['error' => 'User Not Found'], 'Mass delete cart products failed, User Not Found', 400);
        }

        $ids = $request->input('ids');
        $cartProduct = CartProduct::with([
            'product' => function ($query) {
                $query->select('id', 'name', 'price', 'stock', 'productPhotoPath');
            },
            'user' => function ($query) {
                $query->select('id', 'name', 'email', 'phone_number', 'address', 'profilePhotoPath');
            }
        ])->whereIn('id', $ids)->where('user_id', $user_id)->get();

        if ($cartProduct->isEmpty()) {
            return ResponseFormatter::error(['error' => 'Cart Product Not Found'], 'Mass delete cart products failed', 400);
        }

        CartProduct::whereIn('id', $ids)->delete();
        // return response()->json(['message' => 'Cart Product deleted successfully.']);
        return ResponseFormatter::success(['cartProduct' => $cartProduct], 'Cart Product deleted successfully');
    }
}
