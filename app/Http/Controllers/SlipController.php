<?php

namespace App\Http\Controllers;

use App\Models\Slip;
use App\Models\Bet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SlipController extends Controller
{
    public function calculate_string($mathString)
    {
        list($top, $bottom) = explode('/', $mathString);
        $float = $top / $bottom;
        return $float;
    }

    public function newSlip(Request $request)
    {
        $slip = Slip::create([
            'user_id' => Auth::id(),
            'bet_amount' => $request->params['amount'],
            'slip_status' => 3
        ]);

        foreach ($request->params['events'] as $sportEvent) {
            $odd_value = $this->calculate_string($sportEvent['betOdd']['odd']);

            Bet::create([
                'bet_odd_id' => $sportEvent['betOdd']['id'],
                'bet_odd_value' => number_format($odd_value, 2, '.', ''),
                'bet_market_id' => $sportEvent['sportEvent']['markets'][0]['id'],
                'bet_event_id' => $sportEvent['sportEvent']['eventID'],
                'bet_status' => 3,
                'slip_id' => $slip->id
            ]);
        }

        return response('OK', 200);
    }
}
