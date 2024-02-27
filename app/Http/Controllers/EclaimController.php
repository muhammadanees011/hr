<?php

namespace App\Http\Controllers;

use App\Models\Eclaim;
use App\Models\EclaimType;
use Illuminate\Http\Request;

class EclaimController extends Controller
{
    public function index()
    {
        if (\Auth::user()->can('Manage Eclaim')) {
            $eclaims = Eclaim::with('claimType')->where('created_by', '=', \Auth::user()->creatorId())->get();
            return view('eclaim.index', compact('eclaims'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (\Auth::user()->can('Create Eclaim')) {
            $eClaimTypes = EclaimType::get()->pluck('title', 'id');
            return view('eclaim.create', compact('eClaimTypes'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if (\Auth::user()->can('Create Eclaim')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'type_id' => 'required',
                    'amount' => 'required',
                    'receipt' => 'required',
                    'description' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            if (!empty($request->receipt)) {

                $filenameWithExt = $request->file('receipt')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('receipt')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $dir = 'uploads/eclaimreceipts/';
                $image_path = $dir . $fileNameToStore;
                if (\File::exists($image_path)) {
                    \File::delete($image_path);
                }
                $url = '';
                $path = \Utility::upload_file($request, 'receipt', $fileNameToStore, $dir, []);
                if ($path['flag'] == 1) {
                    $url = $path['url'];
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }

            $eClaimType               = new Eclaim();
            $eClaimType->type_id      = $request->type_id;
            $eClaimType->amount       = $request->amount;
            $eClaimType->description  = $request->description;
            $eClaimType->receipt      = !empty($request->receipt) ? $fileNameToStore : '';
            $eClaimType->created_by   = \Auth::user()->creatorId();
            $eClaimType->save();

            return redirect()->route('eclaim.index')->with('success', __('Eclaim  successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show(EclaimType $eclaim)
    {
        return redirect()->route('eclaim_type.index');
    }

    public function edit(Eclaim $eclaim)
    {
        if (\Auth::user()->can('Edit Eclaim')) {
            if ($eclaimType->created_by == \Auth::user()->creatorId()) {
                return view('eclaim.edit', compact('eclaimType'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, Eclaim $eclaim)
    {
        if (\Auth::user()->can('Edit Eclaim')) {
            if ($eclaimType->created_by == \Auth::user()->creatorId()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'title' => 'required',
                        'description' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $eclaimType->title = $request->title;
                $eclaimType->description = $request->description;
                $eclaimType->save();

                return redirect()->route('eclaim_type.index')->with('success', __('EclaimType successfully updated.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(Eclaim $eclaim)
    {
        if (\Auth::user()->can('Delete Eclaim')) {
            if ($eclaimType->created_by == \Auth::user()->creatorId()) {
                    $eclaim->delete();
                return redirect()->route('eclaim_type.index')->with('success', __('EclaimType successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
