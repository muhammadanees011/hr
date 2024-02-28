<?php
$logo = \App\Models\Utility::get_file('uploads/logo/');
$setting = App\Models\Utility::colorset();
$color = !empty($setting['theme_color']) ? $setting['theme_color'] : 'theme-3';
$SITE_RTL = \App\Models\Utility::getValByName('SITE_RTL');
$company_logo_light = Utility::getValByName('company_logo_light');
$company_favicon = Utility::getValByName('company_favicon');

$getseo = App\Models\Utility::getSeoSetting();
$metatitle = isset($getseo['meta_title']) ? $getseo['meta_title'] : '';
$metadesc = isset($getseo['meta_description']) ? $getseo['meta_description'] : '';
$meta_image = \App\Models\Utility::get_file('uploads/meta/');
$meta_logo = isset($getseo['meta_image']) ? $getseo['meta_image'] : '';
$enable_cookie = \App\Models\Utility::getCookieSetting('enable_cookie');

?>

<!DOCTYPE html>

<html lang="en">
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" dir="<?php echo e($SITE_RTL == 'on' ? 'rtl' : ''); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>
        <?php echo e(!empty($companySettings['title_text']) ? $companySettings['title_text']->value : config('app.name', 'HRMGO')); ?>

        - <?php echo e(__('Career')); ?>

    </title>

    <!-- SEO META -->
    <meta name="title" content="<?php echo e($metatitle); ?>">
    <meta name="description" content="<?php echo e($metadesc); ?>">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo e(env('APP_URL')); ?>">
    <meta property="og:title" content="<?php echo e($metatitle); ?>">
    <meta property="og:description" content="<?php echo e($metadesc); ?>">
    <meta property="og:image" content="<?php echo e(isset($meta_logo) && !empty(asset('storage/uploads/meta/' . $meta_logo)) ? asset('storage/uploads/meta/' . $meta_logo) : 'hrmgo.png'); ?>">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo e(env('APP_URL')); ?>">
    <meta property="twitter:title" content="<?php echo e($metatitle); ?>">
    <meta property="twitter:description" content="<?php echo e($metadesc); ?>">
    <meta property="twitter:image" content="<?php echo e(isset($meta_logo) && !empty(asset('storage/uploads/meta/' . $meta_logo)) ? asset('storage/uploads/meta/' . $meta_logo) : 'hrmgo.png'); ?>">


    <link rel="icon" href="<?php echo e($logo . '/' . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon .'?'.time() : 'favicon.png' .'?'.time())); ?>" type="image/x-icon" />
    <link rel="stylesheet" href="<?php echo e(asset('assets/fonts/tabler-icons.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('css/site.css')); ?>" id="stylesheet">
    <?php if(isset($setting['dark_mode']) && $setting['dark_mode'] == 'on'): ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/style-dark.css')); ?>">
    <?php else: ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/style.css')); ?>" id="main-style-link">
    <?php endif; ?>
    <link rel="stylesheet" href="<?php echo e(asset('css/custom.css')); ?>">

    <?php if(isset($setting['dark_mode']) && $setting['dark_mode'] == 'on'): ?>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/custom-dark.css')); ?>">
    <?php endif; ?>

    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
</head>

