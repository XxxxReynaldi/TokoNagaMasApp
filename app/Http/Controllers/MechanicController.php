<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseFormatter;
use App\Models\Mechanic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\Paginator;
use DataTables;
use Illuminate\Support\Facades\Session;

class MechanicController extends Controller
{
    public function index(Request $request)
    {
        $mechanics = Mechanic::all();
        return view('pages.mechanics.index', compact('mechanics'));

        // if (request()->ajax()) {

        //     $mechanics = Mechanic::select(['id', 'name', 'category', 'price', 'description', 'status', 'mechanicPhotoPath'])->get();
        //     return DataTables::of($mechanics)
        //         ->addColumn('action', function ($mechanic) {
        //             return view('mechanics.action', compact('mechanic'));
        //         })
        //         ->addColumn('status_label', function ($mechanic) {
        //             return $mechanic->status == 1 ? 'Aktif' : 'Tidak Aktif';
        //         })
        //         ->toJson();
        // }

        // return view('mechanics.index');
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'mechanicPhotoPath' => 'required|image|max:4096',
        ]);

        // if ($validator->fails()) {
        //     return redirect()->route('mechanics.index')->with('error', $validator->errors());
        //     // return ResponseFormatter::error(['error' => $validator->errors()], 'Add mechanic fails', 400);
        // }

        if ($request->hasFile('mechanicPhotoPath')) {
            $image = $request->file('mechanicPhotoPath');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $mechanicPhotoPath = $request->file('mechanicPhotoPath')->storeAs('public/img/photoMechanic', $imageName);
            $imageUrl = url('') . Storage::url($mechanicPhotoPath);

            $data['mechanicPhotoPath'] = $imageUrl;
            Mechanic::create($data);
        }


        return redirect()->route('mechanics.index')
            ->with('success', 'Mechanic created successfully.');
    }

    public function update(Request $request, Mechanic $mechanic)
    {
        $data = $request->all();

        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'price' => 'required|integer|min:0',
            'mechanicPhotoPath' => 'nullable|image|max:4096',
        ]);

        if (!array_key_exists("status", $data)) {
            $data['status'] = "0";
        }

        if ($request->hasFile('mechanicPhotoPath')) {
            $image = $request->file('mechanicPhotoPath');
            $imageName = time() . '_' . $image->getClientOriginalName();

            $mechanicPhotoPath = $request->file('mechanicPhotoPath')->storeAs('public/img/photoMechanic', $imageName);
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

        return redirect()->route('mechanics.index')
            ->with('success', 'Mechanic updated successfully.');
    }

    public function destroy(Mechanic $mechanic)
    {
        // $mechanic = Mechanic::find($id);
        // if (!$mechanic) {
        //     return redirect()->route('mechanics.index')
        //         ->with('error', 'Mechanic Not Found');
        // }

        if ($mechanic->mechanicPhotoPath) {
            $path = parse_url($mechanic->mechanicPhotoPath, PHP_URL_PATH);
            $fileName = basename($path);
            $relativePath = 'public/img/photoMechanic/' . $fileName;

            if (Storage::exists($relativePath)) {
                Storage::delete($relativePath);
            }
        }

        $mechanic->delete();
        return redirect()->route('mechanics.index')
            ->with('success', 'Mechanic deleted successfully.');
    }
}
