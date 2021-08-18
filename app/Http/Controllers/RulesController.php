<?php

namespace App\Http\Controllers;

use App\Models\Rule;
use App\Models\Category;
use Illuminate\Http\Request;

class RulesController extends Controller
{
    public function getRules()
    {
        $rules = Category::orderBy('category_order', 'ASC')->with('rules')->get();
        return response()->json($rules);
    }

    public function insertCategory()
    {
        // return view('app', ['passwordToken' => null]);
    }

    public function updateCategory()
    {
        // return view('app', ['passwordToken' => null]);
    }

    public function deleteCategory()
    {
        // return view('app', ['passwordToken' => null]);
    }

    public function insertRule()
    {
        // return view('app', ['passwordToken' => null]);
    }

    public function updateRule()
    {
        // return view('app', ['passwordToken' => null]);
    }

    public function deleteRule()
    {
        // return view('app', ['passwordToken' => null]);
    }
}
