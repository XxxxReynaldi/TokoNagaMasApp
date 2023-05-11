<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Mechanic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MechanicController extends Controller
{
    public function filter(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 6);
        $name = $request->input('name');
        $status = $request->input('status');

        if ($id) {
            $mechanic = Mechanic::find($id);

            if ($mechanic)
                return ResponseFormatter::success($mechanic, 'Data mechanic retrieved successfully');
            else
                return ResponseFormatter::error(null, 'Data mechanic not found', 404);
        }

        $mechanic = Mechanic::query();

        if ($name)
            $mechanic->where('name', 'like', '%' . $name . '%');

        if ($status)
            $mechanic->where('status', $status);


        return ResponseFormatter::success(
            $mechanic->paginate($limit),
            'Data list mechanic retrieved successfully'
        );
    }

    public function index()
    {

        // Jika header authorization tidak kosong, ambil user yang login
        // $user = Auth::guard('sanctum')->user();

        $mechanics = Mechanic::all();

        return ResponseFormatter::success(['mechanics' => $mechanics], 'Mechanics retrieved successfully');

        // return response()->json([
        //     'status' => 'success',
        //     'message' => 'Data mechanics retrieved successfully',
        //     'data' => $mechanics,
        // ]);
    }

    public function show(Request $request, $id)
    {
        $mechanic = Mechanic::find($id);
        if (!$mechanic) {
            return ResponseFormatter::error(['error' => 'Mechanic Not Found'], 'Mechanic Not Found', 404);
        }
        return ResponseFormatter::success($request->mechanic(), 'Show Data Mechanic Success');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'status' => 'required',
            'mechanicPhotoPath' => 'required|image|max:4096',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(['error' => $validator->errors()], 'Add mechanic fails', 400);
        }

        $image = $request->file('mechanicPhotoPath');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $mechanicPhotoPath = $request->file('mechanicPhotoPath')->storeAs('public/img/photoMechanic/', $imageName);
        $imageUrl = url('') . Storage::url($mechanicPhotoPath);

        $data['mechanicPhotoPath'] = $imageUrl;
        $mechanic = Mechanic::create($data);

        return ResponseFormatter::success(['mechanic' => $mechanic], 'Mechanic inserted successfully');
    }


    public function update(Request $request, $id)
    {
        $data = $request->all();
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'status' => 'required',
            'mechanicPhotoPath' => 'nullable|image|max:4096',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(['error' => $validator->errors()], 'Add mechanic fails', 400);
        }

        $mechanic = Mechanic::find($id);
        if (!$mechanic) {
            return ResponseFormatter::error(['error' => 'Mechanic Not Found'], 'Mechanic Not Found', 404);
        }

        if ($request->hasFile('mechanicPhotoPath')) {
            $image = $request->file('mechanicPhotoPath');
            $imageName = time() . '_' . $image->getClientOriginalName();

            $mechanicPhotoPath = $request->file('mechanicPhotoPath')->storeAs('public/img/photoMechanic/', $imageName);
            $imageUrl = url('') . Storage::url($mechanicPhotoPath);

            /**
             * $path: pisahkan http://127.0.0.1:8000 menjadi /img/photoMechanic/{file}
             * 
             * $relativePath : buat link /var/www/myapp/public/img/photoMechanic/{file}
             */

            if ($mechanic->mechanicPhotoPath) {
                $path = parse_url($mechanic->mechanicPhotoPath, PHP_URL_PATH);
                $fileName = basename($path);
                $relativePath = 'public/img/photoMechanic/' . $fileName;

                if (Storage::exists($relativePath)) {
                    Storage::delete($relativePath);
                }
            }

            $data['mechanicPhotoPath'] = $imageUrl;
        }

        $mechanic->update($data);

        return ResponseFormatter::success(['mechanic' => $mechanic], 'Mechanics updated successfully');
    }

    public function destroy($id)
    {
        $mechanic = Mechanic::find($id);
        if (!$mechanic) {
            return ResponseFormatter::error(['error' => 'Mechanic Not Found'], 'Mechanic Not Found', 404);
        }

        if ($mechanic->mechanicPhotoPath) {
            $path = parse_url($mechanic->mechanicPhotoPath, PHP_URL_PATH);
            $fileName = basename($path);
            $relativePath = 'public/img/photoMechanic/' . $fileName;

            if (Storage::exists($relativePath)) {
                Storage::delete($relativePath);
            }
        }

        $mechanic->delete();
        return ResponseFormatter::success(['mechanic' => $mechanic], 'Mechanics deleted successfully');
    }

    public function massDestroy(Request $request)
    {
        // memeriksa apakah pengguna memiliki akses untuk melakukan aksi ini
        $user = Auth::user();
        if (!$user) {
            return ResponseFormatter::error(['message' => 'Unauthorized'], 'Authentication Failed', 401);
        }

        $ids = $request->input('ids');
        $mechanics = Mechanic::whereIn('id', $ids)->get();
        foreach ($mechanics as $mechanic) {
            if ($mechanic->mechanicPhotoPath) {
                $imagePath = 'public/img/photoMechanic/' . basename($mechanic->mechanicPhotoPath);

                if (Storage::exists($imagePath)) {
                    Storage::delete($imagePath);
                }
            }
        }
        Mechanic::whereIn('id', $ids)->delete();
        return response()->json(['message' => 'Mechanics deleted successfully.']);
    }
}
