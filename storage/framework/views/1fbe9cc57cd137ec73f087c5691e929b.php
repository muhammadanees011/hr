<div class="card sticky-top" style="top:30px">
    <div class="list-group list-group-flush" id="useradd-sidenav">

        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Payslip Type')): ?>
            <a href="<?php echo e(route('bonus.index')); ?>"
                class="list-group-item list-group-item-action border-0 <?php echo e(request()->is('bonus*') ? 'active' : ''); ?>"><?php echo e(__('Bonus Type')); ?>

                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
            </a>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Payslip Type')): ?>
            <a href="<?php echo e(route('bonus.index')); ?>"
                class="list-group-item list-group-item-action border-0 <?php echo e(request()->is('taxrules*') ? 'active' : ''); ?>"><?php echo e(__('Tax Rules')); ?>

                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
            </a>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Payslip Type')): ?>
            <a href="<?php echo e(route('bonus.index')); ?>"
                class="list-group-item list-group-item-action border-0 <?php echo e(request()->is('providentfundspolicy*') ? 'active' : ''); ?>"><?php echo e(__('Provident Funds Policy')); ?>

                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
            </a>
        <?php endif; ?>
        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Payslip Type')): ?>
            <a href="<?php echo e(route('bonus.index')); ?>"
                class="list-group-item list-group-item-action border-0 <?php echo e(request()->is('overtimepolicy*') ? 'active' : ''); ?>"><?php echo e(__('OverTime Policy')); ?>

                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
            </a>
        <?php endif; ?>
    </div>
</div>
<?php /**PATH C:\xampp\htdocs\HRMGO\resources\views/layouts/payroll_setup.blade.php ENDPATH**/ ?>