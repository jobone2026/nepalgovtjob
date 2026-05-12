<?php
    $daysRemaining = null;
    $isUrgent = false;
    if ($post->last_date) {
        $daysRemaining = now()->diffInDays($post->last_date, false);
        $isUrgent = $daysRemaining <= 5 && $daysRemaining >= 0;
    }

    $eduLabels = [
        '10th_pass' => '10th',
        '12th_pass' => '12th',
        'graduate' => 'Graduate',
        'post_graduate' => 'PG',
        'diploma' => 'Diploma',
        'iti' => 'ITI',
        'btech' => 'B.Tech',
        'mtech' => 'M.Tech',
        'bsc' => 'B.Sc',
        'msc' => 'M.Sc',
        'bcom' => 'B.Com',
        'mcom' => 'M.Com',
        'ba' => 'B.A',
        'ma' => 'M.A',
        'bba' => 'BBA',
        'mba' => 'MBA',
        'ca' => 'CA',
        'cs' => 'CS',
        'cma' => 'CMA',
        'llb' => 'LLB',
        'llm' => 'LLM',
        'mbbs' => 'MBBS',
        'bds' => 'BDS',
        'bpharm' => 'B.Pharm',
        'mpharm' => 'M.Pharm',
        'nursing' => 'Nursing',
        'bed' => 'B.Ed',
        'med' => 'M.Ed',
        'phd' => 'PhD',
        'any_qualification' => 'Any',
    ];

    $typeColors = [
        'job' => '#2563eb',
        'result' => '#ea580c',
        'admit_card' => '#9333ea',
        'answer_key' => '#ca8a04',
        'syllabus' => '#4f46e5',
        'blog' => '#db2777',
        'scholarship' => '#0d9488',
    ];
    $typeLightBg = [
        'job' => '#eff6ff',
        'result' => '#fff7ed',
        'admit_card' => '#faf5ff',
        'answer_key' => '#fefce8',
        'syllabus' => '#eef2ff',
        'blog' => '#fdf2f8',
        'scholarship' => '#f0fdfa',
    ];
    $typeLightBorder = [
        'job' => '#bfdbfe',
        'result' => '#fed7aa',
        'admit_card' => '#e9d5ff',
        'answer_key' => '#fde68a',
        'syllabus' => '#c7d2fe',
        'blog' => '#fbcfe8',
        'scholarship' => '#99f6e4',
    ];
    $typeIcons = [
        'job' => 'fa-briefcase',
        'result' => 'fa-chart-bar',
        'admit_card' => 'fa-id-card',
        'answer_key' => 'fa-key',
        'syllabus' => 'fa-book',
        'blog' => 'fa-pen-fancy',
        'scholarship' => 'fa-graduation-cap',
    ];
    $typeLabels = [
        'job' => 'Job',
        'result' => 'Result',
        'admit_card' => 'Admit Card',
        'answer_key' => 'Answer Key',
        'syllabus' => 'Syllabus',
        'blog' => 'Blog',
        'scholarship' => 'Scholarship',
    ];
    $borderColor = $typeColors[$post->type] ?? '#6b7280';
    $headerBg = $typeLightBg[$post->type] ?? '#f9fafb';
    $headerBorder = $typeLightBorder[$post->type] ?? '#e5e7eb';
    $typeIcon = $typeIcons[$post->type] ?? 'fa-file';
    $typeLabel = $typeLabels[$post->type] ?? ucfirst($post->type);

    // Clean title & generate attention badges
    $cleanTitle = \App\Helpers\PostHelper::cleanTitle($post->title);
    $attentionBadges = \App\Helpers\PostHelper::getAttentionBadges($post);

    // Type-aware card CTA
    $cardCta = match ($post->type) {
        'job' => ['icon' => 'fa-paper-plane', 'text' => 'Apply Now', 'bg' => '#f0fdf4', 'color' => '#16a34a', 'border' => '#bbf7d0', 'hover' => '#16a34a'],
        'admit_card' => ['icon' => 'fa-download', 'text' => 'Download', 'bg' => '#faf5ff', 'color' => '#7e22ce', 'border' => '#e9d5ff', 'hover' => '#7e22ce'],
        'result' => ['icon' => 'fa-trophy', 'text' => 'Check Result', 'bg' => '#fff7ed', 'color' => '#c2410c', 'border' => '#fed7aa', 'hover' => '#c2410c'],
        'answer_key' => ['icon' => 'fa-key', 'text' => 'Answer Key', 'bg' => '#fefce8', 'color' => '#854d0e', 'border' => '#fde68a', 'hover' => '#854d0e'],
        'syllabus' => ['icon' => 'fa-book', 'text' => 'Syllabus', 'bg' => '#eef2ff', 'color' => '#4338ca', 'border' => '#c7d2fe', 'hover' => '#4338ca'],
        'scholarship' => ['icon' => 'fa-graduation-cap', 'text' => 'Apply Now', 'bg' => '#f0fdfa', 'color' => '#0d9488', 'border' => '#99f6e4', 'hover' => '#0d9488'],
        default => ['icon' => 'fa-eye', 'text' => 'View', 'bg' => '#f9fafb', 'color' => '#374151', 'border' => '#e5e7eb', 'hover' => '#374151'],
    };
