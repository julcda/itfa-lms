
<?php $__env->startSection('title', __('messages.add_material')); ?>
<?php $__env->startSection('page-title', __('messages.add_material')); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-3xl">
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <form method="POST" action="<?php echo e(route('admin.teacher-materials.store')); ?>" enctype="multipart/form-data" class="space-y-5">
            <?php echo csrf_field(); ?>

            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('messages.title_en')); ?> *</label>
                    <input type="text" name="title" value="<?php echo e(old('title')); ?>" required
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300 <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-400 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><p class="text-red-500 text-xs mt-1"><?php echo e($message); ?></p><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('messages.title_ar')); ?></label>
                    <input type="text" name="title_ar" value="<?php echo e(old('title_ar')); ?>" dir="rtl"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
            </div>

            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('messages.description_en')); ?></label>
                    <textarea name="description" rows="3"
                              class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300"><?php echo e(old('description')); ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('messages.description_ar')); ?></label>
                    <textarea name="description_ar" rows="3" dir="rtl"
                              class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300"><?php echo e(old('description_ar')); ?></textarea>
                </div>
            </div>

            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('messages.material_type')); ?> *</label>
                    <select name="material_type" required
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        <?php $__currentLoopData = \App\Models\TeacherMaterial::allTypes(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $t): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($t); ?>" <?php echo e(old('material_type', 'pdf') === $t ? 'selected' : ''); ?>><?php echo e(strtoupper($t)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('messages.language')); ?></label>
                    <select name="language"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        <option value="english"  <?php echo e(old('language','english')==='english'  ? 'selected':''); ?>>English</option>
                        <option value="arabic"   <?php echo e(old('language')==='arabic'   ? 'selected':''); ?>>Arabic / عربي</option>
                        <option value="bilingual"<?php echo e(old('language')==='bilingual' ? 'selected':''); ?>>Bilingual</option>
                        <option value="filipino" <?php echo e(old('language')==='filipino'  ? 'selected':''); ?>>Filipino</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('messages.status')); ?> *</label>
                    <select name="status" required
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        <option value="active" <?php echo e(old('status','active')==='active' ? 'selected':''); ?>><?php echo e(__('messages.active')); ?></option>
                        <option value="draft"  <?php echo e(old('status')==='draft' ? 'selected':''); ?>><?php echo e(__('messages.draft')); ?></option>
                    </select>
                </div>
            </div>

            
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('messages.subject')); ?></label>
                    <input type="text" name="subject" value="<?php echo e(old('subject')); ?>"
                           placeholder="e.g. Mathematics, Science"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('messages.grade_level')); ?></label>
                    <input type="text" name="grade_level" value="<?php echo e(old('grade_level')); ?>"
                           placeholder="e.g. Grade 7, All Grades"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('messages.category')); ?></label>
                    <select name="category_id"
                            class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                        <option value="">—</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($cat->id); ?>" <?php echo e(old('category_id')==$cat->id ? 'selected':''); ?>><?php echo e($cat->name_localized); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
            </div>

            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('messages.source')); ?></label>
                    <input type="text" name="source" value="<?php echo e(old('source')); ?>"
                           placeholder="<?php echo e(__('messages.source_placeholder')); ?>"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('messages.published_year')); ?></label>
                    <input type="number" name="published_year" value="<?php echo e(old('published_year')); ?>"
                           min="1900" max="<?php echo e(date('Y') + 1); ?>" placeholder="<?php echo e(date('Y')); ?>"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                </div>
            </div>

            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('messages.tags')); ?></label>
                <input type="text" name="tags"
                       value="<?php echo e(old('tags', is_array(old('tags')) ? implode(', ', old('tags')) : old('tags'))); ?>"
                       placeholder="<?php echo e(__('messages.tags_placeholder')); ?>"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                <p class="text-xs text-gray-400 mt-1"><?php echo e(__('messages.comma_separated')); ?></p>
            </div>

            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('messages.external_url')); ?></label>
                <input type="url" name="external_url" value="<?php echo e(old('external_url')); ?>"
                       placeholder="https://…"
                       class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                <p class="text-xs text-gray-400 mt-1"><?php echo e(__('messages.leave_blank_for_upload')); ?></p>
            </div>

            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('messages.file')); ?></label>
                    <input type="file" name="file_path"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                    <p class="text-xs text-gray-400 mt-1"><?php echo e(__('messages.max_100mb')); ?></p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('messages.cover_image')); ?></label>
                    <input type="file" name="cover_image" accept="image/*"
                           class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-300">
                    <p class="text-xs text-gray-400 mt-1"><?php echo e(__('messages.max_2mb')); ?></p>
                </div>
            </div>

            
            <div class="flex items-center gap-3 pt-2">
                <button type="submit"
                        class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-lg text-sm font-semibold transition">
                    <?php echo e(__('messages.save')); ?>

                </button>
                <a href="<?php echo e(route('admin.teacher-materials.index')); ?>"
                   class="border border-gray-200 text-gray-600 hover:bg-gray-50 px-5 py-2.5 rounded-lg text-sm transition">
                    <?php echo e(__('messages.cancel')); ?>

                </a>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\lms\resources\views/admin/teacher-materials/create.blade.php ENDPATH**/ ?>