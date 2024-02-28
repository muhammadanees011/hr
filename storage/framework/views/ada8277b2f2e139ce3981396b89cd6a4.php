

<?php $__env->startSection('page-title'); ?>
   <?php echo e(__('Manage Eclaimss')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Eclaim')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('action-button'); ?>
   <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Create Eclaim')): ?>
        <a href="#" data-url="<?php echo e(route('eclaim.create')); ?>" data-ajax-popup="true"
            data-title="<?php echo e(__('Request A New Eclaimss')); ?>" data-bs-toggle="tooltip" title="" class="btn btn-sm btn-primary"
            data-bs-original-title="<?php echo e(__('Create')); ?>">
            <i class="ti ti-plus"></i>
        </a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
        <div class="col-12">
            <div class="card">
                <div class="card-body table-border-style">

                    <div class="table-responsive">
                    <table class="table" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <?php if(\Auth::user()->type !="employee"): ?>
                                    <th><?php echo e(__('Employee ID')); ?></th>
                                    <th><?php echo e(__('Name')); ?></th>
                                <?php endif; ?>
                                <th><?php echo e(__('Eclaim Type')); ?></th>
                                <th><?php echo e(__('Amount')); ?></th>
                                <th><?php echo e(__('Description')); ?></th>
                                <th><?php echo e(__('Status')); ?></th>
                                <th><?php echo e(__('Requested Date')); ?></th>
                                <th width="200px"><?php echo e(__('Action')); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $eclaims; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $eclaim): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <?php if(\Auth::user()->type !="employee"): ?>
                                        <td>
                                            <a href="#" class="btn btn-outline-primary"><?php echo e(\Auth::user()->employeeIdFormat($eclaim->employee_id)); ?></a>
                                        </td>
                                        <th><?php echo e($eclaim->employee->name); ?></th>
                                    <?php endif; ?>
                                    <td><?php echo e($eclaim->claimType->title); ?></td>
                                    <td><?php echo e(env('CURRENCY_SYMBOL') ?? '£'); ?><?php echo e(number_format($eclaim->amount, 2)); ?></td>
                                    <td><?php echo e($eclaim->description); ?></td>
                                    <td>
                                        <?php if($eclaim->status=="pending"): ?>
                                            <button class="btn bg-warning btn-sm"><?php echo e(ucfirst($eclaim->status)); ?></button>
                                        <?php elseif($eclaim->status=="approved by HR"): ?>
                                            <button class="btn bg-info btn-sm"><?php echo e(ucfirst($eclaim->status)); ?></button>
                                        <?php elseif($eclaim->status=="approved"): ?>
                                            <button class="btn bg-success btn-sm"><?php echo e(ucfirst($eclaim->status)); ?></button>
                                        <?php else: ?>
                                            <button class="btn bg-danger btn-sm"><?php echo e(ucfirst($eclaim->status)); ?></button>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e(\Auth::user()->dateFormat($eclaim->created_at)); ?></td>
                                    <td class="Action">
                                        <span>
                                            <?php if(\Auth::user()->type == 'employee' && $eclaim->status=="pending"): ?>
                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Edit Eclaim')): ?>
                                                    <div class="action-btn bg-info ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center"
                                                            data-url="<?php echo e(URL::to('eclaim/' . $eclaim->id . '/edit')); ?>"
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                            data-title="<?php echo e(__('Edit Eclaim')); ?>"
                                                            data-bs-original-title="<?php echo e(__('Edit')); ?>">
                                                            <i class="ti ti-pencil text-white"></i>
                                                        </a>
                                                    </div>
                                                <?php endif; ?>

                                                <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Delete Eclaim')): ?>
                                                    <div class="action-btn bg-danger ms-2">
                                                        <?php echo Form::open(['method' => 'DELETE', 'route' => ['eclaim.destroy', $eclaim->id], 'id' => 'delete-form-' . $eclaim->id]); ?>

                                                        <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                            data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                            aria-label="Delete"><i
                                                                class="ti ti-trash text-white text-white"></i></a>
                                                        </form>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endif; ?>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Eclaim')): ?>
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm bg-info  align-items-center"
                                                    data-url="<?php echo e(URL::to('eclaim/showReceipt/' . $eclaim->id )); ?>"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                        data-title="<?php echo e(__('Eclaim Receipt')); ?>"
                                                        data-bs-original-title="<?php echo e(__('View Receipt')); ?>">
                                                        <i class="ti ti-eye text-white"></i>
                                                    </a>
                                                </div>

                                                <?php if(\Auth::user()->type=="hr" || \Auth::user()->type=="company"): ?>
                                                    <div class="action-btn bg-danger ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm bg-danger  align-items-center"
                                                            data-url="<?php echo e(URL::to('eclaim/' . $eclaim->id . '/reject')); ?>"
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                            data-title="<?php echo e(__('Reject Eclaim')); ?>"
                                                            data-bs-original-title="<?php echo e(__('Reject')); ?>">
                                                            <i class="ti ti-trash-off text-white"></i>
                                                        </a>
                                                    </div>
                                                    <div class="action-btn bg-success ms-2">
                                                        <a href="#" class="mx-3 btn btn-sm bg-success  align-items-center"
                                                            data-url="<?php echo e(URL::to('eclaim/' . $eclaim->id . '/approve')); ?>"
                                                            data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip" title=""
                                                            data-title="<?php echo e(__('Eclaim Approval')); ?>"
                                                            data-bs-original-title="<?php echo e(__('Approve')); ?>">
                                                            <i class="ti ti-check text-white"></i>
                                                        </a>
                                                    </div>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Manage Eclaim')): ?>
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm bg-info  align-items-center"
                                                        data-url="<?php echo e(URL::to('eclaim/showHistory/' . $eclaim->id )); ?>"
                                                        data-ajax-popup="true" data-size="lg" data-bs-toggle="tooltip" title=""
                                                        data-title="<?php echo e(__('Eclaim History')); ?>"
                                                        data-bs-original-title="<?php echo e(__('View History')); ?>">
                                                        <i class="ti ti-eye text-white"></i>
                                                    </a>
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


<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\hrmgo\resources\views/eclaim/index.blade.php ENDPATH**/ ?>