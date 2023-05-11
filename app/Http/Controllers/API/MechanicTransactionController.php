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
            'total_price' => 'required|int|min:0',
        ]);

        if ($validator->fails()) {
            return ResponseFormatter::error(['error' => $validator->errors()], 'Add cart product fails', 400);
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
}
