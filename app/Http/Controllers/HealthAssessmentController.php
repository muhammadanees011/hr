<?php

namespace App\Http\Controllers;

use App\Models\HealthAssessment;
use App\Models\Employee;
use Illuminate\Http\Request;

class HealthAssessmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(\Auth::user()->can('Manage Retirement'))
        {
            $healthassessments = HealthAssessment::where('created_by', '=', \Auth::user()->creatorId())->get();

            $total_healthassessments = HealthAssessment::where('created_by', '=', \Auth::user()->creatorId())->count();
            $curr_month  = HealthAssessment::where('created_by', '=', \Auth::user()->creatorId())->whereMonth('created_at', '=', date('m'))->count();
            $curr_week   = HealthAssessment::where('created_by', '=', \Auth::user()->creatorId())->whereBetween(
                'created_at',
                [
                    \Carbon\Carbon::now()->startOfWeek(),
                    \Carbon\Carbon::now()->endOfWeek(),
                ]
            )->count();
            $last_30days = HealthAssessment::where('created_by', '=', \Auth::user()->creatorId())->whereDate('created_at', '>', \Carbon\Carbon::now()->subDays(30))->count();

            // Health Assessment Summary
            $cnt_assessment                = [];
            $cnt_assessment['total']       = $total_healthassessments;
            $cnt_assessment['this_month']  = $curr_month;
            $cnt_assessment['this_week']   = $curr_week;
            $cnt_assessment['last_30days'] = $last_30days;

            return view('healthassessment.index', compact('healthassessments','cnt_assessment'));
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
            return view('healthassessment.create', compact('employees'));
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
                    'assessment_type' => 'required',
                    'assessment_date' => 'required',
                    'details' => 'nullable',
                    'assessment_result' => 'nullable',
                    'assessment_file' => 'nullable',
                    ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $healthassessment                   = new HealthAssessment();
            $healthassessment->employee_id      = $request->employee_id;
            $healthassessment->assessment_type  = $request->assessment_type;
            $healthassessment->assessment_date  = $request->assessment_date;
            $healthassessment->details          = $request->details;
            $healthassessment->assessment_result= $request->assessment_result;
            $healthassessment->assessment_file  = $request->assessment_file;
            $healthassessment->created_by       = \Auth::user()->creatorId();
            $healthassessment->save();

            return redirect()->route('healthassessment.index')->with('success', __('HealthAssessment  successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(HealthAssessment $healthassessment)
    {
        if ($healthassessment->created_by == \Auth::user()->creatorId()) {
            $employee   = $healthassessment->employee->name;

            return view('healthassessment.show', compact('healthassessment', 'employee'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(HealthAssessment $healthassessment)
    {
        if(\Auth::user()->can('Edit Retirement'))
        {
            $employees        = Employee::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            if($healthassessment->created_by == \Auth::user()->creatorId())
            {

                return view('healthassessment.edit', compact('healthassessment', 'employees'));
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
    public function update(Request $request, HealthAssessment $healthassessment)
    {
        if(\Auth::user()->can('Edit Retirement'))
        {
            if($healthassessment->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                        'employee_id' => 'required',
                        'assessment_type' => 'required',
                        'assessment_date' => 'required',
                        'details' => 'nullable',
                        'assessment_result' => 'nullable',
                        'assessment_file' => 'nullable',
                        ]
                );

                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $healthassessment->employee_id      = $request->employee_id;
                $healthassessment->assessment_type  = $request->assessment_type;
                $healthassessment->assessment_date  = $request->assessment_date;
                $healthassessment->details          = $request->details;
                $healthassessment->assessment_result= $request->assessment_result;
                $healthassessment->assessment_file  = $request->assessment_file;
                $healthassessment->save();

                return redirect()->route('healthassessment.index')->with('success', __('HealthAssessment successfully updated.'));
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(HealthAssessment $healthassessment)
    {
        if(\Auth::user()->can('Delete Retirement'))
        {
            if($healthassessment->created_by == \Auth::user()->creatorId())
            {
                $healthassessment->delete();

                return redirect()->route('healthassessment.index')->with('success', __('HealthAssessment successfully deleted.'));
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
