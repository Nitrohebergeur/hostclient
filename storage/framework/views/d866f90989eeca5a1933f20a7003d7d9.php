<!DOCTYPE html>
<html lang="fr" x-data="darkMode">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?> - <?php echo e(config('app.name', 'HostClient')); ?></title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700,800,900" rel="stylesheet" />
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="antialiased" :class="{'dark': dark}">
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <?php echo $__env->yieldContent('content'); ?>
    </div>
    
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH /var/www/hostclient/resources/views/layouts/app.blade.php ENDPATH**/ ?>