

<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Manage Retirement')); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Retirement')); ?></li>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('action-button'); ?>
    <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Create Retirement')): ?>
        <a href="#" data-url="<?php echo e(route('retirement.create')); ?>" data-ajax-popup="true"
            data-title="<?php echo e(__('Create New Retirement')); ?>" data-size="lg" data-bs-toggle="tooltip" title=""
            class="btn btn-sm btn-primary" data-bs-original-title="<?php echo e(__('Create')); ?>">
            <i class="ti ti-plus"></i>
        </a>
    <?php endif; ?>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header card-body table-border-style">
                
                <div class="table-responsive">
                    <table class="table" id="pc-dt-simple">
                        <thead>
                            <tr>
                                <?php if(\Spatie\Permission\PermissionServiceProvider::bladeMethodWrapper('hasRole', 'company')): ?>
                                    <th><?php echo e(__('Employee Name')); ?></th>
                                <?php endif; ?>
                                <th><?php echo e(__('Retirement Type')); ?></th>
                                <th><?php echo e(__('Notice Date')); ?></th>
                                <th><?php echo e(__('Retirement Date')); ?></th>
                                <th><?php echo e(__('Description')); ?></th>
                                <th><?php echo e(__('Exit Stage')); ?></th>
                                <?php if(Gate::check('Edit Termination') || Gate::check('Delete Termination')): ?>
                                    <th width="200px"><?php echo e(__('Action')); ?></th>
                                <?php endif; ?>
                            </tr>
                        </thead>
                        <tbody>
                          

                            <?php $__currentLoopData = $retirements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $retirement): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <?php if(\Spatie\Permission\PermissionServiceProvider::bladeMethodWrapper('hasRole', 'company')): ?>
                                <td><?php echo e(!empty($retirement->employee_id) ? $retirement->employee->name : ''); ?></td>
                            <?php endif; ?>

                            <td><?php echo e(!empty($retirement->retirement_type) ? $retirement->retirementType->name : ''); ?>

                            </td>
                            <td><?php echo e(\Auth::user()->dateFormat($retirement->notice_date)); ?></td>
                            <td><?php echo e(\Auth::user()->dateFormat($retirement->retirement_date)); ?></td>
                            <td>
                                <a href="#" class="action-item" data-url="<?php echo e(route('retirement.description',$retirement->id)); ?>" data-ajax-popup="true" data-bs-toggle="tooltip" title="<?php echo e(__('Desciption')); ?>" data-title="<?php echo e(__('Desciption')); ?>"><i class="icon_desc fa fa-comment"></i></a>
                            </td>
                            <td><?php echo e($retirement->exitProcedure ? $retirement->exitProcedure->name :'Waiting For Exit Interview'); ?></td>
                                <td class="Action">
                                    <?php if(Gate::check('Edit Termination') || Gate::check('Delete Termination')): ?>
                                        <span>
                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Edit Retirement')): ?>
                                                <div class="action-btn bg-info ms-2">
                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center" data-size="lg"
                                                        data-url="<?php echo e(URL::to('retirement/' . $retirement->id . '/edit')); ?>"
                                                        data-ajax-popup="true" data-size="md" data-bs-toggle="tooltip"
                                                        title="" data-title="<?php echo e(__('Edit Retirement')); ?>"
                                                        data-bs-original-title="<?php echo e(__('Edit')); ?>">
                                                        <i class="ti ti-pencil text-white"></i>
                                                    </a>
                                                </div>
                                            <?php endif; ?>

                                            <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('Delete Retirement')): ?>
                                                <div class="action-btn bg-danger ms-2">
                                                    <?php echo Form::open(['method' => 'DELETE', 'route' => ['retirement.destroy', $retirement->id], 'id' => 'delete-form-' . $retirement->id]); ?>

                                                    <a href="#" class="mx-3 btn btn-sm  align-items-center bs-pass-para"
                                                        data-bs-toggle="tooltip" title="" data-bs-original-title="Delete"
                                                        aria-label="Delete"><i
                                                            class="ti ti-trash text-white text-white"></i></a>
                                                    </form>
                                                </div>
                                            <?php endif; ?>
                                        </span>
                                    <?php endif; ?>
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

<?php echo $__env->make('layouts.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\HRMGO\resources\views/retirement/index.blade.php ENDPATH**/ ?>