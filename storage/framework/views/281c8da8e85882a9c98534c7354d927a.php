<?php echo e(Form::open(['url' => $url, 'method' => 'post'])); ?>

<div class="modal-body">
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                <?php echo e(Form::label('comment', __('Comment'),['class'=>'col-form-label'])); ?>

                <?php echo e(Form::textarea('comment', '', array('class' => 'form-control', 'required' => 'required', 'rows' => '3'))); ?>

            </div>
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Save')); ?>" class="btn btn-primary">
</div>
<?php echo e(Form::close()); ?>

<?php /**PATH C:\laragon\www\hrmgo\resources\views/eclaim/comment-form.blade.php ENDPATH**/ ?>