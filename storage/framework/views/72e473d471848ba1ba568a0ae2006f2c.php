<?php echo e(Form::open(['url' => 'eclaim', 'method' => 'post', 'enctype' => 'multipart/form-data'])); ?>

<div class="modal-body">

    <div class="row">
        
        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                <?php echo e(Form::label('type_id', __('Eclaim Type'), ['class' => 'form-label'])); ?>

                <div class="form-icon-user">
                    <?php echo e(Form::select('type_id', $eClaimTypes, null, ['class' => 'form-control select2 ', 'required' => 'required', 'placeholder' => __('Select Eclaim Type')])); ?>

                </div>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                <?php echo e(Form::label('amount', __('Amount'), ['class' => 'form-label'])); ?>

                <div class="form-icon-user">
                    <?php echo e(Form::number('amount', null, ['class' => 'form-control', 'required' => 'required', 'placeholder' => __('Enter Amount')])); ?>

                </div>
                <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <span class="invalid-amount" role="alert">
                        <strong class="text-danger"><?php echo e($message); ?></strong>
                    </span>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                <?php echo e(Form::label('receipt', __('Receipt'), ['class' => 'col-form-label'])); ?>

                <div class="choose-files ">
                    <label for="receipt">
                        <div class=" bg-primary receipt "> <i
                                class="ti ti-upload px-1"></i><?php echo e(__('Choose file here')); ?>

                        </div>
                        <input style="margin-top: -50px" type="file" class="form-control file" name="receipt"  onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                        <img id="blah" class="mt-3"  width="100" src="" />
                    </label>
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-md-12 col-sm-12">
            <div class="form-group">
                <?php echo e(Form::label('description', __('Description'),['class'=>'col-form-label'])); ?>

                <?php echo e(Form::textarea('description', '', array('class' => 'form-control', 'rows' => '3'))); ?>

            </div>
        </div>

    </div>
</div>
<div class="modal-footer">
    <input type="button" value="Cancel" class="btn btn-light" data-bs-dismiss="modal">
    <input type="submit" value="<?php echo e(__('Create')); ?>" class="btn btn-primary">
</div>
<?php echo e(Form::close()); ?>

<?php /**PATH C:\laragon\www\hrmgo\resources\views/eclaim/create.blade.php ENDPATH**/ ?>