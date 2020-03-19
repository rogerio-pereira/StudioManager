<?php

namespace App\Http\Controllers;

use App\Model\Sale;
use App\Model\Payment;
use Illuminate\Http\Request;
use App\Http\Requests\PayRequest;

class PaymentController extends Controller
{
    public function index($id)
    {
        return Sale::find($id)->payments;
    }

    public function pay(PayRequest $request, $id)
    {
        $data = $request->all();

        $payement = Payment::find($id);
        $payement->update($data);

        return $payement;
    }
}
