<?php

namespace App\Http\Controllers;

use App\Website;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    //
    public function index() {
        $websites = Website::all();
        return view('website.index', compact('websites'));
    }

    public function create() {
        $website = new Website();
        return view('website.create', compact('website'));
    }

    public function store(Request $request) {
        dd($request->all());
    }
}
