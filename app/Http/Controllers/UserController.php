<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function avatarUpdate(Request $request)
    {
        $id = Auth::id();

        $path = public_path('img/faces/');

        if(!File::isDirectory($path)){
            File::makeDirectory($path, 0777, true, true);
        }

        $file = $request->file('avatar');
        $destinationPath = '/img/faces/';
        $extension = explode('.', $file->getClientOriginalName());
        $filename = date("YmdHis") . $id . '.' . end($extension);
        $file->move(public_path() . $destinationPath, $filename);

        User::where('id', $id)->update([
            'avatar' => 'img/faces/' . $filename
        ]);

        return response('Success', 200);
    }

    public function profileUpdate(Request $request)
    {
        $id = Auth::id();

        User::where('id', $id)->update([
            'first_name' => $request->params['first_name'],
            'middle_name' => $request->params['middle_name'],
            'last_name' => $request->params['last_name'],
            'email' => $request->params['email'],
            'phone' => $request->params['phone'],
            'birthday' => date('Y-m-d H:i:s', strtotime($request->params['birthday'])),
            'country' => $request->params['country'],
            'state' => $request->params['province'],
            'city' => $request->params['city'],
            'address' => $request->params['address'],
            'zip_code' => $request->params['zip_code'],
            'password' => Hash::make($request->params['password']),
        ]);

        return response('Success', 200);
    }

    public function purchase(Request $request)
    {
        $user = Auth::user();

        try {
            $customer = $user->createOrGetStripeCustomer($request->params['customer']);

            $payment = $user->charge($request->params['amount'], $request->params['payment_method_id'], [
                'customer' => $customer->id
            ]);

            $user->update([
                'amount' => $user->amount + $request->params['amount'] / 100
            ]);

            $payment = $payment->asStripePaymentIntent();

            return response('OK', 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 201);
        }
    }

    public function oddsPreference(Request $request)
    {
        $user = Auth::user();
        $pref = $request->params['pref'];

        if ((bool) $pref && (int) $user->odds_pref === 1) {
          return response('Already chosen', 200);
        } else {
          try {
            if ((bool) $pref) {
              $user->update(['odds_pref' => 1]);
            } else {
              $user->update(['odds_pref' => 2]);
            }
          } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
          }
        }

        return response('OK', 200);
    }

    public function getPreference(Request $request)
    {
        $odds_pref = Auth::user()->odds_pref;
        return response()->json($odds_pref);
    }
}
