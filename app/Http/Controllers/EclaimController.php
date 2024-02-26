<?php

namespace App\Http\Controllers;

use App\Models\Eclaim;
use Illuminate\Http\Request;

class EclaimController extends Controller
{
    public function index()
    {
        if (\Auth::user()->can('Manage Eclaim')) {
            $eclaims = Eclaim::where('created_by', '=', \Auth::user()->creatorId())->get();
            return view('eclaimTypes.index', compact('eclaims'));
        } else {
            // return redirect()->back()->with('error', __('Permission denied.'));
            die('no edit access');
        }
    }
}
