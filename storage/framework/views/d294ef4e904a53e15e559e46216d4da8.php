<?php $__env->startSection('title', 'LokSewaAlert – लोकसेवा सुचना ' . date('Y') . ', Nepal Government Jobs'); ?>
<?php $__env->startSection('description', 'LokSewaAlert – Nepal\'s fastest-updated लोकसेवा सुचना portal. Today\'s govt job vacancies, exam results, admit cards, answer keys & syllabus.'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Trending Jobs Widget -->
    <?php if(($trendingJobs && $trendingJobs->count() > 0) || ($closingSoon && $closingSoon->count() > 0)): ?>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">
        <!-- Most Viewed Jobs -->
        <?php if($trendingJobs && $trendingJobs->count() > 0): ?>
        <div class="bg-gradient-to-br from-orange-50 to-red-50 border border-orange-200 rounded-xl p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-900 flex items-center gap-2">
                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-orange-500 text-white">
                        <i class="fas fa-fire text-sm"></i>
                    </div>
                    <span>🔥 Latest Jobs</span>
                </h3>
                <span class="text-xs text-orange-600 font-semibold bg-orange-100 px-2 py-1 rounded-full">This Week</span>
            </div>
            <div class="space-y-2">
                <?php $__currentLoopData = $trendingJobs->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('posts.show', [$job->type, $job->slug])); ?>" 
                   class="block bg-white border border-orange-100 rounded-lg p-3 hover:border-orange-400 hover:shadow-md transition-all group">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-sm text-gray-800 group-hover:text-orange-600 transition line-clamp-2 mb-1">
                                <?php echo e(Str::limit($job->title, 60)); ?>

                            </h4>
                            <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500">
                                <?php if($job->organization): ?>
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-building text-[10px]"></i>
                                    <?php echo e(Str::limit($job->organization, 25)); ?>

                                </span>
                                <?php endif; ?>
                                <?php if($job->state): ?>
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-map-marker-alt text-[10px]"></i>
                                    <?php echo e($job->state->name); ?>

                                </span>
                                <?php endif; ?>
                                <?php if($job->total_posts): ?>
                                <span class="flex items-center gap-1 text-orange-600 font-semibold">
                                    <i class="fas fa-users text-[10px]"></i>
                                    <?php echo e(number_format($job->total_posts)); ?> posts
                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-arrow-right text-orange-400 group-hover:text-orange-600 group-hover:translate-x-1 transition-all"></i>
                        </div>
                    </div>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Closing Soon Jobs -->
        <?php if($closingSoon && $closingSoon->count() > 0): ?>
        <div class="bg-gradient-to-br from-red-50 to-pink-50 border border-red-200 rounded-xl p-5 shadow-sm">
            <div class="flex items-center justify-between mb-4">
                <h3 class="font-bold text-gray-900 flex items-center gap-2">
                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-red-500 text-white">
                        <i class="fas fa-clock text-sm"></i>
                    </div>
                    <span>⏰ Closing Soon</span>
                </h3>
                <span class="text-xs text-red-600 font-semibold bg-red-100 px-2 py-1 rounded-full">Apply Now</span>
            </div>
            <div class="space-y-2">
                <?php $__currentLoopData = $closingSoon->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $daysLeft = now()->startOfDay()->diffInDays($job->last_date->startOfDay(), false);
                ?>
                <a href="<?php echo e(route('posts.show', [$job->type, $job->slug])); ?>" 
                   class="block bg-white border border-red-100 rounded-lg p-3 hover:border-red-400 hover:shadow-md transition-all group">
                    <div class="flex items-start justify-between gap-2">
                        <div class="flex-1 min-w-0">
                            <h4 class="font-semibold text-sm text-gray-800 group-hover:text-red-600 transition line-clamp-2 mb-1">
                                <?php echo e(Str::limit($job->title, 60)); ?>

                            </h4>
                            <div class="flex flex-wrap items-center gap-2 text-xs text-gray-500">
                                <?php if($job->organization): ?>
                                <span class="flex items-center gap-1">
                                    <i class="fas fa-building text-[10px]"></i>
                                    <?php echo e(Str::limit($job->organization, 25)); ?>

                                </span>
                                <?php endif; ?>
                                <?php if($job->last_date): ?>
                                <span class="flex items-center gap-1 <?php echo e($daysLeft <= 3 ? 'text-red-600 font-bold' : 'text-orange-600 font-semibold'); ?>">
                                    <i class="fas fa-calendar-times text-[10px]"></i>
                                    <?php echo e($daysLeft == 0 ? 'Today!' : ($daysLeft == 1 ? 'Tomorrow' : $daysLeft . ' days left')); ?>

                                </span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="flex-shrink-0">
                            <i class="fas fa-arrow-right text-red-400 group-hover:text-red-600 group-hover:translate-x-1 transition-all"></i>
                        </div>
                    </div>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- India Map with State Job Counts -->


    <!-- Column Sections for Each Type -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Jobs Column -->
        <div>
            <div class="px-4 py-3 font-bold flex items-center justify-between rounded-lg mb-3" style="background:#eff6ff;border-left:4px solid #2563eb;border:1px solid #bfdbfe;">
                <span style="color:#1d4ed8;"><i class="fa-solid fa-briefcase"></i> Latest Jobs</span>
                <a href="<?php echo e(route('posts.jobs')); ?>" style="color:#2563eb;" class="text-sm"><i class="fa-solid fa-arrow-right"></i></a>
            </div>
            <div class="space-y-4">
                <?php $__currentLoopData = ($sections['jobs'] ?? [])->take(25); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if (isset($component)) { $__componentOriginald203e834c3725bfcc8d3dae774849790 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald203e834c3725bfcc8d3dae774849790 = $attributes; } ?>
