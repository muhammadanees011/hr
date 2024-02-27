

<?php $__env->startSection('page-title'); ?>
<?php echo e(__("Manage Pension Opt-ins")); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
<li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__("Home")); ?></a></li>
<li class="breadcrumb-item"><?php echo e(__("Opt-ins")); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('action-button'); ?>
<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Create Pension OptIn')): ?>
<a href="<?php echo e(route('pension-opt-ins.create')); ?>" data-title="<?php echo e(__('Create Opt-in')); ?>" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary" data-bs-original-title="<?php echo e(__('Create')); ?>">
    <i class="ti ti-plus"></i>
</a>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="col-12">
    <div class="card">
        <div class="card-body table-border-style">
            <div class="table-responsive">
                <table class="table datatable">
                    <thead>
                        <tr>
                            <th><?php echo e(__('Employee')); ?></th>
                            <th><?php echo e(__('Pension Scheme')); ?></th>
                            <th><?php echo e(__('Date')); ?></th>
                            <th><?php echo e(__('Status')); ?></th>
                            <th width="200px"><?php echo e(__('Action')); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $optIns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $optIn): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><?php echo e($optIn->employee->name); ?></td>
                            <td><?php echo e($optIn->pensionScheme->scheme_name); ?></td>
                            <td><?php echo e(\Auth::user()->dateFormat($optIn->date)); ?></td>
                            <td><?php echo e($optIn->status); ?></td>
                            <td class="Action">
                                <span>
                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Edit Pension OptIn')): ?>
                                    <div class="action-btn bg-info ms-2">
                                        <a href="<?php echo e(route('pension-opt-ins.edit', $optIn->id)); ?>" class="mx-3 btn btn-sm  align-items-center" data-bs-toggle="tooltip" title="" data-bs-original-title="<?php echo e(__('Edit')); ?>">
                                            <i class="ti ti-pencil text-white"></i>
                                        </a>
                                    </div>
                                    <?php endif; ?>

                                    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Delete Pension OptIn')): ?>
                                    <div class="action-btn bg-danger ms-2">
                                        <?php echo Form::open(['method' => 'DELETE', 'route' => ['pension-opt-ins.destroy', $optIn->id], 'id' => 'delete-form-' . $optIn->id]); ?>

                                        <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para" data-bs-toggle="tooltip" title="" data-bs-original-title="Delete" aria-label="Delete"><i class="ti ti-trash text-white text-white"></i></a>
                                        </form>
                                    </div>
                                    <?php endif; ?>
                                </span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\hrmgo\resources\views/pensionOptIn/index.blade.php ENDPATH**/ ?>