

<?php $__env->startSection('page-title'); ?>
<?php echo e(__('Create Pension Opt-in')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
<li class="breadcrumb-item"><a href="<?php echo e(url('pension-opt-ins')); ?>"><?php echo e(__('Pension Opt-ins')); ?></a></li>
<li class="breadcrumb-item"><?php echo e(__('Create Pension Opt-ins')); ?></li>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('content'); ?>
<style>
    .cursor-pointer {
        cursor: pointer;
    }
</style>

<div class="">
    <div class="">
        <?php echo e(Form::model($PensionOptIn, ['route' => ['pension-opt-ins.update', $PensionOptIn->id], 'method' => 'PUT', 'enctype' => 'multipart/form-data'])); ?>

        <div class="row">
            <div class="col-md-6">
                <div class="card em-card">
                    <div class="card-header">
                        <h5><?php echo e(__('Opt-in Form')); ?></h5>
                    </div>
                    <div class="card-body employee-detail-create-body">
                        <div class="row">
                            <?php echo csrf_field(); ?>
                            <div class="form-group">
                                <?php echo e(Form::label('employee_id', __('Select Employee*'), ['class' => 'form-label'])); ?>

                                <div class="form-icon-user">
                                    <?php echo e(Form::select('employee_id', $employees, null, ['class' => 'form-control employee_id', 'required' => 'required', 'placeholder' => 'Select Employee', 'id' => 'employee_id'])); ?>

                                </div>
                            </div>
                            <div class="form-group">
                                <?php echo e(Form::label('pension_scheme_id', __('Select Pension Scheme*'), ['class' => 'form-label'])); ?>

                                <div class="form-icon-user">
                                    <?php echo e(Form::select('pension_scheme_id', $pensionSchemes, null, ['class' => 'form-control pension_scheme_id', 'required' => 'required', 'placeholder' => 'Select Pension Scheme', 'id' => 'pension_scheme_id'])); ?>

                                </div>
                            </div>
                            <div class="form-group">
                                <?php echo Form::label('date', __('Opt-in Date'), ['class' => 'form-label']); ?><span class="text-danger pl-1">*</span>
                                <?php echo e(Form::date('date', null, ['class' => 'form-control current_date', 'required' => 'required', 'autocomplete' => 'off', 'placeholder' => 'Select Opt-in date'])); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card em-card" id="emp-detail-block" style="display: none;">
                    <div class="card-header">
                        <h5><?php echo e(__('Employee Details')); ?></h5>
                    </div>
                    <div class="card-body employee-detail-create-body">
                        <div class="row">
                            <div id="emp_detail"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="float-end">
        <button type="submit" class="btn  btn-primary"><?php echo e('Submit'); ?></button>
    </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-page'); ?>
<script>
    var initialEmpId = $('select[name=employee_id]').val();
    if (initialEmpId) {
        getEmployee(initialEmpId);
    }

    $(document).on('change', 'select[name=employee_id]', function() {
        var emp_id = $(this).val();
        // console.log("sadasdasd", emp_id);
        getEmployee(emp_id);
    });

    function getEmployee(eid) {
        $.ajax({
            url: '<?php echo e(route('pensionOptIn.emp')); ?>',
            type: 'POST',
            data: {
                "emp_id": eid,
                "_token": "<?php echo e(csrf_token()); ?>",
            },
            success: function(data) {
                $('#emp-detail-block').show();
                $('#emp_detail').empty();
                $('#emp_detail').html(`
                <div style="font-size: 18px;line-height: 2.5rem;"><b>Name:</b> ${data.employee.name}</div>
                <div style="font-size: 18px;line-height: 2.5rem;"><b>Gender:</b> ${data.employee.gender}</div>
                <div style="font-size: 18px;line-height: 2.5rem;"><b>Email:</b> ${data.employee.email}</div>
                <div style="font-size: 18px;line-height: 2.5rem;"><b>Age:</b> ${calculateAge(data.employee.dob)}</div>
                <div style="font-size: 18px;line-height: 2.5rem;"><b>Date of Birth:</b> ${formatDate(data.employee.dob)}</div>
                <hr>
                <div style="font-size: 18px;line-height: 2.5rem;"><b>Company DOJ:</b> ${formatDate(data.employee.company_doj)}</div>
                <div style="font-size: 18px;line-height: 2.5rem;"><b>Branch Name:</b> ${data.employee.branch.name}</div>
                <div style="font-size: 18px;line-height: 2.5rem;"><b>Department Name:</b> ${data.employee.department.name}</div>
                <div style="font-size: 18px;line-height: 2.5rem;"><b>Designation:</b> ${data.employee.designation.name}</div>
                <hr>
                <div style="font-size: 18px;line-height: 2.5rem;"><b>Salary:</b> $${data.employee.salary}</div>
                <div style="font-size: 18px;line-height: 2.5rem;"><b>Salary Type:</b> ${data.employee.salary_type.name}</div>
            `);
            }
        });
    }

    // Function to calculate age from dob
    function calculateAge(dob) {
        const today = new Date();
        const birthDate = new Date(dob);
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();

        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }

        return age;
    }

    // Function to format the date
    function formatDate(dateString) {
        const options = {
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        return new Date(dateString).toLocaleDateString(undefined, options);
    }
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\hrmgo\resources\views/pensionOptIn/edit.blade.php ENDPATH**/ ?>