<?php $component = App\View\Components\PostCard::resolve(['post' => $post] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('post-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\PostCard::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald203e834c3725bfcc8d3dae774849790)): ?>
<?php $attributes = $__attributesOriginald203e834c3725bfcc8d3dae774849790; ?>
<?php unset($__attributesOriginald203e834c3725bfcc8d3dae774849790); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald203e834c3725bfcc8d3dae774849790)): ?>
<?php $component = $__componentOriginald203e834c3725bfcc8d3dae774849790; ?>
<?php unset($__componentOriginald203e834c3725bfcc8d3dae774849790); ?>
<?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php if(($sections['jobs'] ?? [])->count() > 25): ?>
            <div class="mt-4">
                <a href="<?php echo e(route('posts.jobs')); ?>" class="block text-center px-4 py-3 bg-white border border-gray-300 hover:border-blue-500 hover:bg-blue-50 text-gray-700 hover:text-blue-600 font-semibold rounded-lg transition-all">
                    View All Jobs <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
            </div>
            <?php endif; ?>
        </div>

        <!-- Results Column -->
        <div>
            <div class="px-4 py-3 font-bold flex items-center justify-between rounded-lg mb-3" style="background:#fff7ed;border-left:4px solid #ea580c;border:1px solid #fed7aa;">
                <span style="color:#c2410c;"><i class="fa-solid fa-chart-bar"></i> Exam Results</span>
                <a href="<?php echo e(route('posts.results')); ?>" style="color:#ea580c;" class="text-sm"><i class="fa-solid fa-arrow-right"></i></a>
            </div>
            <div class="space-y-4">
                <?php $__currentLoopData = ($sections['results'] ?? [])->take(25); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if (isset($component)) { $__componentOriginald203e834c3725bfcc8d3dae774849790 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald203e834c3725bfcc8d3dae774849790 = $attributes; } ?>
<?php $component = App\View\Components\PostCard::resolve(['post' => $post] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('post-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\PostCard::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald203e834c3725bfcc8d3dae774849790)): ?>
<?php $attributes = $__attributesOriginald203e834c3725bfcc8d3dae774849790; ?>
<?php unset($__attributesOriginald203e834c3725bfcc8d3dae774849790); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald203e834c3725bfcc8d3dae774849790)): ?>
<?php $component = $__componentOriginald203e834c3725bfcc8d3dae774849790; ?>
<?php unset($__componentOriginald203e834c3725bfcc8d3dae774849790); ?>
<?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php if(($sections['results'] ?? [])->count() > 25): ?>
            <div class="mt-4">
                <a href="<?php echo e(route('posts.results')); ?>" class="block text-center px-4 py-3 bg-white border border-gray-300 hover:border-orange-500 hover:bg-orange-50 text-gray-700 hover:text-orange-600 font-semibold rounded-lg transition-all">
                    View All Results <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
            </div>
            <?php endif; ?>
        </div>

        <!-- Admit Cards Column -->
        <div>
            <div class="px-4 py-3 font-bold flex items-center justify-between rounded-lg mb-3" style="background:#faf5ff;border-left:4px solid #9333ea;border:1px solid #e9d5ff;">
                <span style="color:#7e22ce;"><i class="fa-solid fa-id-card"></i> Admit Cards</span>
                <a href="<?php echo e(route('posts.admit-cards')); ?>" style="color:#9333ea;" class="text-sm"><i class="fa-solid fa-arrow-right"></i></a>
            </div>
            <div class="space-y-4">
                <?php $__currentLoopData = ($sections['admit_cards'] ?? [])->take(25); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if (isset($component)) { $__componentOriginald203e834c3725bfcc8d3dae774849790 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald203e834c3725bfcc8d3dae774849790 = $attributes; } ?>
