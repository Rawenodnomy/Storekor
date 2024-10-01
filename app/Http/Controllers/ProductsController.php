<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductsController extends Controller
{
    function content() {

        $albums = DB::select('SELECT * FROM albums');

        return view("content", compact('allSciences', 'allCountries', 'allScientists', 'random'));
    }

}
