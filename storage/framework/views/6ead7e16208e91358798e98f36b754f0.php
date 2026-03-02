

<?php $__env->startSection('title', __('messages.home')); ?>

<?php $__env->startSection('content'); ?>

<section class="bg-gradient-to-br from-emerald-800 to-emerald-600 text-white py-20">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h1 class="text-4xl md:text-5xl font-bold mb-4"><?php echo e(school_name()); ?></h1>
        <p class="text-emerald-100 text-lg mb-8 max-w-2xl mx-auto"><?php echo e(__('messages.hero_subtitle')); ?></p>
        <div class="flex flex-wrap justify-center gap-4">
            <?php if(auth()->guard()->guest()): ?>
                <a href="<?php echo e(route('register')); ?>" class="bg-white text-emerald-800 px-8 py-3 rounded-full font-bold hover:bg-emerald-50 transition shadow-lg"><?php echo e(__('messages.get_started')); ?></a>
                <a href="<?php echo e(route('login')); ?>" class="border-2 border-white text-white px-8 py-3 rounded-full font-semibold hover:bg-white hover:text-emerald-800 transition"><?php echo e(__('messages.login')); ?></a>
            <?php else: ?>
                <?php if (\Illuminate\Support\Facades\Blade::check('hasrole', 'admin|teacher')): ?>
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="bg-white text-emerald-800 px-8 py-3 rounded-full font-bold hover:bg-emerald-50 transition shadow-lg"><?php echo e(__('messages.go_to_dashboard')); ?></a>
                <?php else: ?>
                    <a href="<?php echo e(route('student.dashboard')); ?>" class="bg-white text-emerald-800 px-8 py-3 rounded-full font-bold hover:bg-emerald-50 transition shadow-lg"><?php echo e(__('messages.go_to_dashboard')); ?></a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</section>


<section class="bg-white border-b border-gray-100 py-6">
    <div class="max-w-7xl mx-auto px-4 grid grid-cols-3 md:grid-cols-6 gap-6 text-center">
        <div>
            <div class="text-2xl font-bold text-emerald-700">500+</div>
            <div class="text-gray-500 text-sm"><?php echo e(__('messages.students')); ?></div>
        </div>
        <div>
            <div class="text-2xl font-bold text-emerald-700">50+</div>
            <div class="text-gray-500 text-sm"><?php echo e(__('messages.courses')); ?></div>
        </div>
        <div>
            <div class="text-2xl font-bold text-emerald-700">200+</div>
            <div class="text-gray-500 text-sm"><?php echo e(__('messages.books')); ?></div>
        </div>
        <div>
            <div class="text-2xl font-bold text-emerald-700">20+</div>
            <div class="text-gray-500 text-sm"><?php echo e(__('messages.teachers')); ?></div>
        </div>
        <div>
            <div class="text-2xl font-bold text-emerald-700">1000+</div>
            <div class="text-gray-500 text-sm"><?php echo e(__('messages.certificates')); ?></div>
        </div>
        <div>
            <div class="text-2xl font-bold text-emerald-700">100%</div>
            <div class="text-gray-500 text-sm"><?php echo e(__('messages.online')); ?></div>
        </div>
    </div>
</section>


