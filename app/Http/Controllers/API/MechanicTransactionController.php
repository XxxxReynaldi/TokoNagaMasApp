<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Mechanic;
use App\Models\MechanicTransaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class MechanicTransactionController extends Controller
{
    public function checkout(Request $request)
    {
        $data = $request->all();
        $user_id    = $request->input('user_id');
        $mechanic_id = $request->input('mechanic_id');

        $validator = Validator::make($data, [
            'user_id' => 'required|int|exists:users,id',
            'mechanic_id' => 'required|int|exists:mechanics,id',
            'bank_account_name' => 'required|regex:/^[a-zA-Z\s]*$/',
            'purchaseReceiptPath' => 'required|image|mimes:jpeg,png,jpg|max:4096',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(['error' => $validator->errors()], 'Check out failed', 400);
        }

        $user = User::find($user_id);
        if (!$user) {
            return ResponseFormatter::error(['error' => 'User Not Found'], 'Check out failed', 404);
        }

        $mechanic = Mechanic::where('id', $mechanic_id);
        if ($mechanic->get()->isEmpty()) {
            return ResponseFormatter::error(['error' => 'Mechanic Not Found'], 'Check out failed', 404);
        }

        if ($mechanic->first()->status == 0) {
            return ResponseFormatter::error(['error' => 'mechanic not available'], 'Check out failed', 404);
        }

        $folder = $user_id;
        $image = $request->file('purchaseReceiptPath');
        $imageName = time() . '_' . $image->getClientOriginalName();

        $purchaseReceiptPath = $request->file('purchaseReceiptPath')->storeAs('public/img/purchaseReceipt/' . $folder . '/mechanic', $imageName);
        $data['status'] = 'pending';
        $data['total_price'] = $mechanic->get()[0]->price;
        $data['purchaseReceiptPath'] = url('') . Storage::url($purchaseReceiptPath);

        $transaction = MechanicTransaction::create($data);

        return ResponseFormatter::success(['transaction' => $transaction], 'Check out mechanic transaction successfully');
    }

    public function destroy($id)
    {
        $transaction = MechanicTransaction::find($id);

        if (!$transaction) {
            return ResponseFormatter::error(['error' => 'Transaction Not Found'], 'Transaction Not Found', 404);
        }

        $imagePath = $transaction->purchaseReceiptPath;
        $folder = $transaction->user_id;
        if ($imagePath) {
            $path = parse_url($transaction->purchaseReceiptPath, PHP_URL_PATH);
            $fileName = basename($path);
            $relativePath = 'public/img/purchaseReceipt/' . $folder . '/mechanic/' . $fileName;

            if (Storage::exists($relativePath)) {
                Storage::delete($relativePath);
            }
        }

        $transaction->delete();
        return ResponseFormatter::success(['transaction' => $transaction], 'Transaction deleted successfully');
    }

    public function getMechanicTransaction(Request $request)
    {
        $id = $request->input('id');
        $limit = $request->input('limit', 6);

        $user_id = $request->input('user_id');
        $status = $request->input('status');

        /**
         * $id = transaction_id
         */
        if ($id) {
            $mechanicTransaction = MechanicTransaction::with([
                'mechanic' => function ($query) {
                    $query->select('id', 'name', 'category', 'description', 'price', 'mechanicPhotoPath');
                },
                'user' => function ($query) {
                    $query->select('id', 'name', 'email', 'phone_number', 'address', 'profilePhotoPath');
                }
            ])->find($id);

            if ($mechanicTransaction)
                return ResponseFormatter::success($mechanicTransaction, 'Data mechanic transaction retrieved successfully');
            else
                return ResponseFormatter::error(null, 'Data mechanic transaction not found', 404);
        }

        $mechanicTransaction = MechanicTransaction::with([
            'mechanic' => function ($query) {
                $query->select('id', 'name', 'category', 'description', 'price', 'mechanicPhotoPath');
            },
            'user' => function ($query) {
                $query->select('id', 'name', 'email', 'phone_number', 'address', 'profilePhotoPath');
            }
        ]);

        if ($user_id)
            $mechanicTransaction->where('user_id', $user_id);

        if ($status)
            $mechanicTransaction->where('status', $status);

        $payload = JWTAuth::parseToken()->getPayload();
        $role_id = $payload->get('user')['role_id'];
        $userIdToken = $payload->get('user')['id'];

        /***
         * Jika role_id = 2 dan
         * pastikan terdapat param user_id 
         */
        if ($role_id == 2 && !$user_id) {
            return ResponseFormatter::error(null, 'Data mechanic transaction not found', 404);
        }

        // jika user_id bukan yang bersangkutan
        if ($role_id == 2 && $user_id != $userIdToken) {
            return ResponseFormatter::error(null, 'Data mechanic transaction not found', 404);
        }


        return ResponseFormatter::success(
            $mechanicTransaction->paginate($limit),
            'Data list mechanic transaction retrieved successfully'
        );
    }
}