<body class="<?php echo e($color); ?>">
    <div class="job-wrapper">
        <div class="job-content">
            <nav class="navbar">
                <div class="container">
                    <a class="navbar-brand" href="#">
                        <img src="<?php echo e($logo . '/' . (isset($company_logo_light) && !empty($company_logo_light) ? $company_logo_light .'?'.time() : 'logo-light.png' .'?'.time())); ?>" alt="logo" style="width: 90px">

                    </a>
                </div>
            </nav>
            <section class="job-banner">
                <div class="job-banner-bg">
                    <img src="<?php echo e(asset('/storage/uploads/job/banner.png')); ?>" alt="">
                </div>
                <div class="container">
                    <div class="job-banner-content text-center text-white">
                        <h1 class="text-white mb-3">
                            <?php echo e(__(' We help')); ?> <br> <?php echo e(__('businesses grow')); ?>

                        </h1>
                        <p><?php echo e(__('Work there. Find the dream job you’ve always wanted..')); ?></p>
                        </p>
                    </div>
                </div>
            </section>
            <section class="apply-job-section">
                <div class="container">
                    <div class="apply-job-wrapper bg-light">
                        <div class="section-title text-center">
                            <h2 class="h1 mb-3"> <?php echo e($job->title); ?></h2>
                            <div class="d-flex flex-wrap justify-content-center gap-1 mb-4">
                                <?php $__currentLoopData = explode(',', $job->skill); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="badge rounded p-2 bg-primary"><?php echo e($skill); ?></span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <?php if(!empty($job->branches) ? $job->branches->name : ''): ?>
                            <p> <i class="ti ti-map-pin ms-1"></i>
                                <?php echo e(!empty($job->branches) ? $job->branches->name : ''); ?>

                            </p>
                            <?php endif; ?>

                        </div>
                        <div class="apply-job-form">
                            <h2 class="mb-4"><?php echo e(__('Apply for this job')); ?></h2>
                            <?php echo e(Form::open(['route' => ['job.apply.data', $job->code], 'method' => 'post', 'enctype' => 'multipart/form-data'])); ?>


                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php echo e(Form::label('name', __('Name'), ['class' => 'form-label'])); ?>

                                        <?php echo e(Form::text('name', null, ['class' => 'form-control name', 'required' => 'required'])); ?>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php echo e(Form::label('email', __('Email'), ['class' => 'form-label'])); ?>

                                        <?php echo e(Form::email('email', null, ['class' => 'form-control', 'required' => 'required'])); ?>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php echo e(Form::label('phone', __('Phone'), ['class' => 'form-label'])); ?>

                                        <?php echo e(Form::text('phone', null, ['class' => 'form-control', 'required' => 'required'])); ?>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <?php if(!empty($job->applicant) && in_array('dob', explode(',', $job->applicant))): ?>
                                    <div class="form-group">
                                        <?php echo Form::label('dob', __('Date of Birth'), ['class' => 'form-label']); ?>

                                        <?php echo Form::date('dob', old('dob'), ['class' => 'form-control datepicker w-100', 'required' => 'required']); ?>

                                    </div>
                                    <?php endif; ?>
                                </div>
                                <?php if(!empty($job->applicant) && in_array('gender', explode(',', $job->applicant))): ?>
                                <div class="form-group col-md-6 ">
                                    <?php echo Form::label('gender', __('Gender'), ['class' => 'form-label']); ?>

                                    <div class="d-flex radio-check">
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="g_male" value="Male" name="gender" class="custom-control-input">
                                            <label class="custom-control-label" for="g_male"><?php echo e(__('Male')); ?></label>
                                        </div>
                                        <div class="custom-control custom-radio custom-control-inline">
                                            <input type="radio" id="g_female" value="Female" name="gender" class="custom-control-input">
                                            <label class="custom-control-label" for="g_female"><?php echo e(__('Female')); ?></label>
                                        </div>
                                    </div>
                                </div>
                                <?php endif; ?>

                                <?php if(!empty($job->applicant) && in_array('country', explode(',', $job->applicant))): ?>
                                <div class="form-group col-md-6 ">
                                    <?php echo e(Form::label('country', __('Country'), ['class' => 'form-label'])); ?>

                                    <?php echo e(Form::text('country', null, ['class' => 'form-control', 'required' => 'required'])); ?>

                                </div>
                                <div class="form-group col-md-6 country">
                                    <?php echo e(Form::label('state', __('State'), ['class' => 'form-label'])); ?>

                                    <?php echo e(Form::text('state', null, ['class' => 'form-control', 'required' => 'required'])); ?>

                                </div>
                                <div class="form-group col-md-6 country">
                                    <?php echo e(Form::label('city', __('City'), ['class' => 'form-label'])); ?>

                                    <?php echo e(Form::text('city', null, ['class' => 'form-control', 'required' => 'required'])); ?>

                                </div>
                                <?php endif; ?>

                                <?php if(!empty($job->visibility) && in_array('profile', explode(',', $job->visibility))): ?>
                                <div class="form-group col-md-6 ">
                                    <?php echo e(Form::label('profile', __('Profile'), ['class' => 'col-form-label'])); ?>

                                    <input type="file" class="form-control" name="profile" id="profile" data-filename="profile_create" onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                                    <img id="blah" src="" class="mt-3" width="25%" />
                                    <p class="profile_create"></p>
                                </div>
                                <?php endif; ?>

                                <?php if(!empty($job->visibility) && in_array('resume', explode(',', $job->visibility))): ?>
                                <div class="form-group col-md-6 ">
                                    <?php echo e(Form::label('resume', __('CV / Resume'), ['class' => 'col-form-label'])); ?>

                                    <input type="file" class="form-control" name="resume" id="resume" data-filename="resume_create" onchange="document.getElementById('blah1').src = window.URL.createObjectURL(this.files[0])" required>
                                    <img id="blah1" class="mt-3" src="" width="25%" />
                                    <p class="resume_create"></p>

                                </div>
                                <?php endif; ?>

                                <?php if(!empty($job->visibility) && in_array('letter', explode(',', $job->visibility))): ?>
                                <div class="form-group col-md-12 ">
                                    <?php echo e(Form::label('cover_letter', __('Cover Letter'), ['class' => 'form-label'])); ?>

                                    <?php echo e(Form::textarea('cover_letter', null, ['class' => 'form-control', 'rows' => '3', 'id'=> 'coverLetter'])); ?>

                                    <span id="wordCount"></span>
                                </div>
                                <?php endif; ?>

                                <?php $__currentLoopData = $questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="form-group col-md-12  question question_<?php echo e($question->id); ?>">
                                    <?php echo e(Form::label($question->question, $question->question, ['class' => 'form-label'])); ?>

                                    <input type="text" class="form-control" name="question[<?php echo e($question->question); ?>]" <?php echo e($question->is_required == 'yes' ? 'required' : ''); ?>>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <div class="col-12">
                                    <div class="text-center mt-4">
                                        <button type="submit" class="btn btn-primary"><?php echo e(__('Submit your application')); ?></button>
                                    </div>
                                </div>

                            </div>
                            <?php echo e(Form::close()); ?>

                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 99999">
        <div id="liveToast" class="toast text-white  fade" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body"> </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script src="<?php echo e(asset('assets/js/plugins/popper.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/plugins/perfect-scrollbar.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/plugins/bootstrap.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/plugins/feather.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/plugins/sweetalert2.all.min.js')); ?>"></script>

    <script src="<?php echo e(asset('js/site.core.js')); ?>"></script>
    <script src="<?php echo e(asset('js/site.js')); ?>"></script>
    <script src="<?php echo e(asset('js/demo.js')); ?> "></script>
    <script src="<?php echo e(asset('js/custom.js')); ?>"></script>

    <script>
        $(document).ready(function() {
            // Set the maximum word limit
            var maxWords = 100;

            // Add input event listener to the textarea
            $('#coverLetter').on('input', function() {
                var words = $(this).val().match(/\S+/g) || [];
                var wordCount = words.length;

                // Display word count
                $('#wordCount').text('Word Count: ' + wordCount + '/100');

                // Disable textarea if it exceeds the word limit
                if (wordCount > maxWords) {
                    var truncatedWords = words.slice(0, maxWords);
                    $(this).val(truncatedWords.join(' '));
                    // $('#wordCount').text('Word Count: ' + maxWords);
                }
            });
        });
    </script>



    <?php if($message = Session::get('success')): ?>
    <script>
        show_toastr('<?php echo e('success'); ?>', '<?php echo $message; ?>');
    </script>
    <?php endif; ?>
    <?php if($message = Session::get('error')): ?>
    <script>
        show_toastr('<?php echo e('error'); ?>', '<?php echo $message; ?>');
    </script>
    <?php endif; ?>

    <?php echo $__env->yieldPushContent('custom-scripts'); ?>
    <?php if($enable_cookie['enable_cookie'] == 'on'): ?>
    <?php echo $__env->make('layouts.cookie_consent', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?>

</body>

</html><?php /**PATH C:\wamp64\www\hrmgo\resources\views/job/apply.blade.php ENDPATH**/ ?>