?>

<div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-md transition-all duration-200 relative cursor-pointer"
    style="border-left: 4px solid <?php echo e($borderColor); ?>;">

    
    <a href="<?php echo e(route('posts.show', [$post->type, $post->slug])); ?>" style="position:absolute;inset:0;z-index:0;"
        aria-label="<?php echo e($post->title); ?>"></a>

    
    <div style="background:<?php echo e($headerBg); ?>;border-bottom:1px solid <?php echo e($headerBorder); ?>;padding:8px 12px;
                display:flex;align-items:center;justify-content:space-between;gap:6px;">

        
        <span style="display:inline-flex;align-items:center;gap:5px;
                     background:<?php echo e($headerBg); ?>;color:<?php echo e($borderColor); ?>;
                     border:1.5px solid <?php echo e($headerBorder); ?>;
                     padding:3px 10px;border-radius:20px;font-size:10px;font-weight:700;letter-spacing:.2px;">
            <i class="fas <?php echo e($typeIcon); ?>" style="font-size:9px;"></i>
            <?php echo e($typeLabel); ?>

        </span>

        \
        <div style="display:flex;align-items:center;gap:5px;flex-wrap:wrap;">

            
            <?php $__empty_1 = true; $__currentLoopData = $attentionBadges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $badge): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <span style="display:inline-flex;align-items:center;gap:3px;padding:2px 7px;
                             border-radius:5px;font-size:9px;font-weight:700;
                             background:<?php echo e($badge['bg']); ?>;color:<?php echo e($badge['color']); ?>;border:1px solid <?php echo e($badge['border']); ?>;">
                    <?php echo e($badge['icon']); ?> <?php echo e($badge['label']); ?>

                </span>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                
                <span style="display:inline-flex;align-items:center;gap:3px;padding:2px 7px;
                             border-radius:5px;font-size:9px;font-weight:700;
                             background:#dcfce7;color:#15803d;border:1px solid #86efac;">
                    <i class="fa-solid fa-star" style="font-size:8px;"></i> NEW
                </span>
            <?php endif; ?>

            <?php if($post->state): ?>
                <span style="display:inline-flex;align-items:center;gap:3px;padding:2px 7px;
                             border-radius:5px;font-size:9px;font-weight:700;
                             background:#eff6ff;color:#1d4ed8;border:1px solid #bfdbfe;">
                    <i class="fa-solid fa-location-dot" style="font-size:8px;"></i> <?php echo e(Str::limit($post->state->name, 12)); ?>

                </span>
            <?php else: ?>
                <span style="display:inline-flex;align-items:center;gap:3px;padding:2px 7px;
                             border-radius:5px;font-size:9px;font-weight:700;
                             background:#faf5ff;color:#7e22ce;border:1px solid #ddd6fe;">
                    <i class="fa-solid fa-globe" style="font-size:8px;"></i> All India
                </span>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="p-3.5">

        
        <div class="text-sm font-semibold leading-snug mb-3" style="color:#111827;line-height:1.45;">
            <a href="<?php echo e(route('posts.show', [$post->type, $post->slug])); ?>" style="color:#111827;text-decoration:none;"
                onmouseover="this.style.color='<?php echo e($borderColor); ?>'" onmouseout="this.style.color='#111827'">
                <?php echo e($cleanTitle); ?>

            </a>
        </div>

        
        <div class="grid grid-cols-2 gap-2 mb-3">

            
            <div class="flex items-start gap-2">
                <div class="w-7 h-7 rounded-md flex items-center justify-center flex-shrink-0"
                    style="background:#dbeafe;color:#2563eb;">
                    <i class="fa-solid fa-calendar text-[11px]"></i>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 leading-tight">Posted</p>
                    <p class="text-xs font-medium text-gray-800"><?php echo e($post->created_at->format('d M Y')); ?></p>
                </div>
            </div>

            
            <?php if($post->last_date): ?>
                <div class="flex items-start gap-2">
                    <?php if($isUrgent): ?>
                        <div class="w-7 h-7 rounded-md flex items-center justify-center flex-shrink-0"
                            style="background:#fee2e2;color:#dc2626;">
                            <i class="fa-solid fa-clock text-[11px] animate-pulse"></i>
                        </div>
                    <?php else: ?>
                        <div class="w-7 h-7 rounded-md flex items-center justify-center flex-shrink-0"
                            style="background:#fef3c7;color:#d97706;">
                            <i class="fa-solid fa-clock text-[11px]"></i>
                        </div>
                    <?php endif; ?>
                    <div>
                        <p class="text-[10px] text-gray-400 leading-tight">Last date</p>
                        <?php if($isUrgent): ?>
                            <p class="text-xs font-medium text-red-700"><?php echo e($post->last_date->format('d M Y')); ?></p>
                        <?php else: ?>
                            <p class="text-xs font-medium text-gray-800"><?php echo e($post->last_date->format('d M Y')); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            
            <?php if($post->total_posts): ?>
                <div class="flex items-start gap-2">
                    <div class="w-7 h-7 rounded-md flex items-center justify-center flex-shrink-0"
                        style="background:#dcfce7;color:#16a34a;">
                        <i class="fa-solid fa-briefcase text-[11px]"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 leading-tight">Vacancies</p>
                        <p class="text-xs font-medium text-gray-800"><?php echo e(number_format($post->total_posts)); ?> posts</p>
                    </div>
                </div>
            <?php endif; ?>

            
            <?php if($post->category): ?>
                <div class="flex items-start gap-2">
                    <div class="w-7 h-7 rounded-md flex items-center justify-center flex-shrink-0"
                        style="background:#f3e8ff;color:#9333ea;">
                        <i class="fa-solid fa-tag text-[11px]"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 leading-tight">Category</p>
                        <p class="text-xs font-medium text-gray-800"><?php echo e($post->category->name); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            
            <?php if($post->education && count($post->education) > 0): ?>
                <div class="col-span-2 flex items-start gap-2">
                    <div class="w-7 h-7 rounded-md flex items-center justify-center flex-shrink-0"
                        style="background:#ccfbf1;color:#0d9488;">
                        <i class="fa-solid fa-graduation-cap text-[11px]"></i>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 leading-tight">Education</p>
                        <p class="text-xs font-medium text-gray-800">
                            <?php echo e(implode(' · ', array_map(fn($e) => $eduLabels[$e] ?? ucfirst(str_replace('_', ' ', $e)), $post->education))); ?>

                        </p>
                    </div>
                </div>
            <?php endif; ?>

        </div>

        
        <div class="flex gap-2 pt-2.5 border-t border-gray-100 relative z-10">
            
            <a href="<?php echo e(route('posts.show', [$post->type, $post->slug])); ?>"
                class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-semibold rounded-lg transition-all"
                style="background:<?php echo e($headerBg); ?>;color:<?php echo e($borderColor); ?>;border:1.5px solid <?php echo e($headerBorder); ?>;text-decoration:none;"
                onmouseover="this.style.background='<?php echo e($borderColor); ?>';this.style.color='#fff'"
                onmouseout="this.style.background='<?php echo e($headerBg); ?>';this.style.color='<?php echo e($borderColor); ?>'">
                <i class="fa-solid fa-eye" style="font-size:10px;"></i> View Details
            </a>
            
            <a href="<?php echo e(route('posts.show', [$post->type, $post->slug])); ?>#apply"
                class="flex-1 flex items-center justify-center gap-1.5 px-3 py-2 text-xs font-semibold rounded-lg transition-all"
                style="background:<?php echo e($cardCta['bg']); ?>;color:<?php echo e($cardCta['color']); ?>;border:1.5px solid <?php echo e($cardCta['border']); ?>;text-decoration:none;"
                onmouseover="this.style.background='<?php echo e($cardCta['hover']); ?>';this.style.color='#fff'"
                onmouseout="this.style.background='<?php echo e($cardCta['bg']); ?>';this.style.color='<?php echo e($cardCta['color']); ?>'">
                <i class="fa-solid <?php echo e($cardCta['icon']); ?>" style="font-size:10px;"></i> <?php echo e($cardCta['text']); ?>

            </a>
        </div>

    </div>
</div><?php /**PATH C:\xampp\htdocs\job\loksewaalert-portal\resources\views/components/post-card.blade.php ENDPATH**/ ?>