<?php if (isset($component)) { $__componentOriginal69dc84650370d1d4dc1b42d016d7226b = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b = $attributes; } ?>
<?php $component = App\View\Components\GuestLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('guest-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\GuestLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-8">

        
        <div class="mb-7">
            <h2 class="text-2xl font-bold text-gray-900"><?php echo e(__('messages.welcome_back')); ?></h2>
            <p class="text-gray-400 text-sm mt-1"><?php echo e(__('messages.login_subtitle')); ?></p>
        </div>

        <?php if (isset($component)) { $__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.auth-session-status','data' => ['class' => 'mb-4','status' => session('status')]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('auth-session-status'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'mb-4','status' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(session('status'))]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5)): ?>
<?php $attributes = $__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5; ?>
<?php unset($__attributesOriginal7c1bf3a9346f208f66ee83b06b607fb5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5)): ?>
<?php $component = $__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5; ?>
<?php unset($__componentOriginal7c1bf3a9346f208f66ee83b06b607fb5); ?>
<?php endif; ?>

        <?php if($errors->any()): ?>
        <div class="mb-5 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
            <ul class="list-disc ms-4 space-y-0.5">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?><li><?php echo e($error); ?></li><?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <?php endif; ?>

        <form method="POST" action="<?php echo e(route('login')); ?>" class="space-y-5">
            <?php echo csrf_field(); ?>

            
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1"><?php echo e(__('messages.email')); ?></label>
                <input type="email" id="email" name="email" value="<?php echo e(old('email')); ?>" required autofocus autocomplete="username"
                       class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition <?php echo e($errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 focus:bg-white'); ?>">
            </div>

            
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label for="password" class="block text-sm font-medium text-gray-700"><?php echo e(__('messages.password')); ?></label>
                    <?php if(Route::has('password.request')): ?>
                        <a href="<?php echo e(route('password.request')); ?>" class="text-xs text-emerald-600 hover:text-emerald-800 font-medium">
                            <?php echo e(__('messages.forgot_password')); ?>

                        </a>
                    <?php endif; ?>
                </div>
                <input type="password" id="password" name="password" required autocomplete="current-password"
                       class="w-full border rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-transparent transition <?php echo e($errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-200 bg-gray-50 focus:bg-white'); ?>">
            </div>

            
            <div class="flex items-center gap-2">
                <input id="remember_me" type="checkbox" name="remember"
                       class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500">
                <label for="remember_me" class="text-sm text-gray-600 cursor-pointer select-none"><?php echo e(__('messages.remember_me')); ?></label>
            </div>

            
            <button type="submit"
                    class="w-full py-2.5 px-4 rounded-xl text-sm font-semibold text-white transition shadow-sm"
                    style="background:linear-gradient(135deg,#10b981,#059669);box-shadow:0 4px 14px rgba(16,185,129,0.35)">
                <?php echo e(__('messages.login')); ?>

            </button>
        </form>

        <?php if(Route::has('register')): ?>
        <p class="text-center text-sm text-gray-500 mt-6">
            <?php echo e(__('messages.no_account')); ?>

            <a href="<?php echo e(route('register')); ?>" class="text-emerald-600 hover:text-emerald-800 font-semibold"><?php echo e(__('messages.register')); ?></a>
        </p>
        <?php endif; ?>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $attributes = $__attributesOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__attributesOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b)): ?>
<?php $component = $__componentOriginal69dc84650370d1d4dc1b42d016d7226b; ?>
<?php unset($__componentOriginal69dc84650370d1d4dc1b42d016d7226b); ?>
<?php endif; ?>
<?php /**PATH C:\xampp\htdocs\lms\resources\views/auth/login.blade.php ENDPATH**/ ?>