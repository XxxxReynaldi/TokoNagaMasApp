<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\CartProduct;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'quantity' => 'required|int|min:0',
            'price' => 'required|int|min:0',
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
            'cart_products.*.product_id' => 'required|integer',
            'cart_products.*.price' => 'required|integer|min:0',
            'cart_products.*.quantity' => 'required|integer|min:0',
            'cart_products.*.status_check' => 'required',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(['error' => $validator->errors()], 'Update cart products failed', 400);
        }

        $cartProductsData = $data['cart_products'];
        $updatedCartProducts = [];

        foreach ($cartProductsData as $cartProductData) {
            $user_id = $cartProductData['user_id'];
            $product_id = $cartProductData['product_id'];

            $user = User::find($user_id);
            if (!$user) {
                return ResponseFormatter::error(['error' => $validator->errors()], 'Update cart products failed, User Not Found', 400);
            }

            $product = Product::find($product_id);
            if (!$product) {
                return ResponseFormatter::error(['error' => $validator->errors()], 'Update cart products failed, Product Not Found', 400);
            }

            $cartProduct = CartProduct::updateOrCreate(
                [
                    'user_id' => $user_id,
                    'product_id' => $product_id
                ],
                $cartProductData
            );

            $updatedCartProducts[] = $cartProduct;
        }

        return ResponseFormatter::success(['cartProducts' => $updatedCartProducts], 'Cart products updated successfully');
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
