<?php

namespace App\Http\Controllers;

use App\Model\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index($start_date = null, $end_date = null)
    {
        if(!isset($start_date)) {
            return Event::whereDate('date', Carbon::today()->toDateString())
                        ->with('customer')
                        ->with('team')
                        ->get();
        }
        else if(!isset($end_date)) {
            return Event::whereDate('date', $start_date)
                        ->with('customer')
                        ->with('team')
                        ->get();
        }
        else
            return Event::whereBetween('date', [$start_date, $end_date])
                    ->with('customer')
                    ->with('team')
                    ->orderBy('date', 'asc')
                    ->get();
    }
}
