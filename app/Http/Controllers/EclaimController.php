<?php

namespace App\Http\Controllers;

use App\Models\Eclaim;
use App\Models\EclaimType;
use Illuminate\Http\Request;
use Auth;
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
                'receipt' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Assuming you're only allowing image files
                'description' => 'required',
            ]
        );
        
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('receipt')) {
            $file = $request->file('receipt');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = 'public/uploads/eclaimreceipts/' . $fileName;
            $file->storeAs('public', $filePath);
        }
        

        $history = ['time' => now(), 'comment' => 'New Eclaim Requested Generated', 'username' => Auth::user()->name];
        $eClaimType = new Eclaim();
        $eClaimType->type_id = $request->type_id;
        $eClaimType->amount = $request->amount;
        $eClaimType->description = $request->description;
        $eClaimType->receipt = $fileName ?? ''; // Assigning the filename if it exists, otherwise empty string
        $eClaimType->created_by = \Auth::user()->creatorId();
        $eClaimType->history = json_encode($history);
        $eClaimType->save();

        return redirect()->route('eclaim.index')->with('success', __('Eclaim successfully created.'));
    } else {
        return redirect()->back()->with('error', __('Permission denied.'));
    }
}


    public function show(EclaimType $eclaim)
    {
        return redirect()->route('eclaim_type.index');
    }

    public function edit($id)
    {
        if (\Auth::user()->can('Edit Eclaim')) {
            // if ($eclaim->created_by == \Auth::user()->creatorId()) {
                $eclaim = Eclaim::where('id', $id)->first();
                $eClaimTypes = EclaimType::get()->pluck('title', 'id');
                return view('eclaim.edit', compact('eclaim', 'eClaimTypes'));
            // } else {
            //     return response()->json(['error' => __('Permission denied.')], 401);
            // }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, Eclaim $eclaim)
{
    if (\Auth::user()->can('Edit Eclaim')) {
        if ($eclaim->created_by == \Auth::user()->creatorId()) {
            $eclaim_id =  $eclaim->id;
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
            $history = ['time' => now(), 'comment' => 'Eclaim Update Requested', 'username' => Auth::user()->name];

            $eClaimType               = Eclaim::where('id', $eclaim_id)->first();
            $eClaimType->type_id      = $request->type_id;
            $eClaimType->amount       = $request->amount;
            $eClaimType->description  = $request->description;
            $eClaimType->receipt      = !empty($request->receipt) ? $fileNameToStore : '';
            $eClaimType->created_by   = \Auth::user()->creatorId();
            // $eClaimType->history = json_encode($history);
            $eClaimType->save();

            // Redirect to the appropriate route after updating
            return redirect()->route('eclaim.index')->with('success', __('Eclaim successfully updated.'));
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
            // if ($eclaimType->created_by == \Auth::user()->creatorId()) {
                $id =  $eclaim->id;
                $eclaim = Eclaim::find($id);
                $eclaim->delete();
                return redirect()->route('eclaim.index')->with('success', __('EclaimType successfully deleted.'));
            // } else {
            //     return redirect()->back()->with('error', __('Permission denied.'));
            // }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


        public function showHistory(Eclaim $eclaim, $id)
        {
                $eclaim = Eclaim::find($id);
                return view('eclaim.history', compact('eclaim'));
        }
}
