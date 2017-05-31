<?php

namespace App\Http\Controllers;

use App\Events\WebsiteUpdated;
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
            $website = Website::query()->where('id', $request->get('id'))->first();
            $website->update($request->all());
            return response()->json([]);
        } else {
            $website = Website::create($request->all());
            return redirect()->action('WebsiteController@show', $website->id);
        }
    }

    public function show(Request $request, $websiteId) {
        $website = Website::query()->where('id', $websiteId)->first();
        if ($request->ajax()) {
            return response()->json($website);
        } else {
            return view('website.show', compact('website'));
        }
    }
}
