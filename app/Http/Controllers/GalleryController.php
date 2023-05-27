<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\Paginator;
use DataTables;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\API\ProductController as ProductControllerAPI;
use App\Models\Product;

class GalleryController extends Controller
{
    public function index(Request $request)
    {
        $galleries = Gallery::with([
            'products' => function ($query) {
                $query->select('id', 'name', 'price', 'stock', 'description', 'productPhotoPath');
            }
        ])->get();

        return view('pages.galleries.index', compact('galleries'));

        // if (request()->ajax()) {

        //     $galleries = Gallery::select(['id', 'name', 'category', 'price', 'description', 'status', 'productPhotoPath'])->get();
        //     return DataTables::of($galleries)
        //         ->addColumn('action', function ($product) {
        //             return view('galleries.action', compact('product'));
        //         })
        //         ->addColumn('status_label', function ($product) {
        //             return $product->status == 1 ? 'Aktif' : 'Tidak Aktif';
        //         })
        //         ->toJson();
        // }

        // return view('galleries.index');
    }

    public function getProducts(Request $request)
    {
        $search = $request->input('q');
        $products = Product::where('name', 'LIKE', '%' . $search . '%')->get();
        return response()->json([
            'data' => $products
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $request->validate([
            'mechanic_recommendation' => 'nullable|string|max:255',
            'repair_type' => 'required|string',
            'galleryPhotoPath' => 'required|image|max:4096',
            'product' => 'nullable|array|exists:products,id',
        ]);

        if ($request->hasFile('galleryPhotoPath')) {
            $image = $request->file('galleryPhotoPath');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $galleryPhotoPath = $request->file('galleryPhotoPath')->storeAs('public/img/photoGallery', $imageName);
            $imageUrl = url('') . Storage::url($galleryPhotoPath);
            $data['galleryPhotoPath'] = $imageUrl;
            $gallery = Gallery::create($data);

            // Attach products to gallery
            if ($request->filled('product')) {
                $gallery->products()->attach($request->input('product'));
            }
        }

        return redirect()->route('galleries.index')
            ->with('success', 'Gallery created successfully.');
    }


    public function update(Request $request, Gallery $gallery)
    {
        $data = $request->all();
        $request->validate([
            'mechanic_recommendation' => 'nullable|string|max:255',
            'repair_type' => 'required|string',
            'galleryPhotoPath' => 'nullable|image|max:4096',
            'product' => 'nullable|array|exists:products,id',
        ]);

        if ($request->hasFile('galleryPhotoPath')) {
            $image = $request->file('galleryPhotoPath');
            $imageName = time() . '_' . $image->getClientOriginalName();

            $galleryPhotoPath = $request->file('galleryPhotoPath')->storeAs('public/img/photoGallery', $imageName);
            $imageUrl = url('') . Storage::url($galleryPhotoPath);

            /**
             * $path: pisahkan http://127.0.0.1:8000 menjadi /img/photoGallery/{file}
             * 
             * $relativePath : buat link /var/www/myapp/public/img/photoGallery/{file}
             */

            if ($gallery->galleryPhotoPath) {
                $path = parse_url($gallery->galleryPhotoPath, PHP_URL_PATH);
                $fileName = basename($path);
                $relativePath = 'public/img/photoGallery/' . $fileName;

                if (Storage::exists($relativePath)) {
                    Storage::delete($relativePath);
                }
            }

            $data['galleryPhotoPath'] = $imageUrl;
        }

        if ($request->filled('product')) {
            $gallery->products()->sync($request->input('product'));
        }
        $gallery->update($data);

        return redirect()->route('galleries.index')
            ->with('success', 'Gallery updated successfully.');
    }

    public function destroy(Gallery $gallery)
    {
        if ($gallery->galleryPhotoPath) {
            $path = parse_url($gallery->galleryPhotoPath, PHP_URL_PATH);
            $fileName = basename($path);
            $relativePath = 'public/img/photoGallery/' . $fileName;

            if (Storage::exists($relativePath)) {
                Storage::delete($relativePath);
            }
        }

        $gallery->delete();
        return redirect()->route('products.index')
            ->with('success', 'Gallery deleted successfully.');
    }
}
