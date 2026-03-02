<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" dir="<?php echo e(app()->getLocale() === 'ar' ? 'rtl' : 'ltr'); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e(config('app.name', school_name())); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <style>
        [dir="rtl"] { font-family: 'Noto Kufi Arabic', sans-serif; }
        [dir="ltr"] { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="antialiased">

<?php
    $schoolLogo     = setting('school_logo');
    $shortName      = setting('school_short_name', 'S');
    $schoolTagline  = app()->getLocale() === 'ar'
                          ? setting('school_tagline_ar', __('messages.lms'))
                          : setting('school_tagline', __('messages.lms'));
?>

<div class="min-h-screen flex">

    
    <div class="hidden lg:flex lg:w-[44%] xl:w-[40%] flex-col relative overflow-hidden"
         style="background: linear-gradient(160deg, #0d1b2e 0%, #0f2341 45%, #064e3b 100%);">

        
        <div class="absolute -top-28 -start-28 w-96 h-96 rounded-full pointer-events-none" style="background:rgba(52,211,153,0.07)"></div>
        <div class="absolute bottom-0 end-0 w-[28rem] h-[28rem] rounded-full pointer-events-none" style="background:rgba(16,185,129,0.05)"></div>
        <div class="absolute top-1/2 -start-20 w-56 h-56 rounded-full pointer-events-none" style="background:rgba(6,182,212,0.05)"></div>

        <div class="relative flex flex-col flex-1 px-10 py-12">

            
            <div class="flex items-center gap-4 mb-14">
                <?php if($schoolLogo): ?>
                    <img src="<?php echo e(Storage::disk('public')->url($schoolLogo)); ?>"
                         alt="logo"
                         class="h-14 w-14 rounded-2xl object-contain shrink-0"
                         style="background:rgba(255,255,255,0.1); padding:6px;">
                <?php else: ?>
                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-white font-black text-lg shrink-0"
                         style="background:linear-gradient(135deg,#34d399 0%,#059669 60%,#047857 100%);box-shadow:0 6px 20px rgba(16,185,129,0.4);">
                        <?php echo e(strtoupper(substr($shortName, 0, 2))); ?>

                    </div>
                <?php endif; ?>
                <div>
                    <div class="text-white font-bold text-[17px] leading-snug"><?php echo e(school_name()); ?></div>
                    <div class="text-emerald-400 text-sm mt-0.5"><?php echo e($schoolTagline); ?></div>
                </div>
            </div>

            
            <div class="flex-1 flex flex-col justify-center">
                <h1 class="text-white text-3xl xl:text-[2.1rem] font-black leading-tight mb-4">
                    <?php echo __('messages.auth_hero_title'); ?>

                </h1>
                <p class="text-slate-400 text-[0.9375rem] leading-relaxed mb-10">
                    <?php echo e(__('messages.auth_hero_subtitle')); ?>

                </p>

                
                <?php $__currentLoopData = [
                    ['clr'=>'#34d399','path'=>'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253','lbl'=>'messages.courses'],
                    ['clr'=>'#fbbf24','path'=>'M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z','lbl'=>'messages.e_library'],
                    ['clr'=>'#a78bfa','path'=>'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z','lbl'=>'messages.quizzes'],
                    ['clr'=>'#facc15','path'=>'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z','lbl'=>'messages.certificates'],
                ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0"
                         style="background:<?php echo e($f['clr']); ?>22">
                        <svg class="w-4 h-4" style="color:<?php echo e($f['clr']); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo e($f['path']); ?>"/>
                        </svg>
                    </div>
                    <span class="text-slate-300 text-sm font-medium"><?php echo e(__($f['lbl'])); ?></span>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>

            
            <div class="flex items-center gap-2 mt-8">
                <a href="<?php echo e(route('locale.switch','en')); ?>"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition
                          <?php echo e(app()->getLocale()==='en' ? 'bg-emerald-500 text-white shadow-sm' : 'text-slate-400 hover:text-white hover:bg-white/10'); ?>">
                    EN
                </a>
                <a href="<?php echo e(route('locale.switch','ar')); ?>"
                   class="px-3 py-1.5 rounded-lg text-xs font-semibold transition
                          <?php echo e(app()->getLocale()==='ar' ? 'bg-emerald-500 text-white shadow-sm' : 'text-slate-400 hover:text-white hover:bg-white/10'); ?>">
                    عربي
                </a>
            </div>
        </div>
    </div>

    
    <div class="flex-1 flex flex-col min-h-screen"
         style="background:linear-gradient(135deg,#f0fdf4 0%,#ecfeff 50%,#eef2ff 100%);">

        
        <div class="lg:hidden flex items-center justify-between px-5 py-4 bg-white shadow-sm border-b border-gray-100">
            <div class="flex items-center gap-3">
                <?php if($schoolLogo): ?>
                    <img src="<?php echo e(Storage::disk('public')->url($schoolLogo)); ?>"
                         alt="logo" class="h-8 w-8 object-contain rounded-lg">
                <?php else: ?>
                    <div class="w-8 h-8 rounded-lg flex items-center justify-center text-white font-black text-xs shrink-0"
                         style="background:linear-gradient(135deg,#34d399,#059669)">
                        <?php echo e(strtoupper(substr($shortName, 0, 2))); ?>

                    </div>
                <?php endif; ?>
                <span class="font-bold text-gray-800 text-sm truncate max-w-[180px]"><?php echo e(school_name()); ?></span>
            </div>
            <div class="flex gap-1">
                <a href="<?php echo e(route('locale.switch','en')); ?>"
                   class="px-2 py-1 rounded-md text-xs font-semibold transition
                          <?php echo e(app()->getLocale()==='en' ? 'bg-emerald-500 text-white' : 'text-gray-400 hover:text-gray-700'); ?>">EN</a>
                <a href="<?php echo e(route('locale.switch','ar')); ?>"
                   class="px-2 py-1 rounded-md text-xs font-semibold transition
                          <?php echo e(app()->getLocale()==='ar' ? 'bg-emerald-500 text-white' : 'text-gray-400 hover:text-gray-700'); ?>">عربي</a>
            </div>
        </div>

        
        <div class="flex-1 flex items-center justify-center px-5 py-10">
            <div class="w-full max-w-md">
                <?php echo e($slot); ?>

            </div>
        </div>

        
        <div class="text-center pb-6 text-xs text-gray-400 px-4">
            &copy; <?php echo e(date('Y')); ?> <?php echo e(school_name()); ?> &mdash; <?php echo e($schoolTagline); ?>

        </div>
    </div>
</div>

</body>
</html>
<?php /**PATH C:\xampp\htdocs\lms\resources\views/layouts/guest.blade.php ENDPATH**/ ?>