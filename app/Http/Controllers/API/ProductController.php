<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function filter(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 6);
        $name = $request->input('name');

        $price_from = $request->input('price_from');
        $price_to = $request->input('price_to');

        $stock_from = $request->input('stock_from');
        $stock_to = $request->input('stock_to');


        if ($id) {
            $product = Product::find($id);

            if ($product)
                return ResponseFormatter::success($product, 'Data product retrieved successfully');
            else
                return ResponseFormatter::error(null, 'Data product not found', 404);
        }

        $product = Product::query();

        if ($name)
            $product->where('name', 'like', '%' . $name . '%');

        if ($price_from)
            $product->where('price', '>=', $price_from);

        if ($price_to)
            $product->where('price', '<=', $price_to);

        if ($stock_from)
            $product->where('stock', '>=', $stock_from);

        if ($stock_to)
            $product->where('stock', '<=', $stock_to);


        return ResponseFormatter::success(
            $product->paginate($limit),
            'Data list product retrieved successfully'
        );
    }

    public function index()
    {

        // Jika header authorization tidak kosong, ambil user yang login
        // $user = Auth::guard('sanctum')->user();

        $products = Product::all();

        return ResponseFormatter::success(['products' => $products], 'Products retrieved successfully');

        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'Data products retrieved successfully',
        //     'data' => $products,
        // ]);
    }

    public function show(Request $request, $id)
    {
        $product = Product::find($id);
        if (!$product) {
            return ResponseFormatter::error(['error' => 'Product Not Found'], 'Product Not Found', 404);
        }
        return ResponseFormatter::success($request->product(), 'Show Data Product Success');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'productPhotoPath' => 'required|image|max:4096',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(['error' => $validator->errors()], 'Add product fails', 400);
        }

        $image = $request->file('productPhotoPath');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $imagePath = public_path('img/photoProduct');
        $imageUrl = url('img/photoProduct/' . $imageName);

        $image->move($imagePath, $imageName);


        $data['productPhotoPath'] = $imageUrl;
        $product = Product::create($data);

        return ResponseFormatter::success(['product' => $product], 'Product inserted successfully');
    }


    public function update(Request $request, $id)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'productPhotoPath' => 'nullable|image|max:4096',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(['error' => $validator->errors()], 'Add product fails', 400);
        }

        $product = Product::find($id);
        if (!$product) {
            return ResponseFormatter::error(['error' => 'Product Not Found'], 'Product Not Found', 404);
        }

        if ($request->hasFile('productPhotoPath')) {
            $image = $request->file('productPhotoPath');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = public_path('img/photoProduct');
            $imageUrl = url('img/photoProduct/' . $imageName);

            /**
             * $path: pisahkan http://127.0.0.1:8000 menjadi /img/photoProduct/{file}
             * 
             * $relativePath : buat link /var/www/myapp/public/img/photoProduct/{file}
             */

            $path = parse_url($product->productPhotoPath, PHP_URL_PATH);
            $relativePath = public_path($path);

            if (file_exists($relativePath)) {
                unlink($relativePath);
            }
            $image->move($imagePath, $imageName);

            $data['productPhotoPath'] = $imageUrl;
        }

        $product->update($data);

        return ResponseFormatter::success(['product' => $product], 'Products updated successfully');
    }

    public function destroy($id)
    {
        $product = Product::find($id);
        if (!$product) {
            return ResponseFormatter::error(['error' => 'Product Not Found'], 'Product Not Found', 404);
        }

        if ($product->productPhotoPath) {
            $imagePath = public_path('img/photoProduct') . '/' . basename($product->productPhotoPath);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        $product->delete();
        return ResponseFormatter::success(['product' => $product], 'Products deleted successfully');
    }

    public function massDestroy(Request $request)
    {
        // memeriksa apakah pengguna memiliki akses untuk melakukan aksi ini
        $user = Auth::user();
        if (!$user) {
            return ResponseFormatter::error(['message' => 'Unauthorized'], 'Authentication Failed', 401);
        }

        $ids = $request->input('ids');
        $products = Product::whereIn('id', $ids)->get();
        foreach ($products as $product) {
            if ($product->productPhotoPath) {
                $imagePath = public_path('img/photoProduct') . '/' . basename($product->productPhotoPath);
                if (File::exists($imagePath)) {
                    File::delete($imagePath);
                }
            }
        }
        Product::whereIn('id', $ids)->delete();
        return response()->json(['message' => 'Products deleted successfully.']);
    }
}
