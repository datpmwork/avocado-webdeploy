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
        if ($request->ajax()) {
            Website::query()->where('id', $request->get('id'))->update($request->all());
            return response()->json([]);
        } else {
            Website::create($request->all());
            return redirect()->action('WebsiteController@index');
        }
    }

    public function show(Request $request, $websiteId) {
        $website = Website::query()->where('id', $websiteId)->first();
        return view('website.show', compact('website'));
    }
}
