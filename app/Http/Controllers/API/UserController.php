<?php

namespace App\Http\Controllers\API;

use App\Actions\Fortify\PasswordValidationRules;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    use PasswordValidationRules;

    public function login(Request $request)
    {
        try {
            // validate input
            $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);

            // credentials check
            $credentials = request(['email', 'password']);
            if (!Auth::attempt($credentials)) {
                return ResponseFormatter::error([
                    'message' => 'Unauthorized'
                ], 'Authentication Failed', 500);
            }

            $user = User::where('email', $request->email)->first();
            if (!Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Invalid Credentials');
            }

            $tokenResult = $user->createToken('authToken')->plainTextToken;

            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Authenticated');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error,
            ], 'Authentication Failed', 500);
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            // 'password' => $this->passwordRules(),
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 401);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            // 'password' => bcrypt($request->password),
            'roles' => 'customer'
        ]);

        $user = User::where('email', $request->email)->first();

        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user
        ], 200);
    }

    public function logout(Request $request)
    {
        $token = $request->user()->currentAccessToken()->delete();
        return ResponseFormatter::success($token, 'Token Revoked');
    }

    public function showProfile(Request $request)
    {
        return ResponseFormatter::success($request->user(), 'Show Data Profile Success');
    }

    public function updatePhoto(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'profilePhotoPath' => 'required|image|max:4096',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(
                ['error' => $validator->errors()],
                'Update photo fails',
                401
            );
        }

        $user = User::find($id);
        if (!$user) {
            return ResponseFormatter::error(
                ['error' => 'User Not Found'],
                'User Not Found',
                404
            );
        }

        $folder = $user->id;
        $image = $request->file('profilePhotoPath');
        $imageName = time() . '_' . $image->getClientOriginalName();
        $imagePath = public_path('img/photoProfile/' . $folder);

        $imageUrl = url('img/photoProfile/' . $folder . '/' . $imageName);

        /**
         * $path: pisahkan http://127.0.0.1:8000 menjadi /img/photoProfile/{folder}/{file}
         * 
         * $relativePath : buat link /var/www/myapp/public/img/photoProfile/{folder}/{file}
         */

        $path = parse_url($user->profilePhotoPath, PHP_URL_PATH);
        $relativePath = public_path($path);

        if (file_exists($relativePath)) {
            unlink($relativePath);
        }
        $image->move($imagePath, $imageName);

        $user->profilePhotoPath = $imageUrl;
        $user->save();

        return ResponseFormatter::success([
            'profilePhotoPath' => $imageUrl
        ], 'File success upload');
    }


    public function updateProfile(Request $request, $id)
    {
        $data = $request->all();

        // $user = Auth::user();
        $user = auth()->user()->id;
        if (!$user) {
            return ResponseFormatter::error(['message' => 'Unauthorized'], 'Authentication Failed', 500);
        }
        $user->update($data);

        return ResponseFormatter::success($user, 'Profile Updated');
    }
}
