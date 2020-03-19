<?php

namespace App\Http\Controllers;

use App\Model\Sale;
use App\Model\Payment;
use Illuminate\Http\Request;
use App\Http\Requests\PayRequest;
use Carbon\Carbon;

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

    public function paymentsToday()
    {
        return Payment::whereDate('due_at', Carbon::today()->toDateString())
                ->with('sale.customer')
                ->get(); 
    }
}
