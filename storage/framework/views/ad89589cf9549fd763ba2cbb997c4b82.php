
<?php $__env->startSection('title', __('messages.teacher_library')); ?>
<?php $__env->startSection('page-title', __('messages.teacher_library')); ?>

<?php $__env->startSection('content'); ?>


<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-5">
    <div>
        <h2 class="text-lg font-bold text-gray-800"><?php echo e(__('messages.teacher_library')); ?></h2>
        <p class="text-sm text-gray-500"><?php echo e(__('messages.teacher_library_subtitle')); ?></p>
    </div>
    <a href="<?php echo e(route('admin.teacher-materials.create')); ?>"
       class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm px-5 py-2.5 rounded-xl shadow transition active:scale-95">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
        <?php echo e(__('messages.add_material')); ?>

    </a>
</div>


<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">
    <?php $__currentLoopData = [
        ['label'=>__('messages.total'),'value'=>$stats['total'],'color'=>'text-emerald-600','bg'=>'bg-emerald-50','icon'=>'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
        ['label'=>__('messages.published'),'value'=>$stats['active'],'color'=>'text-emerald-600','bg'=>'bg-emerald-50','icon'=>'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0'],
        ['label'=>__('messages.draft'),'value'=>$stats['draft'],'color'=>'text-amber-600','bg'=>'bg-amber-50','icon'=>'M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586'],
        ['label'=>__('messages.downloads'),'value'=>number_format($stats['downloads']),'color'=>'text-blue-600','bg'=>'bg-blue-50','icon'=>'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4'],
    ]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4 flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl <?php echo e($s['bg']); ?> <?php echo e($s['color']); ?> flex items-center justify-center shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo e($s['icon']); ?>"/></svg>
        </div>
        <div>
            <div class="text-xl font-black <?php echo e($s['color']); ?>"><?php echo e($s['value']); ?></div>
            <div class="text-xs text-gray-500"><?php echo e($s['label']); ?></div>
        </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">

    
    <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
        <form class="flex flex-wrap gap-2" method="GET" action="<?php echo e(route('admin.teacher-materials.index')); ?>">
            <div class="relative">
                <input type="text" name="search" value="<?php echo e(request('search')); ?>"
                       placeholder="<?php echo e(__('messages.search')); ?>…"
                       class="border border-gray-200 bg-white rounded-lg ps-8 pe-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300 w-44">
                <svg class="w-3.5 h-3.5 text-gray-400 absolute start-2.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>

            <select name="material_type" class="border border-gray-200 bg-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                <option value=""><?php echo e(__('messages.all_types')); ?></option>
                <?php $__currentLoopData = \App\Models\TeacherMaterial::allTypes(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($t); ?>" <?php echo e(request('material_type')===$t ? 'selected':''); ?>><?php echo e(strtoupper($t)); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <select name="status" class="border border-gray-200 bg-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                <option value=""><?php echo e(__('messages.all_statuses')); ?></option>
                <option value="active" <?php echo e(request('status')==='active' ? 'selected':''); ?>><?php echo e(__('messages.active')); ?></option>
                <option value="draft"  <?php echo e(request('status')==='draft'  ? 'selected':''); ?>><?php echo e(__('messages.draft')); ?></option>
            </select>

            <?php if($subjects->isNotEmpty()): ?>
            <select name="subject" class="border border-gray-200 bg-white rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                <option value=""><?php echo e(__('messages.all_subjects')); ?></option>
                <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($sub); ?>" <?php echo e(request('subject')===$sub ? 'selected':''); ?>><?php echo e($sub); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php endif; ?>

            <button class="bg-emerald-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-emerald-700 transition"><?php echo e(__('messages.search')); ?></button>

            <?php if(request()->hasAny(['search','material_type','status','subject','grade_level'])): ?>
            <a href="<?php echo e(route('admin.teacher-materials.index')); ?>" class="border border-gray-200 bg-white text-gray-600 px-3 py-2 rounded-lg text-sm hover:bg-gray-100 transition">✕ <?php echo e(__('messages.clear')); ?></a>
            <?php endif; ?>
        </form>
    </div>

    
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="px-5 py-3 text-start"><?php echo e(__('messages.material')); ?></th>
                    <th class="px-5 py-3 text-start hidden md:table-cell"><?php echo e(__('messages.subject')); ?></th>
                    <th class="px-5 py-3 text-start hidden lg:table-cell"><?php echo e(__('messages.grade_level')); ?></th>
                    <th class="px-5 py-3 text-center"><?php echo e(__('messages.type')); ?></th>
                    <th class="px-5 py-3 text-center"><?php echo e(__('messages.status')); ?></th>
                    <th class="px-5 py-3 text-center hidden sm:table-cell"><?php echo e(__('messages.downloads')); ?></th>
                    <th class="px-5 py-3 text-start"><?php echo e(__('messages.actions')); ?></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <?php $__empty_1 = true; $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php $tc = \App\Models\TeacherMaterial::typeColor($mat->material_type); ?>
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-3">
                            
                            <div class="w-10 h-12 rounded-lg overflow-hidden shrink-0 bg-emerald-50 flex items-center justify-center">
                                <?php if($mat->cover_image): ?>
                                    <img src="<?php echo e($mat->cover_url); ?>" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <span class="text-lg"><?php echo e($mat->type_icon); ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="min-w-0">
                                <div class="font-medium text-gray-800 truncate max-w-[220px]"><?php echo e($mat->title_localized); ?></div>
                                <div class="text-xs text-gray-400"><?php echo e($mat->source); ?></div>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-3 text-gray-600 hidden md:table-cell"><?php echo e($mat->subject ?: '—'); ?></td>
                    <td class="px-5 py-3 hidden lg:table-cell">
                        <?php if($mat->grade_level): ?>
                        <span class="text-xs bg-amber-50 text-amber-700 border border-amber-200 px-2 py-0.5 rounded-full"><?php echo e($mat->grade_level); ?></span>
                        <?php else: ?>
                        <span class="text-gray-300">—</span>
                        <?php endif; ?>
                    </td>
                    <td class="px-5 py-3 text-center">
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full uppercase <?php echo e($tc); ?>"><?php echo e($mat->material_type); ?></span>
                    </td>
                    <td class="px-5 py-3 text-center">
                        <?php if($mat->status === 'active'): ?>
                        <span class="text-xs bg-emerald-50 text-emerald-600 border border-emerald-200 px-2 py-0.5 rounded-full font-medium"><?php echo e(__('messages.active')); ?></span>
                        <?php else: ?>
                        <span class="text-xs bg-amber-50 text-amber-600 border border-amber-200 px-2 py-0.5 rounded-full font-medium"><?php echo e(__('messages.draft')); ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="px-5 py-3 text-center hidden sm:table-cell text-gray-500"><?php echo e(number_format($mat->download_count)); ?></td>
                    <td class="px-5 py-3">
                        <div class="flex items-center gap-2">
                            <a href="<?php echo e(route('admin.teacher-materials.show', $mat)); ?>"
                               class="text-xs bg-gray-50 border border-gray-200 text-gray-600 hover:bg-gray-100 px-2.5 py-1 rounded-lg transition">
                                <?php echo e(__('messages.view')); ?>

                            </a>
                            <a href="<?php echo e(route('admin.teacher-materials.edit', $mat)); ?>"
                               class="text-xs bg-emerald-50 border border-emerald-200 text-emerald-600 hover:bg-emerald-100 px-2.5 py-1 rounded-lg transition">
                                <?php echo e(__('messages.edit')); ?>

                            </a>
                            <?php if (\Illuminate\Support\Facades\Blade::check('hasrole', 'admin')): ?>
                            <form method="POST" action="<?php echo e(route('admin.teacher-materials.destroy', $mat)); ?>"
                                  onsubmit="return confirm('<?php echo e(__('messages.confirm_delete')); ?>')">
                                <?php echo csrf_field(); ?> <?php echo method_field('DELETE'); ?>
                                <button class="text-xs bg-red-50 border border-red-200 text-red-500 hover:bg-red-100 px-2.5 py-1 rounded-lg transition">
                                    <?php echo e(__('messages.delete')); ?>

                                </button>
                            </form>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="text-center py-16 text-gray-400">
                        <svg class="w-10 h-10 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                        <?php echo e(__('messages.no_records')); ?>

                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    
    <?php if($materials->hasPages()): ?>
    <div class="p-4 border-t border-gray-100">
        <?php echo e($materials->withQueryString()->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\lms\resources\views/admin/teacher-materials/index.blade.php ENDPATH**/ ?>