<?php $component = App\View\Components\PostCard::resolve(['post' => $post] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('post-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\PostCard::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald203e834c3725bfcc8d3dae774849790)): ?>
<?php $attributes = $__attributesOriginald203e834c3725bfcc8d3dae774849790; ?>
<?php unset($__attributesOriginald203e834c3725bfcc8d3dae774849790); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald203e834c3725bfcc8d3dae774849790)): ?>
<?php $component = $__componentOriginald203e834c3725bfcc8d3dae774849790; ?>
<?php unset($__componentOriginald203e834c3725bfcc8d3dae774849790); ?>
<?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php if(($sections['admit_cards'] ?? [])->count() > 25): ?>
            <div class="mt-4">
                <a href="<?php echo e(route('posts.admit-cards')); ?>" class="block text-center px-4 py-3 bg-white border border-gray-300 hover:border-purple-500 hover:bg-purple-50 text-gray-700 hover:text-purple-600 font-semibold rounded-lg transition-all">
                    View All Admit Cards <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
            </div>
            <?php endif; ?>
        </div>

        <!-- Answer Keys Column -->
        <div>
            <div class="px-4 py-3 font-bold flex items-center justify-between rounded-lg mb-3" style="background:#fefce8;border-left:4px solid #ca8a04;border:1px solid #fde68a;">
                <span style="color:#92400e;"><i class="fa-solid fa-key"></i> Answer Keys</span>
                <a href="<?php echo e(route('posts.answer-keys')); ?>" style="color:#ca8a04;" class="text-sm"><i class="fa-solid fa-arrow-right"></i></a>
            </div>
            <div class="space-y-4">
                <?php $__currentLoopData = ($sections['answer_keys'] ?? [])->take(25); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if (isset($component)) { $__componentOriginald203e834c3725bfcc8d3dae774849790 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald203e834c3725bfcc8d3dae774849790 = $attributes; } ?>
<?php $component = App\View\Components\PostCard::resolve(['post' => $post] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('post-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\PostCard::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald203e834c3725bfcc8d3dae774849790)): ?>
<?php $attributes = $__attributesOriginald203e834c3725bfcc8d3dae774849790; ?>
<?php unset($__attributesOriginald203e834c3725bfcc8d3dae774849790); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald203e834c3725bfcc8d3dae774849790)): ?>
<?php $component = $__componentOriginald203e834c3725bfcc8d3dae774849790; ?>
<?php unset($__componentOriginald203e834c3725bfcc8d3dae774849790); ?>
<?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php if(($sections['answer_keys'] ?? [])->count() > 25): ?>
            <div class="mt-4">
                <a href="<?php echo e(route('posts.answer-keys')); ?>" class="block text-center px-4 py-3 bg-white border border-gray-300 hover:border-yellow-500 hover:bg-yellow-50 text-gray-700 hover:text-yellow-600 font-semibold rounded-lg transition-all">
                    View All Answer Keys <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
            </div>
            <?php endif; ?>
        </div>

        <!-- Syllabus Column -->
        <div>
            <div class="px-4 py-3 font-bold flex items-center justify-between rounded-lg mb-3" style="background:#eef2ff;border-left:4px solid #4f46e5;border:1px solid #c7d2fe;">
                <span style="color:#3730a3;"><i class="fa-solid fa-book"></i> Syllabus</span>
                <a href="<?php echo e(route('posts.syllabus')); ?>" style="color:#4f46e5;" class="text-sm"><i class="fa-solid fa-arrow-right"></i></a>
            </div>
            <div class="space-y-4">
                <?php $__currentLoopData = ($sections['syllabus'] ?? [])->take(25); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if (isset($component)) { $__componentOriginald203e834c3725bfcc8d3dae774849790 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald203e834c3725bfcc8d3dae774849790 = $attributes; } ?>
