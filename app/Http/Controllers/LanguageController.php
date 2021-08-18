<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function getLanguage()
    {
        $languages = Config::get('languages');
        $current = '';

        if (Auth::user()) {
            foreach ($languages as $key => $value) {
                if ($key === (int) Auth::user()->lang_pref) {
                    $current = $value;
                }
            }
        } else {
            if (Session::get('applocale')) {
                $current = Session::get('applocale');
            } else {
                $current = App::currentLocale();
            }
        }

        return response()->json(['current' => $current, 'languages' => $languages]);
    }

    public function setLanguage(Request $request)
    {
        $lang = $request->params['language'];
        $languages = Config::get('languages');
        $numerize = 0;

        foreach ($languages as $key => $value) {
            if ($value === $lang) {
                $numerize = $key;
            }
        }

        if (Auth::user()) {
          Auth::user()->update([
              'lang_pref' => $numerize
          ]);
        }

        Session::put('applocale', $lang);
        App::setLocale($lang);
    }
}
