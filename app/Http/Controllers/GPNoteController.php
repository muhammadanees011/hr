<?php

namespace App\Http\Controllers;

use App\Models\GPNote;
use App\Models\Employee;
use Illuminate\Http\Request;

class GPNoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(\Auth::user()->can('Manage Health And Fitness'))
        {
            $gpnotes = GPNote::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('gpnote.index', compact('gpnotes'));
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
        if(\Auth::user()->can('Create Health And Fitness'))
        {
            $employees        = Employee::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            return view('gpnote.create', compact('employees'));
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
        
        if(\Auth::user()->can('Create Health And Fitness'))
        {

            $validator = \Validator::make(
                $request->all(), [
                    'employee_id' => 'required',
                    'assessment_date' => 'required',
                    'presenting_complaint' => 'required',
                    'assessment' => 'nullable',
                    'follow_up_date' => 'required',
                    'prescription_file' => 'nullable',
                    ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $gpnote                   = new GPNote();
            $gpnote->employee_id      = $request->employee_id;
            $gpnote->assessment_date  = $request->assessment_date;
            $gpnote->presenting_complaint = $request->presenting_complaint;
            $gpnote->assessment       = $request->assessment;
            $gpnote->follow_up_date   = $request->follow_up_date;
            $gpnote->created_by       = \Auth::user()->creatorId();
            $gpnote->save();

            return redirect()->route('gpnote.index')->with('success', __('GPNote  successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(GPNote $gpnote)
    {
        if ($gpnote->created_by == \Auth::user()->creatorId()) {
            $employee   = $gpnote->employee->name;

            return view('gpnote.show', compact('gpnote', 'employee'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(GPNote $gpnote)
    {
        if(\Auth::user()->can('Edit Health And Fitness'))
        {
            $employees        = Employee::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            if($gpnote->created_by == \Auth::user()->creatorId())
            {
                return view('gpnote.edit', compact('gpnote', 'employees'));
            }
            else
            {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, GPNote $gpnote)
    {
        if(\Auth::user()->can('Create Health And Fitness'))
        {

            $validator = \Validator::make(
                $request->all(), [
                    'employee_id' => 'required',
                    'assessment_date' => 'required',
                    'presenting_complaint' => 'required',
                    'assessment' => 'nullable',
                    'follow_up_date' => 'required',
                    'prescription_file' => 'nullable',
                    ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $gpnote->employee_id      = $request->employee_id;
            $gpnote->assessment_date  = $request->assessment_date;
            $gpnote->presenting_complaint = $request->presenting_complaint;
            $gpnote->assessment       = $request->assessment;
            $gpnote->follow_up_date   = $request->follow_up_date;
            $gpnote->save();

            return redirect()->route('gpnote.index')->with('success', __('GPNote  successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GPNote $gpnote)
    {   
        if(\Auth::user()->can('Delete Health And Fitness'))
        {
            if($gpnote->created_by == \Auth::user()->creatorId())
            {
                $gpnote->delete();

                return redirect()->route('gpnote.index')->with('success', __('GPNote successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        } 
    }
}
