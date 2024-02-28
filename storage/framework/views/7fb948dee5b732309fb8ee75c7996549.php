<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Retirement Stage')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startPush('css-page'); ?>
    <link href="<?php echo e(asset('public/libs/bootstrap-tagsinput/dist/bootstrap-tagsinput.css')); ?>" rel="stylesheet" />
<?php $__env->stopPush(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('Home')); ?></a></li>
    <li class="breadcrumb-item"><a href="<?php echo e(route('retirement.index')); ?>"><?php echo e(__('Retirement')); ?></a></li>
    <li class="breadcrumb-item"><?php echo e(__('Retirement Stage')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('script-page'); ?>
    <script src="<?php echo e(asset('public/libs/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')); ?>"></script>

    <script>
        var e = $('[data-toggle="tags"]');
        e.length && e.each(function() {
            $(this).tagsinput({
                tagClass: "badge badge-primary"
            })
        });

        $(document).ready(function() {

            /* 1. Visualizing things on Hover - See next part for action on click */
            $('#stars li').on('mouseover', function() {
                var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on

                // Now highlight all the stars that's not after the current hovered star
                $(this).parent().children('li.star').each(function(e) {
                    if (e < onStar) {
                        $(this).addClass('hover');
                    } else {
                        $(this).removeClass('hover');
                    }
                });

            }).on('mouseout', function() {
                $(this).parent().children('li.star').each(function(e) {
                    $(this).removeClass('hover');
                });
            });


            /* 2. Action to perform on click */
            $('#stars li').on('click', function() {

                var onStar = parseInt($(this).data('value'), 10); // The star currently selected
                var stars = $(this).parent().children('li.star');

                for (i = 0; i < stars.length; i++) {
                    $(stars[i]).removeClass('selected');
                }

                for (i = 0; i < onStar; i++) {
                    $(stars[i]).addClass('selected');
                }

                // JUST RESPONSE (Not needed)
                var ratingValue = parseInt($('#stars li.selected').last().data('value'), 10);
                $.ajax({
                    url: '',
                    type: 'POST',
                    data: {
                        rating: ratingValue,
                        "_token": $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {

                    },
                    error: function(data) {
                        data = data.responseJSON;
                        show_toastr('Error', data.error, 'error')
                    }
                });

            });

        });
        $(document).on('change', '.stages', function() {
            var id = $(this).val();
            var schedule_id = $(this).attr('data-scheduleid');

            $.ajax({
                url: "<?php echo e(route('job.application.stage.change')); ?>",
                type: 'POST',
                data: {
                    "stage": id,
                    "schedule_id": schedule_id,
                    "_token": "<?php echo e(csrf_token()); ?>",
                },
                success: function(data) {
                    // show_toastr('Suceess', data.success, 'success');
                    show_toastr('Success', 'The candidate stage successfully changed', 'error');
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                }
            });
        });
    </script>
<?php $__env->stopPush(); ?>
<?php $__env->startSection('content'); ?>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-auto">
                        <h6 class="text-muted"><?php echo e(__('Basic Details')); ?></h6>
                    </div>
                    
                </div>

            </div>
            <div class="card-body">

                <h5 class="h4">
                    <div class="d-flex align-items-center" data-toggle="tooltip" data-placement="right"
                        data-title="2 hrs ago" data-original-title="" title="">
                        <div>
                        </div>
                        <div class="flex-fill ms-3">
                            <p class="text-sm lh-140 mb-0">
                            </p>
                        </div>
                    </div>
                </h5>

                <div class="py-2 my-4 border-top ">
                    <div class="row align-items-center my-3">
                        <?php $__currentLoopData = $stages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="form-check form-check-inline form-group">
                                <input type="radio" id="stage_<?php echo e($stage->id); ?>" name="stage"
                                    data-scheduleid="" value="<?php echo e($stage->id); ?>"
                                    class="form-check-input stages"
                                    >
                                <label class="form check-label"
                                    for="stage_<?php echo e($stage->id); ?>"><?php echo e($stage->title); ?></label>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
              </div>
         </div>
     </div>
</div>

</div>

<?php $__env->stopSection(); ?>




<?php
    $chatgpt = Utility::getValByName('enable_chatgpt');
?>

<?php $__env->startPush('script-page'); ?>
    <script src="<?php echo e(asset('public/libs/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js')); ?>"></script>

    <script>
        var e = $('[data-toggle="tags"]');
        e.length && e.each(function() {
            $(this).tagsinput({
                tagClass: "badge badge-primary"
            })
        });

        $(document).ready(function() {

            /* 1. Visualizing things on Hover - See next part for action on click */
            $('#stars li').on('mouseover', function() {
                var onStar = parseInt($(this).data('value'), 10); // The star currently mouse on

                // Now highlight all the stars that's not after the current hovered star
                $(this).parent().children('li.star').each(function(e) {
                    if (e < onStar) {
                        $(this).addClass('hover');
                    } else {
                        $(this).removeClass('hover');
                    }
                });

            }).on('mouseout', function() {
                $(this).parent().children('li.star').each(function(e) {
                    $(this).removeClass('hover');
                });
            });


            /* 2. Action to perform on click */
            $('#stars li').on('click', function() {

                var onStar = parseInt($(this).data('value'), 10); // The star currently selected
                var stars = $(this).parent().children('li.star');

                for (i = 0; i < stars.length; i++) {
                    $(stars[i]).removeClass('selected');
                }

                for (i = 0; i < onStar; i++) {
                    $(stars[i]).addClass('selected');
                }

                // JUST RESPONSE (Not needed)
                var ratingValue = parseInt($('#stars li.selected').last().data('value'), 10);
                $.ajax({
                    url: '',
                    type: 'POST',
                    data: {
                        rating: ratingValue,
                        "_token": $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {

                    },
                    error: function(data) {
                        data = data.responseJSON;
                        show_toastr('Error', data.error, 'error')
                    }
                });

            });

        });
        $(document).on('change', '.stages', function() {
            var id = $(this).val();
            var schedule_id = $(this).attr('data-scheduleid');

            $.ajax({
                url: "<?php echo e(route('job.application.stage.change')); ?>",
                type: 'POST',
                data: {
                    "stage": id,
                    "schedule_id": schedule_id,
                    "_token": "<?php echo e(csrf_token()); ?>",
                },
                success: function(data) {
                    // show_toastr('Suceess', data.success, 'success');
                    show_toastr('Success', 'The candidate stage successfully changed', 'error');
                    setTimeout(function() {
                        window.location.reload();
                    }, 1000);
                }
            });
        });
    </script>
<?php $__env->stopPush(); ?>

<?php echo e(Form::model($retirement, ['route' => ['retirement.update', $retirement->id], 'method' => 'PUT'])); ?>

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
        <div class="py-2 my-4 border-top ">
            <div class="row align-items-center my-3">
                <?php $__currentLoopData = $stages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stage): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="form-check form-check-inline form-group">
                        <input type="radio" id="stage_<?php echo e($stage->id); ?>" name="stage"
                            data-scheduleid="" value="<?php echo e($stage->id); ?>"
                            class="form-check-input stages"
                            >
                        <label class="form check-label"
                            for="stage_<?php echo e($stage->id); ?>"><?php echo e($stage->title); ?></label>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Update')); ?>" class="btn btn-primary">
</div>

<?php echo e(Form::close()); ?>


<?php /**PATH C:\xampp\htdocs\HRMGO\resources\views/retirement/show.blade.php ENDPATH**/ ?>