<?php $component = App\View\Components\PostCard::resolve(['post' => $post] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('post-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\PostCard::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald203e834c3725bfcc8d3dae774849790)): ?>
<?php $attributes = $__attributesOriginald203e834c3725bfcc8d3dae774849790; ?>
<?php unset($__attributesOriginald203e834c3725bfcc8d3dae774849790); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald203e834c3725bfcc8d3dae774849790)): ?>
<?php $component = $__componentOriginald203e834c3725bfcc8d3dae774849790; ?>
<?php unset($__componentOriginald203e834c3725bfcc8d3dae774849790); ?>
<?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php if(($sections['syllabus'] ?? [])->count() > 25): ?>
            <div class="mt-4">
                <a href="<?php echo e(route('posts.syllabus')); ?>" class="block text-center px-4 py-3 bg-white border border-gray-300 hover:border-indigo-500 hover:bg-indigo-50 text-gray-700 hover:text-indigo-600 font-semibold rounded-lg transition-all">
                    View All Syllabus <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
            </div>
            <?php endif; ?>
        </div>

        <!-- Blogs Column -->
        <div>
            <div class="px-4 py-3 font-bold flex items-center justify-between rounded-lg mb-3" style="background:#fdf2f8;border-left:4px solid #db2777;border:1px solid #fbcfe8;">
                <span style="color:#9d174d;"><i class="fa-solid fa-pen-fancy"></i> Blogs</span>
                <a href="<?php echo e(route('posts.blogs')); ?>" style="color:#db2777;" class="text-sm"><i class="fa-solid fa-arrow-right"></i></a>
            </div>
            <div class="space-y-4">
                <?php $__currentLoopData = ($sections['blogs'] ?? [])->take(25); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if (isset($component)) { $__componentOriginald203e834c3725bfcc8d3dae774849790 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald203e834c3725bfcc8d3dae774849790 = $attributes; } ?>
<?php $component = App\View\Components\PostCard::resolve(['post' => $post] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('post-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\PostCard::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald203e834c3725bfcc8d3dae774849790)): ?>
<?php $attributes = $__attributesOriginald203e834c3725bfcc8d3dae774849790; ?>
<?php unset($__attributesOriginald203e834c3725bfcc8d3dae774849790); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald203e834c3725bfcc8d3dae774849790)): ?>
<?php $component = $__componentOriginald203e834c3725bfcc8d3dae774849790; ?>
<?php unset($__componentOriginald203e834c3725bfcc8d3dae774849790); ?>
<?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php if(($sections['blogs'] ?? [])->count() > 25): ?>
            <div class="mt-4">
                <a href="<?php echo e(route('posts.blogs')); ?>" class="block text-center px-4 py-3 bg-white border border-gray-300 hover:border-pink-500 hover:bg-pink-50 text-gray-700 hover:text-pink-600 font-semibold rounded-lg transition-all">
                    View All Blogs <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
            </div>
            <?php endif; ?>
        </div>

        <!-- Scholarships Column -->
        <div>
            <div class="px-4 py-3 font-bold flex items-center justify-between rounded-lg mb-3" style="background:#f0fdfa;border-left:4px solid #0d9488;border:1px solid #99f6e4;">
                <span style="color:#0f766e;"><i class="fa-solid fa-graduation-cap"></i> Scholarships</span>
                <a href="<?php echo e(route('posts.scholarships')); ?>" style="color:#0d9488;" class="text-sm"><i class="fa-solid fa-arrow-right"></i></a>
            </div>
            <div class="space-y-4">
                <?php $__currentLoopData = ($sections['scholarships'] ?? [])->take(25); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $post): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php if (isset($component)) { $__componentOriginald203e834c3725bfcc8d3dae774849790 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald203e834c3725bfcc8d3dae774849790 = $attributes; } ?>
