<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\Paginator;
use DataTables;
use Illuminate\Support\Facades\Session;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::all();
        return view('pages.products.index', compact('products'));

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

    public function store(Request $request)
    {
        $data = $request->all();
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'productPhotoPath' => 'required|image|max:4096',
        ]);

        if ($request->hasFile('productPhotoPath')) {
            $image = $request->file('productPhotoPath');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $productPhotoPath = $request->file('productPhotoPath')->storeAs('public/img/photoProduct', $imageName);
            $imageUrl = url('') . Storage::url($productPhotoPath);
            $data['productPhotoPath'] = $imageUrl;
            Product::create($data);
        }

        return redirect()->route('products.index')
            ->with('success', 'Product created successfully.');
    }


    public function update(Request $request, Product $product)
    {
        $data = $request->all();
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'stock' => 'required|integer|min:0',
            'productPhotoPath' => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('productPhotoPath')) {
            $image = $request->file('productPhotoPath');
            $imageName = time() . '_' . $image->getClientOriginalName();

            $productPhotoPath = $request->file('productPhotoPath')->storeAs('public/img/photoProduct', $imageName);
            $imageUrl = url('') . Storage::url($productPhotoPath);

            /**
             * $path: pisahkan http://127.0.0.1:8000 menjadi /img/photoProduct/{file}
             * 
             * $relativePath : buat link /var/www/myapp/public/img/photoProduct/{file}
             */

            if ($product->productPhotoPath) {
                $path = parse_url($product->productPhotoPath, PHP_URL_PATH);
                $fileName = basename($path);
                $relativePath = 'public/img/photoProduct/' . $fileName;

                if (Storage::exists($relativePath)) {
                    Storage::delete($relativePath);
                }
            }

            $data['productPhotoPath'] = $imageUrl;
        }

        $product->update($data);

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        if ($product->productPhotoPath) {
            $path = parse_url($product->productPhotoPath, PHP_URL_PATH);
            $fileName = basename($path);
            $relativePath = 'public/img/photoProduct/' . $fileName;

            if (Storage::exists($relativePath)) {
                Storage::delete($relativePath);
            }
        }

        $product->delete();
        return redirect()->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
