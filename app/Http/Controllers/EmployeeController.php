<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Document;
use App\Models\Employee;
use App\Models\EmployeeDocument;
use App\Mail\UserCreate;
use App\Models\EmployeePersonalFile;
use App\Models\User;
use App\Models\Utility;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Imports\EmployeesImport;
use App\Exports\EmployeesExport;
use App\Models\Asset;
use App\Models\Contract;
use App\Models\EmployeeProbation;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\NOC;
use App\Models\Termination;
use App\Models\ExperienceCertificate;
use App\Models\JoiningLetter;
use App\Models\LoginDetail;
use App\Models\PaySlip;

//use Faker\Provider\File;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        if (\Auth::user()->can('Manage Employee')) {
            if (Auth::user()->type == 'employee') {
                $employees = Employee::where('user_id', '=', Auth::user()->id)->where('employee_type', 'Permanent')->get();
            } else {
                $employees = Employee::where('created_by', \Auth::user()->creatorId())->where('employee_type', 'Permanent')->with(['branch', 'department', 'designation'])->get();
            }

            return view('employee.index', compact('employees'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function showEmployeeProbation()
    {
        if (\Auth::user()->can('Manage Employee')) {
            if (Auth::user()->type == 'employee') {
                $employees = Employee::where('user_id', '=', Auth::user()->id)->where('employee_type', 'Probation')->get();
            } else {
                $employees = Employee::where('created_by', \Auth::user()->creatorId())->where('employee_type', 'Probation')->with(['branch', 'department', 'designation', 'probationDetails'])->get();
            }
            return view('employee.indexProbation', compact('employees'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if (\Auth::user()->can('Create Employee')) {
            $company_settings = Utility::settings();
            $documents        = Document::where('created_by', \Auth::user()->creatorId())->get();
            $branches         = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $branches->prepend('Select Branch', '');
            $departments      = Department::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $designations     = Designation::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $employees        = User::where('created_by', \Auth::user()->creatorId())->get();

            $employeesId      = \Auth::user()->employeeIdFormat($this->employeeNumber());

            return view('employee.create', compact('employees', 'employeesId', 'departments', 'designations', 'documents', 'branches', 'company_settings'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function store(Request $request)
    {
        if (\Auth::user()->can('Create Employee')) {
            $default_language = \DB::table('settings')->select('value')->where('name', 'default_language')->first();
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'dob' => 'required',
                    'gender' => 'required',
                    'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
                    'address' => 'required',
                    'email' => 'required|unique:users',
                    'password' => 'required',
                    'department_id' => 'required',
                    'designation_id' => 'required',
                    'document.*' => 'required',
                    'employee_type' => 'required',
                    'probation_days' => 'required_if:employee_type,Probation|numeric|min:1',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->withInput()->with('error', $messages->first());
            }

            if ($request->hasFile('document')) {
                foreach ($request->document as $key => $document) {
                    $filenameWithExt = $request->file('document')[$key]->getClientOriginalName();
                    $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension       = $request->file('document')[$key]->getClientOriginalExtension();
                    $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                    $dir             = 'storage/uploads/document/';

                    $image_path      = $dir . $fileNameToStore;

                    if (\File::exists($image_path)) {
                        \File::delete($image_path);
                    }

                    $path = Utility::upload_coustom_file($request, 'document', $fileNameToStore, $dir, $key, []);

                    if ($path['flag'] == 1) {
                        $url = $path['url'];
                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                }
            }

            $user = User::create(
                [
                    'name' => $request['name'],
                    'email' => $request['email'],
                    'password' => Hash::make($request['password']),
                    'type' => 'employee',
                    'lang' => !empty($default_language) ? $default_language->value : 'en',
                    'created_by' => \Auth::user()->creatorId(),
                    'email_verified_at' => date("Y-m-d H:i:s"),
                ]
            );
            $user->save();
            $user->assignRole('Employee');


            if (!empty($request->document) && !is_null($request->document)) {
                $document_implode = implode(',', array_keys($request->document));
            } else {
                $document_implode = null;
            }

            $employee = Employee::create(
                [
                    'user_id' => $user->id,
                    'name' => $request['name'],
                    'dob' => $request['dob'],
                    'gender' => $request['gender'],
                    'phone' => $request['phone'],
                    'address' => $request['address'],
                    'email' => $request['email'],
                    'password' => Hash::make($request['password']),
                    'employee_id' => $this->employeeNumber(),
                    'branch_id' => $request['branch_id'],
                    'department_id' => $request['department_id'],
                    'designation_id' => $request['designation_id'],
                    'company_doj' => $request['company_doj'],
                    'employee_type' => $request['employee_type'],
                    'documents' => $document_implode,
                    'account_holder_name' => $request['account_holder_name'],
                    'account_number' => $request['account_number'],
                    'bank_name' => $request['bank_name'],
                    'bank_identifier_code' => $request['bank_identifier_code'],
                    'branch_location' => $request['branch_location'],
                    'tax_payer_id' => $request['tax_payer_id'],
                    'created_by' => \Auth::user()->creatorId(),
                ]
            );

            if ($employee->employee_type == 'Probation') {
                EmployeeProbationController::store($employee->id, $request['probation_days']);
            }

            if ($request->hasFile('document')) {
                foreach ($request->document as $key => $document) {
                    $filenameWithExt = $request->file('document')[$key]->getClientOriginalName();
                    $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension       = $request->file('document')[$key]->getClientOriginalExtension();
                    $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                    $dir             = 'uploads/document/';

                    $image_path      = $dir . $fileNameToStore;

                    if (\File::exists($image_path)) {
                        \File::delete($image_path);
                    }

                    $path = Utility::upload_coustom_file($request, 'document', $fileNameToStore, $dir, $key, []);

                    if ($path['flag'] == 1) {
                        $url = $path['url'];
                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                    $employee_document = EmployeeDocument::create(
                        [
                            'employee_id' => $employee['employee_id'],
                            'document_id' => $key,
                            'document_value' => $fileNameToStore,
                            'created_by' => \Auth::user()->creatorId(),
                        ]
                    );
                    $employee_document->save();
                }
            }

            $setings = Utility::settings();
            if ($setings['new_employee'] == 1) {
                $department = Department::find($request['department_id']);
                $branch = Branch::find($request['branch_id']);
                $designation = Designation::find($request['designation_id']);
                $uArr = [
                    'employee_email' => $user->email,
                    'employee_password' => $request->password,
                    'employee_name' => $request['name'],
                    'employee_branch' => !empty($branch->name) ? $branch->name : '',
                    'department_id' => !empty($department->name) ? $department->name : '',
                    'designation_id' => !empty($designation->name) ? $designation->name : '',
                ];
                $resp = Utility::sendEmailTemplate('new_employee', [$user->id => $user->email], $uArr);

                return redirect()->route('employee.index')->with('success', __('Employee successfully created.') . ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
            }

            return redirect()->route($employee->employee_type == 'Probation' ? 'employee.probation.index' : 'employee.index')->with('success', __('Employee  successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function edit($id)
    {
        $id = Crypt::decrypt($id);
        if (\Auth::user()->can('Edit Employee')) {
            $documents    = Document::where('created_by', \Auth::user()->creatorId())->get();
            $branches     = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $departments  = Department::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $designations = Designation::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $employee     = Employee::find($id);
            $employeesId  = \Auth::user()->employeeIdFormat($employee->employee_id);

            return view('employee.edit', compact('employee', 'employeesId', 'branches', 'departments', 'designations', 'documents'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function update(Request $request, $id)
    {
        if (\Auth::user()->can('Edit Employee')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'dob' => 'required',
                    'gender' => 'required',
                    'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:9',
                    'address' => 'required',
                    'document.*' => 'required',
                    'employee_type' => 'required',
                    'probation_days' => 'required_if:employee_type,Probation|numeric|min:1',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $employee = Employee::findOrFail($id);

            if ($request['employee_type'] == 'Probation') {
                $probation = EmployeeProbation::where('employee_id', $employee->id)->first();
                $probation->duration = $request['probation_days'];
                $probation->save();
            }

            if ($request->document) {
                foreach ($request->document as $key => $document) {
                    if (!empty($document)) {


                        $filenameWithExt = $request->file('document')[$key]->getClientOriginalName();
                        $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                        $extension       = $request->file('document')[$key]->getClientOriginalExtension();
                        $fileNameToStore = $filename . '_' . time() . '.' . $extension;

                        $dir             = 'storage/uploads/document/';

                        $image_path      = $dir . $fileNameToStore;

                        if (\File::exists($image_path)) {
                            \File::delete($image_path);
                        }

                        $path = \Utility::upload_coustom_file($request, 'document', $fileNameToStore, $dir, $key, []);

                        if ($path['flag'] == 1) {
                            $url = $path['url'];
                        } else {
                            return redirect()->back()->with('error', __($path['msg']));
                        }

                        $employee_document = EmployeeDocument::where('employee_id', $employee->employee_id)->where('document_id', $key)->first();

                        if (!empty($employee_document)) {
                            if ($employee_document->document_value) {
                                \File::delete(storage_path('uploads/document/' . $employee_document->document_value));
                            }
                            $employee_document->document_value = $fileNameToStore;
                            $employee_document->save();
                        } else {
                            $employee_document                 = new EmployeeDocument();
                            $employee_document->employee_id    = $employee->employee_id;
                            $employee_document->document_id    = $key;
                            $employee_document->document_value = $fileNameToStore;
                            $employee_document->save();
                        }
                    }
                }
            }

            $employee = Employee::findOrFail($id);
            $input    = $request->all();
            $employee->fill($input)->save();
            if ($request->salary) {
                return redirect()->route('setsalary.index')->with('success', 'Employee successfully updated.');
            }

            if (\Auth::user()->type != 'employee') {
                return redirect()->route($employee->employee_type == 'Probation' ? 'employee.probation.index' : 'employee.index')->with('success', 'Employee successfully updated.');
            } else {
                return redirect()->route('employee.show', \Illuminate\Support\Facades\Crypt::encrypt($employee->id))->with('success', 'Employee successfully updated.');
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy($id)
    {

        if (Auth::user()->can('Delete Employee')) {
            $employee      = Employee::findOrFail($id);
            $user          = User::where('id', '=', $employee->user_id)->first();
            $ContractEmployee = Contract::where('employee_name', '=', $employee->user_id)->get();
            $emp_documents = EmployeeDocument::where('employee_id', $employee->employee_id)->get();
            $payslips = PaySlip::where('employee_id', $id)->get();

            $employee->delete();
            $user->delete();
            foreach ($payslips as $payslip) {
                $payslip->delete();
            }
            foreach ($ContractEmployee as $contractdelete) {
                $contractdelete->delete();
            }

            $dir = storage_path('uploads/document/');
            foreach ($emp_documents as $emp_document) {
                $emp_document->delete();
                \File::delete(storage_path('uploads/document/' . $emp_document->document_value));
                if (!empty($emp_document->document_value)) {
                    // unlink($dir . $emp_document->document_value);
                }
            }

            return redirect()->route('employee.index')->with('success', 'Employee successfully deleted.');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show($id)
    {

        if (\Auth::user()->can('Show Employee')) {
            $empId        = Crypt::decrypt($id);
            $documents    = Document::where('created_by', \Auth::user()->creatorId())->get();
            $branches     = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $departments  = Department::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $designations = Designation::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $employee     = Employee::find($empId);
            $employeesId  = \Auth::user()->employeeIdFormat($employee->employee_id);
            // dd($documents);

            return view('employee.show', compact('employee', 'employeesId', 'branches', 'departments', 'designations', 'documents'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    // public function json(Request $request)
    // {
    //     $designations = Designation::where('department_id', $request->department_id)->get()->pluck('name', 'id')->toArray();

    //     return response()->json($designations);
    // }

    function employeeNumber()
    {
        $latest = Employee::where('created_by', '=', \Auth::user()->creatorId())->latest('id')->first();
        if (!$latest) {
            return 1;
        }

        return $latest->employee_id + 1;
    }

    public function profile(Request $request)
    {
        if (\Auth::user()->can('Manage Employee Profile')) {
            $employees = Employee::where('created_by', \Auth::user()->creatorId())->with(['designation', 'user']);
            if (!empty($request->branch)) {
                $employees->where('branch_id', $request->branch);
            }
            if (!empty($request->department)) {
                $employees->where('department_id', $request->department);
            }
            if (!empty($request->designation)) {
                $employees->where('designation_id', $request->designation);
            }
            $employees = $employees->get();

            $brances = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $brances->prepend('All', '0');

            $departments = Department::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $departments->prepend('All', '0');

            $designations = Designation::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $designations->prepend('All', '0');

            return view('employee.profile', compact('employees', 'departments', 'designations', 'brances'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function meetTeam(Request $request)
    {
        $query = Employee::with(['designation', 'user']);
        if(\Auth::user()->type=="employee"){
            $employee = Employee::where('user_id',\Auth::user()->id)->first();
            $query = $query->where('user_id', '!=', \Auth::user()->id)->where('department_id', $employee->department_id);
        }elseif(\Auth::user()->type=="employee" || \Auth::user()->type=="manager"){
            $query = $query->where('created_by', \Auth::user()->creatorId());
        }

        if (!empty($request->department)) {
            $query->where('department_id', $request->department);
        }
        if (!empty($request->designation)) {
            $query->where('designation_id', $request->designation);
        }
        $employees = $query->get();
        
        $brances = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $departments = Department::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
        $designations = Designation::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

        return view('employee.meetTeam', compact('employees', 'departments', 'designations', 'brances'));
    }


    public function profileShow($id)
    {
        if (\Auth::user()->can('Show Employee Profile')) {
            try {
                $empId        = \Illuminate\Support\Facades\Crypt::decrypt($id);
            } catch (\RuntimeException $e) {
                return redirect()->back()->with('error', __('Employee not avaliable'));
            }
            $documents    = Document::where('created_by', \Auth::user()->creatorId())->get();
            $branches     = Branch::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $departments  = Department::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $designations = Designation::where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $employee     = Employee::find($empId);
            if ($employee == null) {
                $employee     = Employee::where('user_id', $empId)->first();
            }
            $employeesId  = \Auth::user()->employeeIdFormat($employee->employee_id);

            return view('employee.show', compact('employee', 'employeesId', 'branches', 'departments', 'designations', 'documents'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function lastLogin(Request $request)
    {
        $users = User::where('created_by', \Auth::user()->creatorId())->get();

        $time = date_create($request->month);
        $firstDayofMOnth = (date_format($time, 'Y-m-d'));
        $lastDayofMonth =    \Carbon\Carbon::parse($request->month)->endOfMonth()->toDateString();
        $objUser = \Auth::user();
        // $currentlocation = User::userCurrentLocation();
        $usersList = User::where('created_by', '=', $objUser->creatorId())
            ->whereNotIn('type', ['super admin', 'company'])->get()->pluck('name', 'id');
        $usersList->prepend('All', '');
        if ($request->month == null) {
            $userdetails = \DB::table('login_details')
                ->join('users', 'login_details.user_id', '=', 'users.id')
                ->select(\DB::raw('login_details.*, users.id as user_id , users.name as user_name , users.email as user_email ,users.type as user_type'))
                ->where(['login_details.created_by' => \Auth::user()->creatorId()])
                ->whereMonth('date', date('m'))->whereYear('date', date('Y'));
        } else {
            $userdetails = \DB::table('login_details')
                ->join('users', 'login_details.user_id', '=', 'users.id')
                ->select(\DB::raw('login_details.*, users.id as user_id , users.name as user_name , users.email as user_email ,users.type as user_type'))
                ->where(['login_details.created_by' => \Auth::user()->creatorId()]);
        }
        if (!empty($request->month)) {
            $userdetails->where('date', '>=', $firstDayofMOnth);
            $userdetails->where('date', '<=', $lastDayofMonth);
        }
        if (!empty($request->employee)) {
            $userdetails->where(['user_id'  => $request->employee]);
        }
        $userdetails = $userdetails->get();

        return view('employee.lastLogin', compact('users', 'usersList', 'userdetails'));
    }

    public function view($id)
    {
        $users = LoginDetail::find($id);
        return view('employee.user_log', compact('users'));
    }

    public function logindestroy($id)
    {
        $employee = LoginDetail::where('user_id', $id)->delete();

        return redirect()->back()->with('success', 'Employee successfully deleted.');
    }

    public function employeeJson(Request $request)
    {
        $employees = Employee::where('branch_id', $request->branch)->get()->pluck('name', 'id')->toArray();

        return response()->json($employees);
    }
    public function importFile()
    {
        return view('employee.import');
    }

    public function import(Request $request)
    {
        $rules = [
            'file' => 'required|mimes:csv,txt',
        ];

        $validator = \Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }

        $employees = (new EmployeesImport())->toArray(request()->file('file'))[0];
        $totalCustomer = count($employees) - 1;
        $errorArray    = [];

        for ($i = 1; $i <= count($employees) - 1; $i++) {

            $employee = $employees[$i];

            $employeeByEmail = Employee::where('email', $employee[5])->first();
            $userByEmail = User::where('email', $employee[5])->first();


            if (!empty($employeeByEmail) && !empty($userByEmail)) {
                $employeeData = $employeeByEmail;
            } else {

                $user = new User();
                $user->name = $employee[0];
                $user->email = $employee[5];
                $user->password = Hash::make($employee[6]);
                $user->type = 'employee';
                $user->lang = 'en';
                $user->created_by = \Auth::user()->creatorId();
                $user->email_verified_at = date("Y-m-d H:i:s");
                $user->save();
                $user->assignRole('Employee');

                $employeeData = new Employee();
                $employeeData->employee_id      = $this->employeeNumber();
                $employeeData->user_id             = $user->id;
            }


            $employeeData->name                = $employee[0];
            $employeeData->dob                 = $employee[1];
            $employeeData->gender              = $employee[2];
            $employeeData->phone               = $employee[3];
            $employeeData->address             = $employee[4];
            $employeeData->email               = $employee[5];
            $employeeData->password            = Hash::make($employee[6]);
            $employeeData->employee_id         = $this->employeeNumber();
            $employeeData->branch_id           = $employee[8];
            $employeeData->department_id       = $employee[9];
            $employeeData->designation_id      = $employee[10];
            $employeeData->company_doj         = $employee[11];
            $employeeData->account_holder_name = $employee[12];
            $employeeData->account_number      = $employee[13];
            $employeeData->bank_name           = $employee[14];
            $employeeData->bank_identifier_code = $employee[15];
            $employeeData->branch_location     = $employee[16];
            $employeeData->tax_payer_id        = $employee[17];
            $employeeData->created_by          = \Auth::user()->creatorId();

            if (empty($employeeData)) {
                $errorArray[] = $employeeData;
            } else {
                $employeeData->save();
            }
        }

        $errorRecord = [];
        if (empty($errorArray)) {
            $data['status'] = 'success';
            $data['msg']    = __('Record successfully imported');
        } else {
            $data['status'] = 'error';
            $data['msg']    = count($errorArray) . ' ' . __('Record imported fail out of' . ' ' . $totalCustomer . ' ' . 'record');


            foreach ($errorArray as $errorData) {

                $errorRecord[] = implode(',', $errorData);
            }

            \Session::put('errorArray', $errorRecord);
        }

        return redirect()->back()->with($data['status'], $data['msg']);
    }

    public function export()
    {
        $name = 'employee_' . date('Y-m-d i:h:s');
        $data = Excel::download(new EmployeesExport(), $name . '.xlsx');

        return $data;
    }
    public function joiningletterPdf($id)
    {
        $users = \Auth::user();

        $currantLang = $users->currentLanguage();
        $joiningletter = JoiningLetter::where('lang', $currantLang)->first();
        $date = date('Y-m-d');
        $employees = Employee::find($id);
        $settings = Utility::settings();
        $secs = strtotime($settings['company_start_time']) - strtotime("00:00");
        $result = date("H:i", strtotime($settings['company_end_time']) - $secs);
        $obj = [
            'date' =>  \Auth::user()->dateFormat($date),
            'app_name' => env('APP_NAME'),
            'employee_name' => $employees->name,
            'address' => !empty($employees->address) ? $employees->address : '',
            'designation' => !empty($employees->designation->name) ? $employees->designation->name : '',
            'start_date' => !empty($employees->company_doj) ? $employees->company_doj : '',
            'branch' => !empty($employees->Branch->name) ? $employees->Branch->name : '',
            'start_time' => !empty($settings['company_start_time']) ? $settings['company_start_time'] : '',
            'end_time' => !empty($settings['company_end_time']) ? $settings['company_end_time'] : '',
            'total_hours' => $result,
        ];

        $joiningletter->content = JoiningLetter::replaceVariable($joiningletter->content, $obj);
        return view('employee.template.joiningletterpdf', compact('joiningletter', 'employees'));
    }
    public function joiningletterDoc($id)
    {
        $users = \Auth::user();

        $currantLang = $users->currentLanguage();
        $joiningletter = JoiningLetter::where('lang', $currantLang)->first();
        $date = date('Y-m-d');
        $employees = Employee::find($id);
        $settings = Utility::settings();
        $secs = strtotime($settings['company_start_time']) - strtotime("00:00");
        $result = date("H:i", strtotime($settings['company_end_time']) - $secs);



        $obj = [
            'date' =>  \Auth::user()->dateFormat($date),

            'app_name' => env('APP_NAME'),
            'employee_name' => $employees->name,
            'address' => !empty($employees->address) ? $employees->address : '',
            'designation' => !empty($employees->designation->name) ? $employees->designation->name : '',
            'start_date' => !empty($employees->company_doj) ? $employees->company_doj : '',
            'branch' => !empty($employees->Branch->name) ? $employees->Branch->name : '',
            'start_time' => !empty($settings['company_start_time']) ? $settings['company_start_time'] : '',
            'end_time' => !empty($settings['company_end_time']) ? $settings['company_end_time'] : '',
            'total_hours' => $result,
            //

        ];
        $joiningletter->content = JoiningLetter::replaceVariable($joiningletter->content, $obj);
        return view('employee.template.joiningletterdocx', compact('joiningletter', 'employees'));
    }

    public function ExpCertificatePdf($id)
    {
        $currantLang = \Cookie::get('LANGUAGE');
        if (!isset($currantLang)) {
            $currantLang = 'en';
        }
        $termination = Termination::where('employee_id', $id)->first();
        $experience_certificate = ExperienceCertificate::where('lang', $currantLang)->first();
        $date = date('Y-m-d');
        $employees = Employee::find($id);
        $settings = Utility::settings();
        $secs = strtotime($settings['company_start_time']) - strtotime("00:00");
        $result = date("H:i", strtotime($settings['company_end_time']) - $secs);
        $date1 = date_create($employees->company_doj);
        $date2 = date_create($employees->termination_date);
        $diff  = date_diff($date1, $date2);
        $duration = $diff->format("%a days");

        if (!empty($termination->termination_date)) {

            $obj = [
                'date' =>  \Auth::user()->dateFormat($date),
                'app_name' => env('APP_NAME'),
                'employee_name' => $employees->name,
                'payroll' => !empty($employees->salaryType->name) ? $employees->salaryType->name : '',
                'duration' => $duration,
                'designation' => !empty($employees->designation->name) ? $employees->designation->name : '',

            ];
        } else {
            return redirect()->back()->with('error', __('Termination date is required.'));
        }


        $experience_certificate->content = ExperienceCertificate::replaceVariable($experience_certificate->content, $obj);
        return view('employee.template.ExpCertificatepdf', compact('experience_certificate', 'employees'));
    }
    public function ExpCertificateDoc($id)
    {
        $currantLang = \Cookie::get('LANGUAGE');
        if (!isset($currantLang)) {
            $currantLang = 'en';
        }
        $termination = Termination::where('employee_id', $id)->first();
        $experience_certificate = ExperienceCertificate::where('lang', $currantLang)->first();
        $date = date('Y-m-d');
        $employees = Employee::find($id);
        $settings = Utility::settings();
        $secs = strtotime($settings['company_start_time']) - strtotime("00:00");
        $result = date("H:i", strtotime($settings['company_end_time']) - $secs);
        $date1 = date_create($employees->company_doj);
        $date2 = date_create($employees->termination_date);
        $diff  = date_diff($date1, $date2);
        $duration = $diff->format("%a days");
        if (!empty($termination->termination_date)) {
            $obj = [
                'date' =>  \Auth::user()->dateFormat($date),
                'app_name' => env('APP_NAME'),
                'employee_name' => $employees->name,
                'payroll' => !empty($employees->salaryType->name) ? $employees->salaryType->name : '',
                'duration' => $duration,
                'designation' => !empty($employees->designation->name) ? $employees->designation->name : '',

            ];
        } else {
            return redirect()->back()->with('error', __('Termination date is required.'));
        }

        $experience_certificate->content = ExperienceCertificate::replaceVariable($experience_certificate->content, $obj);
        return view('employee.template.ExpCertificatedocx', compact('experience_certificate', 'employees'));
    }
    public function NocPdf($id)
    {
        $users = \Auth::user();

        $currantLang = $users->currentLanguage();
        $noc_certificate = NOC::where('lang', $currantLang)->first();
        $date = date('Y-m-d');
        $employees = Employee::find($id);
        $settings = Utility::settings();
        $secs = strtotime($settings['company_start_time']) - strtotime("00:00");
        $result = date("H:i", strtotime($settings['company_end_time']) - $secs);


        $obj = [
            'date' =>  \Auth::user()->dateFormat($date),
            'employee_name' => $employees->name,
            'designation' => !empty($employees->designation->name) ? $employees->designation->name : '',
            'app_name' => env('APP_NAME'),
        ];

        $noc_certificate->content = NOC::replaceVariable($noc_certificate->content, $obj);
        return view('employee.template.Nocpdf', compact('noc_certificate', 'employees'));
    }
    public function NocDoc($id)
    {
        $users = \Auth::user();

        $currantLang = $users->currentLanguage();
        $noc_certificate = NOC::where('lang', $currantLang)->first();
        $date = date('Y-m-d');
        $employees = Employee::find($id);
        $settings = Utility::settings();
        $secs = strtotime($settings['company_start_time']) - strtotime("00:00");
        $result = date("H:i", strtotime($settings['company_end_time']) - $secs);


        $obj = [
            'date' =>  \Auth::user()->dateFormat($date),
            'employee_name' => $employees->name,
            'designation' => !empty($employees->designation->name) ? $employees->designation->name : '',
            'app_name' => env('APP_NAME'),
        ];

        $noc_certificate->content = NOC::replaceVariable($noc_certificate->content, $obj);
        return view('employee.template.Nocdocx', compact('noc_certificate', 'employees'));
    }

    public function getdepartment(Request $request)
    {
        if ($request->branch_id == 0) {
            $departments = Department::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id')->toArray();
        } else {
            $departments = Department::where('created_by', '=', \Auth::user()->creatorId())->where('branch_id', $request->branch_id)->get()->pluck('name', 'id')->toArray();
        }
        return response()->json($departments);
    }

    public function json(Request $request)
    {
        if ($request->department_id == 0) {
            $designations = Designation::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id')->toArray();
        }
        $designations = Designation::where('department_id', $request->department_id)->get()->pluck('name', 'id')->toArray();

        return response()->json($designations);
    }

    public function showPersonalFile($id)
    {
        try {
            $id = \Illuminate\Support\Facades\Crypt::decrypt($id);
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', __('Personal file not avaliable'));
        }
        $employee = Employee::where('id', $id)->with(['branch', 'department', 'designation'])->first();

        if ($employee->created_by == \Auth::user()->creatorId()) {
            $personalFiles = EmployeePersonalFile::where('employee_id', $employee->id)->get();
            return view('employee.personalFile', compact('personalFiles', 'employee'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function storePersonalFile(Request $request)
    {
        if (\Auth::user()->can('Store Personal File')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'employee_id' => 'required',
                    'name' => 'required',
                    'file' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $personalFile        = new EmployeePersonalFile();
            $personalFile->employee_id = $request->employee_id;
            $personalFile->name = $request->name;
            if (!empty($request->file)) {
                $image_size = $request->file('file')->getSize();

                $filenameWithExt = $request->file('file')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('file')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $dir = 'uploads/personalFile/';
                $image_path = $dir . $fileNameToStore;

                $url = '';
                $path = Utility::upload_file($request, 'file', $fileNameToStore, $dir, []);
                $personalFile->file    = !empty($request->file) ? $fileNameToStore : '';
                if ($path['flag'] == 1) {
                    $url = $path['url'];
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }

            $personalFile->created_by     = \Auth::user()->creatorId();
            $personalFile->save();

            return redirect()->route('employee.personalFile', encrypt($request->employee_id))->with('success', __('Document successfully uplaoded.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function EmployeeTypeUpdate(Request $request, $id)
    {
        if (\Auth::user()->can('Edit Employee Type')) {
            $probation = EmployeeProbation::where('employee_id', $id)->first();
            if ($probation->delete()) {
                $employee = Employee::where('id', $id)->first();
                $employee->employee_type = 'Permanent';
                $employee->save();
            }
            return redirect()->back()->with('success', __('Employee now changed to permanent status!'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
