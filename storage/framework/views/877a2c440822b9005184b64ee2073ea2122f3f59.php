<?php $__env->startSection('after_styles'); ?>
    <style media="screen">
        .backpack-profile-form .required::after {
            content: ' *';
            color: red;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php
  $breadcrumbs = [
      trans('backpack::crud.admin') => url(config('backpack.base.route_prefix'), 'dashboard'),
      trans('backpack::base.my_account') => false,
  ];
?>

<?php $__env->startSection('header'); ?>
    <section class="content-header">
        <div class="container-fluid mb-3">
            <h1><?php echo e($stories->title); ?></h1>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
            <h5><?php echo e($stories->published_date); ?></h5>
            <p><?php echo $stories->content; ?></p>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('blank', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\stories\resources\views/story.blade.php ENDPATH**/ ?>