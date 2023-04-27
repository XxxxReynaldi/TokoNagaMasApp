<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class GalleryController extends Controller
{

    public function filter(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 6);
        $repair_type = $request->input('repair_type');
        $mechanic_recommendation = $request->input('mechanic_recommendation');

        $product_id = $request->input('product_id');

        if ($id) {
            // $gallery = Gallery::find($id);
            $gallery = Gallery::with([
                'products' => function ($query) {
                    $query->select('name', 'price', 'stock', 'description', 'productPhotoPath');
                }
            ])->find($id);

            if ($gallery)
                return ResponseFormatter::success($gallery, 'Data gallery retrieved successfully');
            else
                return ResponseFormatter::error(null, 'Data gallery not found', 404);
        }

        // $gallery = Gallery::query();
        $gallery = Gallery::with([
            'products' => function ($query) {
                $query->select('name', 'price', 'stock', 'description', 'productPhotoPath');
            }
        ]);

        if ($repair_type)
            $gallery->where('repair_type', 'like', '%' . $repair_type . '%');

        if ($mechanic_recommendation)
            $gallery->where('mechanic_recommendation', 'like', '%' . $mechanic_recommendation . '%');

        if ($product_id)
            $gallery->whereHas('products', function ($query) use ($product_id) {
                $query->where('product_id', $product_id);
            });



        return ResponseFormatter::success(
            $gallery->paginate($limit),
            'Data list gallery retrieved successfully'
        );
    }

    public function index()
    {

        // Jika header authorization tidak kosong, ambil user yang login
        // $user = Auth::guard('sanctum')->user();

        $galleries = Gallery::with([
            'products' => function ($query) {
                $query->select('name', 'price', 'stock', 'description', 'productPhotoPath');
            }
        ])->get();

        return ResponseFormatter::success(['galleries' => $galleries], 'Galleries retrieved successfully');

        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'Data galleries retrieved successfully',
        //     'data' => $galleries,
        // ]);
    }

    public function show(Request $request, $id)
    {
        $gallery = Gallery::with([
            'products' => function ($query) {
                $query->select('name', 'price', 'stock', 'description', 'productPhotoPath');
            }
        ])->find($id);
        if (!$gallery) {
            return ResponseFormatter::error(['error' => 'Gallery Not Found'], 'Gallery Not Found', 404);
        }
        return ResponseFormatter::success($request->gallery(), 'Show Data Gallery Success');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'mechanic_recommendation' => 'nullable|string|max:255',
            'repair_type' => 'required|string',
            'galleryPhotoPath' => 'required|image|max:4096',
            'product' => 'nullable|array|exists:products,id',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(['error' => $validator->errors()], 'Add gallery fails', 400);
        }

        $image = $request->file('galleryPhotoPath');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $imagePath = public_path('img/photoGallery');
        $imageUrl = url('img/photoGallery/' . $imageName);

        $image->move($imagePath, $imageName);


        $data['galleryPhotoPath'] = $imageUrl;
        $gallery = Gallery::create($data);

        // Attach products to gallery
        if ($request->filled('product')) {
            $gallery->products()->attach($request->input('product'));
        }

        return ResponseFormatter::success(['gallery' => $gallery], 'Gallery inserted successfully');
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'mechanic_recommendation' => 'nullable|string|max:255',
            'repair_type' => 'required|string',
            'galleryPhotoPath' => 'nullable|image|max:4096',
            'product' => 'nullable|array|exists:products,id',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(['error' => $validator->errors()], 'Add gallery fails', 400);
        }

        $gallery = Gallery::find($id);
        if (!$gallery) {
            return ResponseFormatter::error(['error' => 'Gallery Not Found'], 'Gallery Not Found', 404);
        }

        if ($request->hasFile('galleryPhotoPath')) {
            $image = $request->file('galleryPhotoPath');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $imagePath = public_path('img/photoGallery');
            $imageUrl = url('img/photoGallery/' . $imageName);

            /**
             * $path: pisahkan http://127.0.0.1:8000 menjadi /img/photoGallery/{file}
             * 
             * $relativePath : buat link /var/www/myapp/public/img/photoGallery/{file}
             */

            $path = parse_url($gallery->galleryPhotoPath, PHP_URL_PATH);
            $relativePath = public_path($path);

            if (file_exists($relativePath)) {
                unlink($relativePath);
            }
            $image->move($imagePath, $imageName);

            $data['galleryPhotoPath'] = $imageUrl;
        }

        if ($request->filled('product')) {
            $gallery->products()->sync($request->input('product'));
        }
        $gallery->update($data);

        return ResponseFormatter::success(['gallery' => $gallery], 'Gallery updated successfully');
    }

    public function destroy($id)
    {
        $gallery = Gallery::find($id);
        if (!$gallery) {
            return ResponseFormatter::error(['error' => 'Gallery Not Found'], 'Gallery Not Found', 404);
        }

        if ($gallery->galleryPhotoPath) {
            $imagePath = public_path('img/photoGallery') . '/' . basename($gallery->galleryPhotoPath);
            if (File::exists($imagePath)) {
                File::delete($imagePath);
            }
        }

        $gallery->delete();
        return ResponseFormatter::success(['gallery' => $gallery], 'Gallery deleted successfully');
    }
}
