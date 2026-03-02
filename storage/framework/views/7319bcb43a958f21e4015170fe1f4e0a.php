
<?php $__env->startSection('title', $teacherMaterial->title_localized); ?>
<?php $__env->startSection('page-title', __('messages.teacher_library')); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl space-y-5">

    
    <a href="<?php echo e(route('admin.teacher-materials.index')); ?>"
       class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-emerald-600 transition mb-1">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        <?php echo e(__('messages.back')); ?>

    </a>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        
        <div class="bg-gradient-to-r from-emerald-600 to-purple-700 px-6 py-5 flex items-start gap-4">
            <div class="w-16 h-16 rounded-xl bg-white/15 flex items-center justify-center text-3xl shrink-0">
                <?php if($teacherMaterial->cover_image): ?>
                    <img src="<?php echo e($teacherMaterial->cover_url); ?>" class="w-full h-full rounded-xl object-cover">
                <?php else: ?>
                    <?php echo e($teacherMaterial->type_icon); ?>

                <?php endif; ?>
            </div>
            <div class="flex-1 min-w-0">
                <h2 class="text-white font-black text-lg leading-tight"><?php echo e($teacherMaterial->title_localized); ?></h2>
                <?php if($teacherMaterial->source): ?>
                <p class="text-emerald-200 text-sm mt-0.5"><?php echo e($teacherMaterial->source); ?></p>
                <?php endif; ?>
                <div class="flex flex-wrap items-center gap-2 mt-2">
                    <?php $tc = \App\Models\TeacherMaterial::typeColor($teacherMaterial->material_type); ?>
                    <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-white/20 text-white uppercase"><?php echo e($teacherMaterial->material_type); ?></span>
                    <?php if($teacherMaterial->status === 'active'): ?>
                        <span class="text-xs px-2 py-0.5 rounded-full bg-emerald-400/30 text-white font-semibold"><?php echo e(__('messages.active')); ?></span>
                    <?php else: ?>
                        <span class="text-xs px-2 py-0.5 rounded-full bg-amber-400/30 text-white font-semibold"><?php echo e(__('messages.draft')); ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <a href="<?php echo e(route('admin.teacher-materials.edit', $teacherMaterial)); ?>"
               class="shrink-0 bg-white/15 hover:bg-white/25 text-white border border-white/20 px-3 py-1.5 rounded-lg text-sm font-medium transition">
                <?php echo e(__('messages.edit')); ?>

            </a>
        </div>

        
        <div class="p-6 grid grid-cols-2 sm:grid-cols-3 gap-4 text-sm border-b border-gray-100">
            <?php $__currentLoopData = [
                [__('messages.subject'),      $teacherMaterial->subject       ?: '—'],
                [__('messages.grade_level'),  $teacherMaterial->grade_level   ?: '—'],
                [__('messages.language'),     ucfirst($teacherMaterial->language)],
                [__('messages.published_year'),$teacherMaterial->published_year ?: '—'],
                [__('messages.views'),         number_format($teacherMaterial->view_count)],
                [__('messages.downloads'),     number_format($teacherMaterial->download_count)],
            ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as [$label, $val]): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div>
                <div class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-0.5"><?php echo e($label); ?></div>
                <div class="text-gray-800 font-medium"><?php echo e($val); ?></div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        
        <?php if($teacherMaterial->description): ?>
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2"><?php echo e(__('messages.description')); ?></h3>
            <p class="text-sm text-gray-700 leading-relaxed"><?php echo e($teacherMaterial->description); ?></p>
        </div>
        <?php endif; ?>

        
        <?php if($teacherMaterial->tags): ?>
        <div class="p-6 border-b border-gray-100">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-2"><?php echo e(__('messages.tags')); ?></h3>
            <div class="flex flex-wrap gap-1.5">
                <?php $__currentLoopData = $teacherMaterial->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <span class="text-xs bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-0.5 rounded-full"><?php echo e($tag); ?></span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>

        
        <div class="p-6 flex flex-wrap gap-3">
            <?php if($teacherMaterial->external_url): ?>
            <a href="<?php echo e(route('admin.teacher-materials.download', $teacherMaterial)); ?>" target="_blank"
               class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <?php echo e(__('messages.open_external')); ?>

            </a>
            <?php elseif($teacherMaterial->file_path): ?>
            <a href="<?php echo e(route('admin.teacher-materials.download', $teacherMaterial)); ?>"
               class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg text-sm font-semibold transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                <?php echo e(__('messages.download')); ?>

            </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\lms\resources\views/admin/teacher-materials/show.blade.php ENDPATH**/ ?>