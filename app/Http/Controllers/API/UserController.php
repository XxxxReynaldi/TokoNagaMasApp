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
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

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
            try {
                if (!$tokenResult = JWTAuth::attempt($credentials)) {
                    return ResponseFormatter::error([
                        'message' => 'Invalid credentials'
                    ], 'Authentication Failed', 401);
                }
            } catch (JWTException $e) {
                return $credentials;
                return ResponseFormatter::error([
                    'success' => false,
                    'message' => 'Could not create token.',
                ], 500);
            }

            $user = User::where('email', $request->email)->first();
            if (!Hash::check($request->password, $user->password, [])) {
                throw new \Exception('Invalid Credentials');
            }

            // $tokenResult = $user->createToken('authToken')->plainTextToken;

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
            'role_id' => $request->role_id,
        ]);

        $user = User::where('email', $request->email)->first();
        $credentials = request(['email', 'password']);
        try {
            if (!$tokenResult = JWTAuth::attempt($credentials)) {
                return ResponseFormatter::error([
                    'message' => 'Invalid credentials'
                ], 'Authentication Failed', 401);
            }
        } catch (JWTException $e) {
            return $credentials;
            return ResponseFormatter::error([
                'success' => false,
                'message' => 'Could not create token.',
            ], 500);
        }

        // $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'access_token' => $tokenResult,
            'token_type' => 'Bearer',
            'message' => 'User created successfully',
            'user' => $user
        ], 200);
    }



    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => $this->guard()->factory()->getTTL() * 60
        ]);
    }

    /**
     * Get the authenticated User
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json($this->guard()->user());
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken($this->guard()->refresh());
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\Guard
     */
    public function guard()
    {
        return Auth::guard();
    }


    public function logout(Request $request)
    {
        // $token = $request->user()->currentAccessToken()->delete();
        // return ResponseFormatter::success($token, 'Token Revoked');
        $this->guard()->logout();
        return ResponseFormatter::success('token', 'Token Revoked');
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
            return ResponseFormatter::error(['error' => $validator->errors()], 'Update photo fails', 401);
        }

        $user = User::find($id);
        if (!$user) {
            return ResponseFormatter::error(['error' => 'User Not Found'], 'User Not Found', 404);
        }

        $folder = $user->id;
        $image = $request->file('profilePhotoPath');
        $imageName = time() . '_' . $image->getClientOriginalName();

        $profilePhotoPath = $request->file('profilePhotoPath')->storeAs('public/img/photoProfile/' . $folder, $imageName);
        $imageUrl = url('') . Storage::url($profilePhotoPath);


        if ($user->profilePhotoPath) {
            $path = parse_url($user->profilePhotoPath, PHP_URL_PATH);
            $fileName = basename($path);
            $relativePath = 'public/img/photoProfile/' . $folder . '/' . $fileName;

            if (Storage::exists($relativePath)) {
                Storage::delete($relativePath);
            }
        }

        $user->profilePhotoPath = $imageUrl;
        $user->save();

        return ResponseFormatter::success(['profilePhotoPath' => $imageUrl], 'File success upload');
    }


    public function updateProfile(Request $request, $id)
    {
        try {
            $data = $request->all();
            $rules = [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            ];

            $validatedData = $request->validate($rules);

            // $user = Auth::user();
            $user = User::find($id);
            if (!$user) {
                return ResponseFormatter::error(['message' => 'Unauthorized'], 'Authentication Failed', 500);
            }
            $user->update($data);

            return ResponseFormatter::success(['user' => $user], 'User data updated successfully');
        } catch (Exception $e) {
            $errors = $e->errors();
            return ResponseFormatter::error([
                'message' => 'Validation error',
                'errors' => $errors,
            ], 'Validation Failed', 422);
        }
    }
}
