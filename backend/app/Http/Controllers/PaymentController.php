<?php

namespace App\Http\Controllers;

use App\Model\Sale;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index($id)
    {
        return Sale::find($id)->payments;
    }
}
