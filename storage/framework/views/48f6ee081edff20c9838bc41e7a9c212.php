<?php
    $chatgpt = Utility::getValByName('enable_chatgpt');
?>

<?php echo e(Form::open(['url' => 'retirement', 'method' => 'post'])); ?>

<div class="modal-body">

    <?php if($chatgpt == 'on'): ?>
    <div class="card-footer text-end">
        <a href="#" class="btn btn-sm btn-primary" data-size="medium" data-ajax-popup-over="true" data-url="<?php echo e(route('generate', ['termination'])); ?>"
            data-bs-toggle="tooltip" data-bs-placement="top" title="<?php echo e(__('Generate')); ?>"
            data-title="<?php echo e(__('Generate Content With AI')); ?>">
            <i class="fas fa-robot"></i><?php echo e(__(' Generate With AI')); ?>

        </a>
    </div>
    <?php endif; ?>

    <div class="row">
        <div class="form-group col-lg-6 col-md-6">
            <?php echo e(Form::label('employee_id', __('Employee'), ['class' => 'col-form-label'])); ?>

            <?php echo e(Form::select('employee_id', $employees, null, ['class' => 'form-control select2', 'required' => 'required'])); ?>

        </div>
        <div class="form-group col-lg-6 col-md-6">
            <?php echo e(Form::label('retirement_type', __('Retirement Type'), ['class' => 'col-form-label'])); ?>

            <?php echo e(Form::select('retirement_type', $retirementtypes, null, ['class' => 'form-control select2', 'required' => 'required'])); ?>

        </div>
        <div class="form-group col-lg-6 col-md-6">
            <?php echo e(Form::label('notice_date', __('Notice Date'), ['class' => 'col-form-label'])); ?>

            <?php echo e(Form::text('notice_date', null, ['class' => 'form-control d_week current_date', 'autocomplete' => 'off' ,'required' => 'required'])); ?>

        </div>
        <div class="form-group col-lg-6 col-md-6">
            <?php echo e(Form::label('retirement_date', __('Retirement Date'), ['class' => 'col-form-label'])); ?>

            <?php echo e(Form::text('retirement_date', null, ['class' => 'form-control d_week current_date', 'autocomplete' => 'off' ,'required' => 'required'])); ?>

        </div>
        <div class="form-group  col-lg-6 col-md-6">
            <?php echo e(Form::label('exitprocedure_id', __('Exit Stage'), ['class' => 'col-form-label'])); ?>

            <?php echo e(Form::select('exitprocedure_id', $exitprocedures, null, ['class' => 'form-control select2', 'required' => 'required'])); ?>

        </div>
        <div class="form-group  col-lg-12">
            <?php echo e(Form::label('description', __('Description'), ['class' => 'col-form-label'])); ?>

            <?php echo e(Form::textarea('description', null, ['class' => 'form-control', 'placeholder' => __('Enter Description'),'rows' => '3' ,'required' => 'required'])); ?>

        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn btn-primary">
</div>

<?php echo e(Form::close()); ?>


<script>
    $(document).ready(function() {
        var now = new Date();
        var month = (now.getMonth() + 1);
        var day = now.getDate();
        if (month < 10) month = "0" + month;
        if (day < 10) day = "0" + day;
        var today = now.getFullYear() + '-' + month + '-' + day;
        $('.current_date').val(today);
    });
</script><?php /**PATH C:\xampp\htdocs\HRMGO\resources\views/retirement/create.blade.php ENDPATH**/ ?>