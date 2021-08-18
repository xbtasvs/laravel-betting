<?php

namespace App\Http\Controllers;

use App\Models\Slip;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class BetsController extends Controller
{
    public function getBets(Request $request)
    {
        switch ($request->params['param']) {
          case 'active':
            $slips = Slip::where('user_id', Auth::id())->where('slip_status', 3)->with('bets')->get();

            return response()->json($slips);
            break;
          case 'history':
            $slips = Slip::where('user_id', Auth::id())->where('slip_status', '!=', 3)->with('bets')->get();

            return response()->json($slips);
            break;
          default:
            return response('OK', 200);
            break;
        }
    }
}
