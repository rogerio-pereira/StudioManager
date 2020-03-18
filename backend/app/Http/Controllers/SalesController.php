<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Model\Sale;
use App\Model\Payment;
use Illuminate\Http\Request;
use App\Http\Requests\SaleRequest;
use App\Model\Useful\DateConversion;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Sale::with('customer')->with('products')->with('payments')->get();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(SaleRequest $request)
    {
        $data = $request->all();
        
        $sale = Sale::create($data);
        $sale->products()->sync($data['products']);

        $installmentValue = ($sale->value - $sale->discount) / $sale->installments;
        $date = Carbon::createFromFormat('Y-m-d', $data['start_date'])->format('Y-m-d');

        for($i=0; $i<$data['installments']; $i++) {
            Payment::create([
                'sale_id' => $sale->id,
                'due_at' => $date,
                'amount' => $installmentValue,
                'payed' => false,
            ]);

            $date = DateConversion::newDateByPeriod($date, $data['period'])->toDateString();
        }

        $sale = $sale->with('customer')->with('products')->with('payments')->get()->first();

        return response()->json($sale, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Sale::with('customer')->with('products')->with('payments')->find($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(SaleRequest $request, $id)
    {
        $data = $request->all();
        
        $sale = Sale::find($id);
        $sale->payments()->delete();
        $sale->update($data);
        $sale->products()->sync($data['products']);

        $installmentValue = ($sale->value - $sale->discount) / $sale->installments;
        $date = Carbon::createFromFormat('Y-m-d', $data['start_date'])->format('Y-m-d');

        for($i=0; $i<$data['installments']; $i++) {
            Payment::create([
                'sale_id' => $sale->id,
                'due_at' => $date,
                'amount' => $installmentValue,
                'payed' => false,
            ]);

            $date = DateConversion::newDateByPeriod($date, $data['period'])->toDateString();
        }

        return $sale->with('customer')->with('products')->with('payments')->get()->first();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Sale::find($id)->delete();

        return [];
    }
}