<section class="max-w-7xl mx-auto px-4 py-14">
    <div class="flex items-center justify-between mb-8">
        <h2 class="text-2xl font-bold text-gray-800"><?php echo e(__('messages.featured_courses')); ?></h2>
        <?php if(auth()->guard()->check()): ?>
            <a href="<?php echo e(auth()->user()->hasRole('student') ? route('student.courses.index') : route('admin.courses.index')); ?>" class="text-emerald-700 hover:underline text-sm font-medium"><?php echo e(__('messages.view_all')); ?> →</a>
        <?php endif; ?>
    </div>
    <?php if($featuredCourses->isEmpty()): ?>
        <div class="text-center py-10 text-gray-400"><?php echo e(__('messages.no_courses_yet')); ?></div>
    <?php else: ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php $__currentLoopData = $featuredCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition group">
                <div class="h-44 bg-gradient-to-br from-emerald-100 to-emerald-200 relative overflow-hidden">
                    <?php if($course->thumbnail): ?>
                        <img src="<?php echo e($course->thumbnail_url); ?>" alt="" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                    <?php else: ?>
                        <div class="flex items-center justify-center h-full">
                            <svg class="w-14 h-14 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                    <?php endif; ?>
                    <span class="absolute top-3 <?php echo e(app()->getLocale()==='ar' ? 'left-3' : 'right-3'); ?> bg-emerald-600 text-white text-xs px-2 py-1 rounded-full"><?php echo e(__('messages.level_'.$course->level)); ?></span>
                </div>
                <div class="p-5">
                    <h3 class="font-semibold text-gray-800 mb-1 line-clamp-2"><?php echo e($course->title_localized); ?></h3>
                    <p class="text-gray-500 text-sm mb-3 line-clamp-2"><?php echo e(Str::limit(strip_tags($course->description), 80)); ?></p>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-6 h-6 bg-emerald-100 rounded-full flex items-center justify-center text-xs font-bold text-emerald-700"><?php echo e(strtoupper(substr($course->teacher->name ?? 'T', 0, 1))); ?></div>
                            <span class="text-xs text-gray-500"><?php echo e($course->teacher->name ?? '-'); ?></span>
                        </div>
                        <span class="text-xs text-gray-400"><?php echo e($course->lessons_count ?? 0); ?> <?php echo e(__('messages.lessons')); ?></span>
                    </div>
                </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</section>


<section class="bg-gray-50 py-14">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-2xl font-bold text-gray-800"><?php echo e(__('messages.latest_books')); ?></h2>
            <?php if(auth()->guard()->check()): ?>
                <a href="<?php echo e(auth()->user()->hasRole('student') ? route('student.library.index') : route('admin.books.index')); ?>" class="text-emerald-700 hover:underline text-sm font-medium"><?php echo e(__('messages.view_all')); ?> →</a>
            <?php endif; ?>
        </div>
        <?php if($latestBooks->isEmpty()): ?>
            <div class="text-center py-10 text-gray-400"><?php echo e(__('messages.no_books_yet')); ?></div>
        <?php else: ?>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                <?php $__currentLoopData = $latestBooks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $book): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                    <div class="h-36 bg-gradient-to-br from-amber-100 to-amber-200 flex items-center justify-center">
                        <?php if($book->cover_image): ?>
                            <img src="<?php echo e($book->cover_url); ?>" alt="" class="h-full w-full object-cover">
                        <?php else: ?>
                            <svg class="w-10 h-10 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        <?php endif; ?>
                    </div>
                    <div class="p-3">
                        <p class="text-sm font-medium text-gray-800 line-clamp-2"><?php echo e($book->title_localized); ?></p>
                        <p class="text-xs text-gray-400 mt-0.5"><?php echo e($book->author); ?></p>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        <?php endif; ?>
    </div>
</section>


<section class="max-w-7xl mx-auto px-4 py-14">
    <h2 class="text-2xl font-bold text-gray-800 text-center mb-12"><?php echo e(__('messages.why_itfa')); ?></h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <?php $__currentLoopData = [
            ['icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'title' => 'feature_courses_title', 'desc' => 'feature_courses_desc'],
            ['icon' => 'M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z', 'title' => 'feature_library_title', 'desc' => 'feature_library_desc'],
            ['icon' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z', 'title' => 'feature_certs_title', 'desc' => 'feature_certs_desc'],
        ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="text-center">
            <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="<?php echo e($f['icon']); ?>"/></svg>
            </div>
            <h3 class="font-bold text-gray-800 mb-2"><?php echo e(__('messages.'.$f['title'])); ?></h3>
            <p class="text-gray-500 text-sm"><?php echo e(__('messages.'.$f['desc'])); ?></p>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.lms', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\lms\resources\views/home.blade.php ENDPATH**/ ?>