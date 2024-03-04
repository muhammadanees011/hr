<?php

namespace App\Http\Controllers;

use App\Models\SelfCertification;
use App\Models\Employee;
use Illuminate\Http\Request;

class SelfCertificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(\Auth::user()->can('Manage Retirement'))
        {
            $selfcertifications = SelfCertification::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('selfcertification.index', compact('selfcertifications'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {   
        if(\Auth::user()->can('Create Retirement'))
        {
            $employees        = Employee::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            return view('selfcertification.create', compact('employees'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {   
        if(\Auth::user()->can('Create Retirement'))
        {
            $validator = \Validator::make(
                $request->all(), [
                    'employee_id' => 'required',
                    'certification_date' => 'required',
                    'certification_type' => 'required',
                    'details' => 'nullable',
                    'certification_file' => 'required',
                    ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $selfcertification                   = new SelfCertification();
            $selfcertification->employee_id      = $request->employee_id;
            $selfcertification->certification_date  = $request->certification_date;
            $selfcertification->certification_type = $request->certification_type;
            $selfcertification->details          = $request->details;
            $selfcertification->certification_file   = $request->certification_file;
            $selfcertification->created_by       = \Auth::user()->creatorId();
            $selfcertification->save();

            return redirect()->route('selfcertification.index')->with('success', __('SelfCertification  successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(SelfCertification $selfCertification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SelfCertification $selfCertification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SelfCertification $selfCertification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SelfCertification $selfCertification)
    {
        //
    }
}