<?php $component = App\View\Components\PostCard::resolve(['post' => $post] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('post-card'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\PostCard::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald203e834c3725bfcc8d3dae774849790)): ?>
<?php $attributes = $__attributesOriginald203e834c3725bfcc8d3dae774849790; ?>
<?php unset($__attributesOriginald203e834c3725bfcc8d3dae774849790); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald203e834c3725bfcc8d3dae774849790)): ?>
<?php $component = $__componentOriginald203e834c3725bfcc8d3dae774849790; ?>
<?php unset($__componentOriginald203e834c3725bfcc8d3dae774849790); ?>
<?php endif; ?>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            <?php if(($sections['scholarships'] ?? [])->count() > 25): ?>
            <div class="mt-4">
                <a href="<?php echo e(route('posts.scholarships')); ?>" class="block text-center px-4 py-3 bg-white border border-gray-300 hover:border-teal-500 hover:bg-teal-50 text-gray-700 hover:text-teal-600 font-semibold rounded-lg transition-all">
                    View All Scholarships <i class="fa-solid fa-arrow-right ml-1"></i>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Share Section -->
    <div class="bg-white border border-gray-200 rounded-xl p-6 mb-6 shadow-sm">
        <h3 class="font-bold text-gray-900 mb-4 text-base flex items-center gap-2">
            <i class="fas fa-share-alt"></i> Follow & Share
        </h3>
        <?php
            $shareUrl = route('home');
            $shareTitle = 'LokSewaAlert – Nepal Government Jobs & Results Updated Daily';
            $simpleMessage = "{$shareTitle} - Visit: {$shareUrl}";
            $encodedSimpleMessage = urlencode($simpleMessage);
            $encodedUrl = urlencode($shareUrl);
            $encodedTitle = urlencode($shareTitle);
        ?>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="https://wa.me/?text=<?php echo e($encodedSimpleMessage); ?>" target="_blank" rel="noopener noreferrer"
               style="display:flex;align-items:center;gap:12px;padding:10px 20px;color:white;text-decoration:none;border-radius:10px;font-weight:600;transition:0.3s;background:#25D366;"
               onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                <div style="background:rgba(255,255,255,0.2);width:40px;height:40px;display:flex;align-items:center;justify-content:center;border-radius:50%;"><i class="fab fa-whatsapp" style="font-size:20px;"></i></div>
                <span>WhatsApp</span>
            </a>
            <a href="https://t.me/share/url?url=<?php echo e($encodedUrl); ?>&text=<?php echo e($encodedTitle); ?>" target="_blank" rel="noopener noreferrer"
               style="display:flex;align-items:center;gap:12px;padding:10px 20px;color:white;text-decoration:none;border-radius:10px;font-weight:600;transition:0.3s;background:#0088cc;"
               onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                <div style="background:rgba(255,255,255,0.2);width:40px;height:40px;display:flex;align-items:center;justify-content:center;border-radius:50%;"><i class="fab fa-telegram" style="font-size:20px;"></i></div>
                <span>Telegram</span>
            </a>
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo e($encodedUrl); ?>" target="_blank" rel="noopener noreferrer"
               style="display:flex;align-items:center;gap:12px;padding:10px 20px;color:white;text-decoration:none;border-radius:10px;font-weight:600;transition:0.3s;background:#1877F2;"
               onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                <div style="background:rgba(255,255,255,0.2);width:40px;height:40px;display:flex;align-items:center;justify-content:center;border-radius:50%;"><i class="fab fa-facebook" style="font-size:20px;"></i></div>
                <span>Facebook</span>
            </a>
            <a href="https://twitter.com/intent/tweet?url=<?php echo e($encodedUrl); ?>&text=<?php echo e($encodedTitle); ?>" target="_blank" rel="noopener noreferrer"
               style="display:flex;align-items:center;gap:12px;padding:10px 20px;color:white;text-decoration:none;border-radius:10px;font-weight:600;transition:0.3s;background:#1DA1F2;"
               onmouseover="this.style.transform='scale(1.05)'" onmouseout="this.style.transform='scale(1)'">
                <div style="background:rgba(255,255,255,0.2);width:40px;height:40px;display:flex;align-items:center;justify-content:center;border-radius:50%;"><i class="fab fa-twitter" style="font-size:20px;"></i></div>
                <span>Twitter</span>
            </a>
        </div>
    </div>

    
    <?php
        $yr = date('Y');
        $faqs = [
            ['q' => 'What is LokSewaAlert and how is it different from other job portals?',
             'a' => 'LokSewaAlert is Nepal\'s fastest-updated लोकसेवा सुचना portal. Unlike other sites, we update vacancies within minutes of official notification release. Every post includes direct apply links, eligibility, salary, and important dates — all in one clean page.'],
            ['q' => 'How do I get instant govt job alerts from LokSewaAlert?',
             'a' => 'Tap the notification bell icon on LokSewaAlert to enable push alerts. You will receive instant alerts whenever a new लोकसेवा, admit card, or result is published. No app download needed — works right in your browser.'],
            ['q' => 'How to download admit card / hall ticket from LokSewaAlert?',
             'a' => 'Go to the Admit Cards section on LokSewaAlert, search for your exam name, and click the official download link. We provide direct links to the official government website — no redirects or ads.'],
            ['q' => 'How to check sarkari result ' . $yr . ' on LokSewaAlert?',
             'a' => 'Visit the Results section on LokSewaAlert. We post Nepal Government exam results with merit list and cut-off marks as soon as they are declared — often within minutes of the official announcement.'],
            ['q' => 'What qualifications are needed for government jobs in Nepal ' . $yr . '?',
             'a' => 'Qualifications vary: Government jobs need 10th/12th pass, clerical posts need graduation, while Lok Sewa Aayog, Police, and Teaching require specific qualifications. Check each post on LokSewaAlert for exact eligibility.'],
            ['q' => 'Is LokSewaAlert free to use?',
             'a' => 'Yes! LokSewaAlert is 100% free. Browse unlimited job notifications, download admit cards, check results — no registration required, no hidden fees. We earn through non-intrusive advertising only.'],
        ];
        $quickLinks = [
            ['label' => 'Latest Jobs ' . $yr, 'route' => 'posts.jobs',        'icon' => 'fa-briefcase',       'color' => '#2563eb'],
            ['label' => 'Sarkari Result',      'route' => 'posts.results',     'icon' => 'fa-chart-bar',       'color' => '#ea580c'],
            ['label' => 'Admit Card',          'route' => 'posts.admit-cards', 'icon' => 'fa-id-card',         'color' => '#9333ea'],
            ['label' => 'Answer Key',          'route' => 'posts.answer-keys', 'icon' => 'fa-key',             'color' => '#ca8a04'],
            ['label' => 'Exam Syllabus',       'route' => 'posts.syllabus',    'icon' => 'fa-book',            'color' => '#4f46e5'],
            ['label' => 'Scholarships',        'route' => 'posts.scholarships','icon' => 'fa-graduation-cap',  'color' => '#0d9488'],
            ['label' => 'SSC Jobs',            'route' => 'posts.jobs',        'icon' => 'fa-building-columns','color' => '#be185d'],
            ['label' => 'UPSC Jobs',           'route' => 'posts.jobs',        'icon' => 'fa-landmark',        'color' => '#7c3aed'],
        ];
        $tags = ['Lok Sewa Aayog','Nepal Police','Nepal Army','Teaching Service','Health Service','Engineering','Banking Jobs','Local Level','Sarkari Result','Admit Card','Answer Key','Syllabus PDF'];
    ?>

    <div class="space-y-4 md:space-y-6 mb-8">

        <!-- About -->
        <div class="bg-white border border-gray-100 rounded-2xl p-4 sm:p-5 md:p-6 shadow-sm">
            <h2 class="text-sm sm:text-base font-bold text-gray-800 mb-3 flex items-center gap-2">
                <div class="flex items-center justify-center w-6 h-6 rounded-full bg-blue-50 flex-shrink-0">
                    <i class="fas fa-info text-blue-600 text-[10px]"></i>
                </div>
                About LokSewaAlert – Nepal's Fastest-Updated लोकसेवा सुचना Portal
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-4">
                <div class="bg-gray-50 border border-gray-100 p-3 rounded-xl text-xs sm:text-sm text-gray-500 leading-relaxed">
                    <p><strong>LokSewaAlert</strong> is Nepal’s fastest-updated <strong>लोकसेवा सुचना</strong> portal. We publish new govt job vacancies, <strong>results</strong>, admit cards, answer keys, and exam syllabus within minutes of official release — completely free. We cover Lok Sewa Aayog, Nepal Police, Nepal Army, Teaching Service, Health Service, Engineering, Banking, and Local Level.</p>
                </div>
                <div class="bg-gray-50 border border-gray-100 p-3 rounded-xl text-xs sm:text-sm text-gray-500 leading-relaxed">
                    <p>Enable instant push notifications to get every new <strong>लोकसेवा ‘ . $yr . ‘</strong> alert directly in your browser. Our team verifies all posts against official sources before publishing. Whether you need federal govt, provincial govt, or local level jobs — LokSewaAlert has it first.</p>
                </div>
                <div class="bg-gray-50 border border-gray-100 p-3 rounded-xl text-xs sm:text-sm text-gray-500 leading-relaxed">
                    <p>Every post on LokSewaAlert includes <strong>direct apply links</strong>, eligibility details, salary info, <strong>exam results ‘ . $yr . ‘</strong>, official <strong>answer keys</strong>, detailed <strong>exam syllabus PDF</strong>, cut-off marks, and merit lists — beautifully organized so you find what you need in seconds.</p>
                </div>
            </div>

            <div class="flex flex-wrap gap-1.5">
                <?php $__currentLoopData = $tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $seoTag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route('search')); ?>?q=<?php echo e(urlencode($seoTag)); ?>"
                   class="text-[10px] sm:text-xs px-2 sm:px-3 py-1 rounded-md border border-blue-100 bg-blue-50/50 text-blue-600 hover:bg-blue-500 hover:border-blue-500 hover:text-white transition-all font-medium whitespace-nowrap">
                    <?php echo e($seoTag); ?>

                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- FAQ -->
        <div class="bg-white border border-gray-100 rounded-2xl p-4 sm:p-6 md:p-8 shadow-sm">
            <h2 class="text-base sm:text-lg font-bold text-gray-900 mb-4 sm:mb-5 flex items-center gap-2">
                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-indigo-50 flex-shrink-0">
                    <i class="fas fa-question text-indigo-600 text-sm"></i>
                </div>
                Frequently Asked Questions (FAQ)
            </h2>
            <div class="space-y-3">
                <?php $__currentLoopData = $faqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <details class="group bg-gray-50 border border-gray-100 rounded-xl overflow-hidden [&_summary::-webkit-details-marker]:hidden">
                    <summary class="flex justify-between items-center cursor-pointer p-4 text-sm sm:text-base font-semibold text-gray-800 hover:text-blue-600 hover:bg-blue-50/50 transition-colors list-none">
                        <span><?php echo e($faq['q']); ?></span>
                        <span class="ml-4 flex-shrink-0 flex items-center justify-center w-6 h-6 rounded-full bg-white border border-gray-200 text-gray-400 group-open:bg-blue-600 group-open:border-blue-600 group-open:text-white transition-all">
                            <i class="fas fa-plus text-[10px] group-open:hidden"></i>
                            <i class="fas fa-minus text-[10px] hidden group-open:block"></i>
                        </span>
                    </summary>
                    <div class="px-4 pb-4 pt-1 text-sm sm:text-base text-gray-600 leading-loose border-t border-gray-100 bg-white">
                        <?php echo e($faq['a']); ?>

                    </div>
                </details>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="bg-white border border-gray-100 rounded-2xl p-4 sm:p-6 md:p-8 shadow-sm">
            <h2 class="text-base sm:text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <div class="flex items-center justify-center w-8 h-8 rounded-full bg-green-50 flex-shrink-0">
                    <i class="fas fa-link text-green-600 text-sm"></i>
                </div>
                Quick Links
            </h2>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
                <?php $__currentLoopData = $quickLinks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ql): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <a href="<?php echo e(route($ql['route'])); ?>"
                   class="flex items-center gap-3 p-3 rounded-xl border border-gray-100 bg-gray-50 hover:border-blue-300 hover:bg-blue-50 transition-all font-medium text-gray-700 hover:text-blue-700 shadow-sm hover:shadow-md">
                    <div class="flex items-center justify-center w-8 h-8 rounded-full bg-white border border-gray-100 shadow-sm flex-shrink-0">
                        <i class="fas <?php echo e($ql['icon']); ?>" style="color:<?php echo e($ql['color']); ?>;font-size:12px;"></i>
                    </div>
                    <span class="text-xs sm:text-sm leading-tight"><?php echo e($ql['label']); ?></span>
                </a>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

    </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\job\loksewaalert-portal\resources\views/home.blade.php ENDPATH**/ ?>