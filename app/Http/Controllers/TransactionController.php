<?php

namespace App\Http\Controllers;

use App\Http\Resources\TransactionResource;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'amount' => ['required', 'numeric'],
            'description' => ['required', 'string', 'max:255'],
        ]);
    }

    public function index(Request $request)
    {
        $id = $request->user()->id;
        $total = round(Transaction::where('user_id', $id)
            ->sum("amount"), 2);
        $records =  TransactionResource::collection(Transaction::where('user_id', $id)
            ->orderBy("created_at", "desc")
            ->get());
        return  response()->json(["total" => $total, "records" => $records]);
    }

    public function create(Request $request)
    {
        $this->validator($request->toArray());
        Transaction::create([
            'user_id' => $request->user()->id,
            'amount' => $request['amount'],
            'description' => $request['description'] ?? collect(['👽', '🐷', '🐶', '👾', '🦊', '🤠', '🍕', '👻', '🎉', '🐹', '🐼', '🐒', '😸'])->random(),
        ]);
        return response()->json(["message" => "Transacción registrada."]);
    }

    public function destroy(Request $request)
    {
        $id = $request->user()->id;
        Transaction::where('user_id', $id)->where('id', $request["id"])->delete();
        return response()->json(["message" => "Transacción eliminada."]);
    }
}
