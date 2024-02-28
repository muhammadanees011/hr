<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Convert To Employee')); ?>

<?php $__env->stopSection(); ?>


<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item">
        <a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a>
    </li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('job.on.board')); ?>"><?php echo e(__('Job OnBoard')); ?></a></li>

    <li class="breadcrumb-item"><?php echo e(__('Convert To Employee')); ?></li>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
    <div class="row">
        <?php echo e(Form::open(['route' => ['job.on.board.convert', $jobOnBoard->id], 'method' => 'post', 'enctype' => 'multipart/form-data'])); ?>

    </div>

    <div class="col-md-6 ">
        <div class="card  em-card">
            <div class="card-header">
                <h6 class="mb-0"><?php echo e(__('Personal Detail')); ?></h6>
            </div>
            <div class="card-body ">
                <div class="row">
                    <div class="form-group col-md-6">
                        <?php echo Form::label('name', __('Name'), ['class' => 'col-form-label']); ?><span class="text-danger pl-1">*</span>
                        <?php echo Form::text('name', !empty($jobOnBoard->applications) ? $jobOnBoard->applications->name : '', ['class' => 'form-control', 'required' => 'required']); ?>

                    </div>
                    <div class="form-group col-md-6">
                        <?php echo Form::label('phone', __('Phone'), ['class' => 'col-form-label']); ?><span class="text-danger pl-1">*</span>
                        <?php echo Form::text('phone', !empty($jobOnBoard->applications) ? $jobOnBoard->applications->phone : '', ['class' => 'form-control']); ?>

                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <?php echo Form::label('dob', __('Date of Birth'), ['class' => 'col-form-label']); ?><span class="text-danger pl-1">*</span>
                            <?php echo Form::date('dob', !empty($jobOnBoard->applications) ? $jobOnBoard->applications->dob : '', ['class' => 'form-control ']); ?>

                        </div>
                    </div>

                    <div class="col-md-6 ">
                        <div class="form-group ">
                            <?php echo Form::label('gender', __('Gender'), ['class' => 'col-form-label']); ?><span class="text-danger pl-1">*</span>
                             

                            <div class="d-flex radio-check">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="g_male" value="Male" name="gender"
                                        class="form-check-input" <?php echo e(!empty($jobOnBoard->applications) && $jobOnBoard->applications->gender == 'Male' ? 'checked' : ''); ?>>
                                    <label class="form-check-label " for="g_male"><?php echo e(__('Male')); ?></label>
                                </div>
                                <div class="custom-control custom-radio ms-1 custom-control-inline">
                                    <input type="radio" id="g_female" value="Female" name="gender"
                                        class="form-check-input" <?php echo e(!empty($jobOnBoard->applications) && $jobOnBoard->applications->gender == 'Female' ? 'checked' : ''); ?>>
                                    <label class="form-check-label "
                                        for="g_female"><?php echo e(__('Female')); ?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-md-6">
                        <?php echo Form::label('email', __('Email'), ['class' => 'col-form-label']); ?><span class="text-danger pl-1">*</span>
                        <?php echo Form::email('email', old('email'), ['class' => 'form-control', 'required' => 'required']); ?>

                    </div>
                    <div class="form-group col-md-6">
                        <?php echo Form::label('password', __('Password'), ['class' => 'col-form-label']); ?><span class="text-danger pl-1">*</span>
                        <?php echo Form::password('password', ['class' => 'form-control', 'required' => 'required']); ?>

                    </div>
                </div>
                <div class="form-group">
                    <?php echo Form::label('address', __('Address'), ['class' => 'col-form-label']); ?><span class="text-danger pl-1">*</span>
                    <?php echo Form::textarea('address', old('address'), ['class' => 'form-control', 'rows' => 3, 'placeholder' => 'Enter Address']); ?>

                </div>
            </div>
        </div>
    </div>

    <div class="col-md-6 ">
        <div class="card  " style="min-height:550px;">
            <div class="card-header">
                <h6 class="mb-0"><?php echo e(__('Company Detail')); ?></h6>
            </div>
            <div class="card-body employee-detail-create-body">
                <div class="row">
                    <?php echo csrf_field(); ?>
                    <div class="form-group col-md-12">
                        <?php echo Form::label('employee_id', __('Employee ID'), ['class' => 'col-form-label']); ?>

                        <?php echo Form::text('employee_id', $employeesId, ['class' => 'form-control', 'disabled' => 'disabled']); ?>

                    </div>

                    <div class="form-group col-md-6">
                        <?php echo e(Form::label('branch_id', __('Branch'), ['class' => 'col-form-label'])); ?>

                        <?php echo e(Form::select('branch_id', $branches, !empty($jobOnBoard->applications) ? (!empty($jobOnBoard->applications->jobs) ? $jobOnBoard->applications->jobs->branch : '') : '', ['class' => 'form-control branch_id', 'required' => 'required', 'id' => 'branch_id'])); ?>

                    </div>  

                    <div class="form-group col-md-6">
                        <?php echo e(Form::label('department_id', __('Department'), ['class' => 'col-form-label'])); ?>

                        <?php echo e(Form::select('department_id', $departments, null, ['class' => 'form-control department_id', 'id' => 'department_id', 'required' => 'required', 'placeholder' => 'Select Department'])); ?>

                    </div>

                    <div class="form-group ">
                        <?php echo e(Form::label('designation_id', __('Designation'), ['class' => 'col-form-label'])); ?>

                        <select class=" form-control select2-multiple" id="designation_id" name="designation_id"
                            data-toggle="select2" data-placeholder="<?php echo e(__('Select Designation ...')); ?>">
                            <option value=""><?php echo e(__('Select any Designation')); ?></option>
                        </select>
                    </div>
                    <div class="form-group ">
                        <?php echo Form::label('company_doj', __('Company Date Of Joining'), ['class' => 'col-form-label']); ?>

                        <?php echo Form::date('company_doj', $jobOnBoard->joining_date, ['class' => 'form-control ', 'required' => 'required']); ?>

                    </div>  

                    
                   
                   
                </div>
            </div>
        </div>
    </div>


    <div class="col-md-6 ">
        <div class="card  em-card">
            <div class="card-header">
                <h6 class="mb-0"><?php echo e(__('Document')); ?></h6>
            </div>
            <div class="card-body employee-detail-create-body">
                <?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    
                    <div class="row">
                        <div class="form-group col-12 d-flex">
                            <div class="float-left col-4">
                                <label for="document" class="float-left pt-1 form-label"><?php echo e($document->name); ?>

                                    <?php if($document->is_required == 1): ?>
                                        <span class="text-danger">*</span>
                                    <?php endif; ?>
                                </label>
                            </div>
                            <div class="float-right col-8">
                                <input type="hidden" name="emp_doc_id[<?php echo e($document->id); ?>]" id=""
                                    value="<?php echo e($document->id); ?>">

                                <div class="choose-files ">
                                    <label for="document[<?php echo e($document->id); ?>]">
                                        <div class=" bg-primary document "> <i
                                                class="ti ti-upload px-1"></i><?php echo e(__('Choose file here')); ?>

                                        </div>
                                        <input type="file"
                                            class="form-control file  d-none <?php $__errorArgs = ['document'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            <?php if($document->is_required == 1): ?> required <?php endif; ?>
                                            name="document[<?php echo e($document->id); ?>]" id="document[<?php echo e($document->id); ?>]"
                                            data-filename="<?php echo e($document->id . '_filename'); ?>">
                                    </label>
                                    <a href="#">
                                        <p class="<?php echo e($document->id . '_filename'); ?> "></p>
                                    </a>
                                </div>

                            </div>

                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
    <div class="col-md-6 ">
        <div class="card  em-card">
            <div class="card-header">
                <h6 class="mb-0"><?php echo e(__('Bank Account Detail')); ?></h6>
            </div>
            <div class="card-body employee-detail-create-body">
                <div class="row">
                    <div class="form-group col-md-6">
                        <?php echo Form::label('account_holder_name', __('Account Holder Name'), ['class' => 'col-form-label']); ?>

                        <?php echo Form::text('account_holder_name', old('account_holder_name'), ['class' => 'form-control', 'placeholder' => 'Enter Account Holder Name']); ?>


                    </div>
                    <div class="form-group col-md-6">
                        <?php echo Form::label('account_number', __('Account Number'), ['class' => 'col-form-label']); ?>

                        <?php echo Form::number('account_number', old('account_number'), ['class' => 'form-control', 'placeholder' => 'Enter Account Number']); ?>


                    </div>
                    <div class="form-group col-md-6">
                        <?php echo Form::label('bank_name', __('Bank Name'), ['class' => 'col-form-label']); ?>

                        <?php echo Form::text('bank_name', old('bank_name'), ['class' => 'form-control', 'placeholder' => 'Enter Bank Name']); ?>


                    </div>
                    <div class="form-group col-md-6">
                        <?php echo Form::label('bank_identifier_code', __('Bank Identifier Code'), ['class' => 'col-form-label']); ?>

                        <?php echo Form::text('bank_identifier_code', old('bank_identifier_code'), ['class' => 'form-control', 'placeholder' => 'Enter Bank Identifier Code']); ?>

                    </div>
                    <div class="form-group col-md-6">
                        <?php echo Form::label('branch_location', __('Branch Location'), ['class' => 'col-form-label']); ?>

                        <?php echo Form::text('branch_location', old('branch_location'), ['class' => 'form-control', 'placeholder' => 'Enter Branch Location']); ?>

                    </div>
                    <div class="form-group col-md-6">
                        <?php echo Form::label('tax_payer_id', __('Tax Payer Id'), ['class' => 'col-form-label']); ?>

                        <?php echo Form::text('tax_payer_id', old('tax_payer_id'), ['class' => 'form-control', 'placeholder' => 'Enter Tax Payer Id']); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="col-12">
        <?php echo Form::submit('Create', ['class' => 'btn  btn-primary float-end']); ?>

        
        <?php echo e(Form::close()); ?>

    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-page'); ?>
    <script>
        
        $(document).ready(function() {
            var b_id = $('#branch_id').val();
            // getDepartment(b_id);
        });
        $(document).on('change', 'select[name=branch_id]', function() {
            var branch_id = $(this).val();

            getDepartment(branch_id);
        });

        function getDepartment(bid) {

            $.ajax({
                url: '<?php echo e(route('monthly.getdepartment')); ?>',
                type: 'POST',
                data: {
                    "branch_id": bid,
                    "_token": "<?php echo e(csrf_token()); ?>",
                },
                success: function(data) {

                    $('.department_id').empty();
                    var emp_selct = `<select class="form-control department_id" name="department_id" id="choices-multiple"
                                            placeholder="Select Department" required>
                                            </select>`;
                    $('.department_div').html(emp_selct);

                    $('.department_id').append('<option value=""> <?php echo e(__('Select Department')); ?> </option>');
                    $.each(data, function(key, value) {
                        $('.department_id').append('<option value="' + key + '">' + value +
                            '</option>');
                    });
                    new Choices('#choices-multiple', {
                        removeItemButton: true,
                    });
                }
            });
        }
        
        $(document).ready(function() {
            var d_id = $('#department_id').val();
            getDesignation(d_id);
        });

        $(document).on('change', 'select[name=department_id]', function() {
            var department_id = $(this).val();
            getDesignation(department_id);
        });

        function getDesignation(did) {

            $.ajax({
                url: '<?php echo e(route('employee.json')); ?>',
                type: 'POST',
                data: {
                    "department_id": did,
                    "_token": "<?php echo e(csrf_token()); ?>",
                },
                success: function(data) {
                    $('#designation_id').empty();
                    $('#designation_id').append(
                        '<option value=""><?php echo e(__('Select any Designation')); ?></option>');
                    $.each(data, function(key, value) {
                        $('#designation_id').append('<option value="' + key + '">' + value +
                            '</option>');
                    });
                }
            });
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\HRMGO\resources\views/jobApplication/convert.blade.php ENDPATH**/ ?>