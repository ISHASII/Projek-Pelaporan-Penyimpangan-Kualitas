<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $__env->yieldContent('title', 'Dashboard'); ?></title>
    
    <script src="<?php echo e(asset('assets/js/tailwind.min.js')); ?>"></script>
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/flatpickr.min.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/app.css')); ?>">
    <script src="<?php echo e(asset('assets/js/chart.min.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/flatpickr.min.js')); ?>"></script>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <style>
        .responsive-container {
            padding-left: 1rem;
            padding-right: 1rem;
        }

        .responsive-table {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .img-responsive {
            max-width: 100%;
            height: auto;
        }

        @media (max-width: 640px) {
            .text-sm-collapse {
                font-size: 13px;
            }

            .h-16 {
                height: 4rem;
            }

            .lg\:hidden-sm {
                display: block;
            }
        }

        @media (max-width: 640px) {
            .max-w-7xl {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }

            .p-6 {
                padding: 0.5rem !important;
            }

            .px-6 {
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
            }

            .py-4 {
                padding-top: 0.35rem !important;
                padding-bottom: 0.35rem !important;
            }

            .responsive-table table th,
            .responsive-table table td {
                padding: 0.45rem 0.4rem !important;
                font-size: 12px !important;
            }

            .text-sm {
                font-size: 12px !important;
            }

            .text-xs {
                font-size: 11px !important;
            }

            .nav-button {
                padding: 0.35rem 0.6rem !important;
            }

            header .h-10 {
                height: 2.25rem;
            }

            .logo-text {
                font-size: 0.95rem;
            }

            .mobile-optimized {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }

            #delete-modal .w-full.max-w-md {
                max-width: 18rem;
            }

            .form-compact .mb-6 {
                margin-bottom: 0.5rem !important;
            }

            .form-compact .grid>div,
            .form-compact .flex>div {
                margin-bottom: 0 !important;
            }

            .form-compact label {
                margin-bottom: 0.25rem !important;
                font-size: 12px !important;
            }

            .form-compact input,
            .form-compact select,
            .form-compact textarea {
                padding-top: 0.45rem !important;
                padding-bottom: 0.45rem !important;
            }

            .responsive-table table tbody tr {
                display: block;
                margin-bottom: 0.5rem;
                background: #ffffff;
                border-radius: 0.5rem;
                box-shadow: 0 1px 6px rgba(16, 24, 40, 0.06);
                overflow: hidden;
            }

            .responsive-table table tbody tr td {
                display: block;
                width: 100%;
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }

            .responsive-table table thead,
            .responsive-table table thead tr {
                display: none;
            }
        }

        @media (max-width: 640px) {
            .action-text {
                display: none !important;
            }

            .action-icon {
                display: inline-flex !important;
                width: 1.6rem;
                height: 1.6rem;
            }
        }

        @media (min-width: 641px) {
            .action-icon {
                display: none !important;
            }
        }

        .nav-transition {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .mobile-menu-transition {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: top;
        }

        .mobile-menu.closing {
            transform: scaleY(0);
            opacity: 0;
        }

        .mobile-menu.opening {
            transform: scaleY(1);
            opacity: 1;
        }

        .nav-button {
            position: relative;
            overflow: hidden;
        }

        .nav-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transition: left 0.5s;
        }

        .nav-button:hover::before {
            left: 100%;
        }

        .nav-button:focus {
            outline: 2px solid rgba(255, 255, 255, 0.5);
            outline-offset: 2px;
        }

        .logo-text {
            background: linear-gradient(45deg, #ffffff, #fecaca);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        @media (max-width: 768px) {
            .mobile-optimized {
                padding-left: 1rem;
                padding-right: 1rem;
            }
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }

        .icon-white {
            filter: invert(1) brightness(2) saturate(0);

            image-rendering: -webkit-optimize-contrast;
        }
    </style>
</head>

<body class="font-sans bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen antialiased">

    <?php
        $user = auth()->user();
        $role = strtolower(preg_replace('/[\s_\-]/', '', $user->role ?? 'qc'));

        $roleMap = [
            'qc' => 'qc',
            'quality' => 'qc',
            'foreman' => 'foreman',
            'vdd' => 'vdd',
            'sect' => 'secthead',
            'dept' => 'depthead',
            'ppc' => 'ppchead',
            'agm' => 'agm',
            'procure' => 'procurement',
        ];

        $roleKey = 'qc';
        foreach ($roleMap as $key => $val) {
            if (str_contains($role, $key)) {
                $roleKey = $val;
                break;
            }
        }

        $lpkRoute = $roleKey . '.lpk.index';
        $nqrRoute = $roleKey . '.nqr.index';
        $cmrRoute = $roleKey . '.cmr.index';
        $isLimitedRole = in_array($roleKey, ['agm', 'procurement']);
        $isForeman = $roleKey === 'foreman' || str_contains($role, 'foreman');
        $isVdd = $roleKey === 'vdd' || str_contains($role, 'vdd');
        $dashboardRouteName = $isForeman ? 'foreman.dashboard' : 'dashboard';

        $now = now()->setTimezone('Asia/Jakarta');
        $bulanIndonesia = [
            1 => 'JANUARI',
            2 => 'FEBRUARI',
            3 => 'MARET',
            4 => 'APRIL',
            5 => 'MEI',
            6 => 'JUNI',
            7 => 'JULI',
            8 => 'AGUSTUS',
            9 => 'SEPTEMBER',
            10 => 'OKTOBER',
            11 => 'NOVEMBER',
            12 => 'DESEMBER'
        ];
        $tanggalHariIni = $now->format('d');
        $tanggalBulan = $bulanIndonesia[$now->month] ?? 'UNKNOWN';
        $tanggalTahun = $now->year;
        $jamSekarang = $now->format('H:i:s');
        $tanggalBaris1 = "{$tanggalHariIni} {$tanggalBulan} {$tanggalTahun}";
        $jamBaris = $jamSekarang;
    ?>

    <!-- Top Header -->
    <header class="bg-gradient-to-r from-red-600 via-red-600 to-red-700 text-white relative">
        <div
            class="absolute inset-0 bg-black bg-opacity-5 bg-gradient-to-r from-transparent via-white/5 to-transparent">
        </div>

        <div class="w-full px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="flex items-center justify-between h-16 lg:h-18 max-w-screen-xl mx-auto">
                <!-- Logo dan Nav rapat kiri -->
                <!-- Make this container take available space so right-side controls (logout) stay in consistent position -->
                <div class="flex items-center gap-0 flex-1">
                    <div class="flex-shrink-0 flex items-center py-1 mr-2">
                        <img src="<?php echo e(asset('image/KYB.png')); ?>" alt="Kayaba" draggable="false"
                            class="h-10 md:h-11 lg:h-12 max-h-full w-auto object-contain bg-white p-1 rounded-lg shadow-sm" />
                    </div>
                    <nav class="hidden lg:flex items-center gap-2">
                        <a href="<?php echo e(route($dashboardRouteName)); ?>"
                            class="nav-button px-6 py-3 rounded-xl text-sm font-semibold text-white hover:bg-white/15 active:bg-white/20 nav-transition <?php echo e(request()->routeIs($dashboardRouteName) ? 'bg-white/20 shadow-lg' : ''); ?>">
                            DASHBOARD
                        </a>
                        <?php if(!$isVdd && !$isLimitedRole && !$isForeman): ?>
                            <a href="<?php echo e(Route::has($lpkRoute) ? route($lpkRoute) : '#'); ?>"
                                class="nav-button px-6 py-3 rounded-xl text-sm font-semibold text-white hover:bg-white/15 active:bg-white/20 nav-transition <?php echo e(Route::has($lpkRoute) && request()->routeIs($roleKey . '.lpk.*') ? 'bg-white/20 shadow-lg' : ''); ?>">
                                LPK
                            </a>
                        <?php endif; ?>

                        <?php if($isVdd || $isForeman || !$isLimitedRole || $roleKey === 'procurement'): ?>
                            <a href="<?php echo e(Route::has($nqrRoute) ? route($nqrRoute) : '#'); ?>"
                                class="nav-button px-6 py-3 rounded-xl text-sm font-semibold text-white hover:bg-white/15 active:bg-white/20 nav-transition <?php echo e(Route::has($nqrRoute) && request()->routeIs($roleKey . '.nqr.*') && !request()->routeIs($dashboardRouteName) ? 'bg-white/20 shadow-lg' : ''); ?>">
                                NQR
                            </a>
                        <?php endif; ?>
                        <?php if($isVdd || !$isForeman): ?>
                            <a href="<?php echo e($isLimitedRole ? route($roleKey . '.cmr.index') : (Route::has($cmrRoute) ? route($cmrRoute) : '#')); ?>"
                                class="nav-button px-6 py-3 rounded-xl text-sm font-semibold text-white hover:bg-white/15 active:bg-white/20 nav-transition <?php echo e(($isLimitedRole && request()->routeIs($roleKey . '.cmr.*')) || (Route::has($cmrRoute) && request()->routeIs($roleKey . '.cmr.*')) ? 'bg-white/20 shadow-lg' : ''); ?>">
                                CMR
                            </a>
                        <?php endif; ?>
                    </nav>
                </div>

                <!-- User & Notifications & Logout -->
                <!-- Prevent this block from shrinking so its position is stable across pages -->
                <div class="hidden lg:flex items-center space-x-4 flex-shrink-0">
                    
                    <?php
                        use Illuminate\Support\Facades\Schema;
                        use Illuminate\Support\Collection;

                        if (Schema::hasTable('notifications') && $user && method_exists($user, 'unreadNotifications')) {
                            $unread = $user->unreadNotifications;
                            $unreadCount = is_countable($unread) ? count($unread) : ($unread->count() ?? 0);
                            $notifications = $unread instanceof Collection ? $unread : collect($unread);
                        } else {
                            $unreadCount = 0;
                            $notifications = collect();
                        }
                    ?>
                    <div class="relative">
                        <button id="notif-button" aria-haspopup="true" aria-expanded="false"
                            class="nav-button inline-flex items-center justify-center p-3 rounded-xl text-white hover:bg-white/10 active:bg-white/20">
                            
                            <span class="relative inline-block">
                                <img src="<?php echo e(asset('icon/notif.ico')); ?>" alt="Notifikasi" class="w-5 h-5 icon-white" />
                                <?php if($unreadCount > 0): ?>
                                    
                                    <span
                                        class="absolute -top-1 -right-1 w-3 h-3 rounded-full bg-yellow-400 ring-1 ring-white"
                                        aria-hidden="true"></span>
                                <?php endif; ?>
                            </span>
                            <?php if($unreadCount > 0): ?>
                                <span class="ml-2 sr-only">Anda memiliki <?php echo e($unreadCount); ?> notifikasi belum dibaca</span>
                            <?php endif; ?>
                        </button>

                        
                        <div id="notif-dropdown" role="menu" aria-hidden="true"
                            class="hidden origin-top-right absolute right-0 mt-2 w-80 bg-white text-gray-800 rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 overflow-hidden z-50">
                            <div class="px-4 py-3 border-b text-sm font-semibold">Notifikasi</div>
                            <div class="max-h-64 overflow-y-auto">
                                <?php $__empty_1 = true; $__currentLoopData = $notifications->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php
                                        // Prefer constructing an Indonesian message when LPK info is available
                                        $noteUrl = $note->data['url'] ?? '#';
                                        $noReg = $note->data['lpk_no_reg'] ?? null;
                                        $act = strtolower($note->data['action'] ?? '');
                                        $actorRoleRaw = $note->data['actor_role'] ?? ($note->data['actorRole'] ?? '');
                                        $noteText = $note->data['message'] ?? null;

                                        // map action to Indonesian
                                        $actionLabel = $act === 'approved' ? 'disetujui' : (($act === 'rejected') ? 'ditolak' : $act);

                                        // map actor role to Indonesian label
                                        $actorRoleLower = strtolower($actorRoleRaw);
                                        if (Str::contains($actorRoleLower, 'sect')) {
                                            $actorLabel = 'Kepala Seksi';
                                        } elseif (Str::contains($actorRoleLower, 'dept')) {
                                            $actorLabel = 'Kepala Departemen';
                                        } elseif (Str::contains($actorRoleLower, 'ppc')) {
                                            $actorLabel = 'Kepala PPC';
                                        } elseif (Str::contains($actorRoleLower, 'qc') || Str::contains($actorRoleLower, 'quality')) {
                                            $actorLabel = 'Quality Control';
                                        } else {
                                            $actorLabel = Str::title($actorRoleRaw ?: ($note->data['actor_name'] ?? ''));
                                        }

                                        if ($noReg && $actionLabel && $actorLabel) {
                                            $noteTitle = "LPK {$noReg} telah {$actionLabel} oleh {$actorLabel}";
                                            $noteMessage = $noteText ? $noteText : '';
                                        } else {
                                            // fallback to stored title/message
                                            $noteTitle = $note->data['title'] ?? $note->data['message'] ?? ($note->type ?? 'Notifikasi');
                                            $noteMessage = $note->data['message'] ?? '';
                                        }
                                    ?>
                                    <?php
                                        // Determine actor initial and color based on notification data
                                        $actorRole = strtolower($note->data['actor_role'] ?? '');
                                        $action = strtolower($note->data['action'] ?? '');
                                        $actorInitial = 'N';
                                        if (Str::contains($actorRole, 'sect'))
                                            $actorInitial = 'S';
                                        elseif (Str::contains($actorRole, 'dept'))
                                            $actorInitial = 'D';
                                        elseif (Str::contains($actorRole, 'ppc'))
                                            $actorInitial = 'P';
                                        elseif (Str::contains($actorRole, 'qc') || Str::contains($actorRole, 'quality'))
                                            $actorInitial = 'Q';

                                        $badgeBg = 'bg-yellow-100 text-yellow-700';
                                        if ($action === 'approved') {
                                            $badgeBg = 'bg-green-100 text-green-700';
                                        } elseif ($action === 'rejected') {
                                            $badgeBg = 'bg-red-100 text-red-700';
                                        }
                                    ?>
                                    <a href="<?php echo e($noteUrl); ?>" data-notif-id="<?php echo e($note->id); ?>"
                                        class="flex items-start gap-3 px-4 py-3 hover:bg-gray-50 <?php echo e($action === 'rejected' ? 'border-l-4 border-red-200 shadow-sm' : ($action === 'approved' ? 'border-l-4 border-green-200 shadow-sm' : '')); ?>">
                                        <div
                                            class="w-8 h-8 <?php echo e($badgeBg); ?> rounded-full flex items-center justify-center text-sm font-semibold">
                                            <?php echo e($actorInitial); ?>

                                        </div>
                                        <div class="flex-1 text-sm">
                                            <div class="font-medium text-gray-900"><?php echo e(Str::limit($noteTitle, 60)); ?></div>
                                            <?php if($noteMessage): ?>
                                                <div class="text-xs text-gray-500"><?php echo e(Str::limit($noteMessage, 80)); ?></div>
                                            <?php endif; ?>
                                            <div class="text-xs text-gray-400 mt-1">
                                                <?php echo e($note->created_at->diffForHumans() ?? ''); ?>

                                            </div>
                                        </div>
                                    </a>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <div class="px-4 py-4 text-sm text-gray-500">Tidak ada notifikasi.</div>
                                <?php endif; ?>
                            </div>
                            <div id="notif-footer" class="border-t px-3 py-2 text-center text-sm bg-gray-50">
                                <button id="mark-all-read" type="button"
                                    class="text-sm text-gray-700 hover:underline">Tandai semua telah dibaca</button>
                            </div>
                        </div>
                    </div>
                    <div class="text-right border-r border-white/20 pr-4 self-center">
                        <div class="text-sm font-semibold tracking-wide"><?php echo e($user->name ?? 'Guest'); ?></div>
                        <div class="text-xs opacity-80 font-medium"><?php echo e(ucfirst($user->role ?? 'unknown')); ?></div>
                    </div>

                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="logout-form self-center mr-4">
                        <?php echo csrf_field(); ?>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2.5 bg-white/10 text-white rounded-xl text-sm font-semibold hover:bg-white/20 active:bg-white/30 nav-transition border border-white/30 backdrop-blur-sm shadow-lg">
                            <img src="<?php echo e(asset('icon/logout.ico')); ?>" alt="Logout" class="w-4 h-4 mr-2 icon-white" />
                            Logout
                        </button>
                    </form>

                    <div class="text-xs opacity-70 font-medium self-center text-right">
                        <?php echo e($tanggalBaris1); ?>

                        <div id="desktop-time"><?php echo e($jamBaris); ?></div>
                    </div>
                </div>

                <div class="lg:hidden flex items-center gap-2">
                    <?php
                        if (Schema::hasTable('notifications') && $user && method_exists($user, 'unreadNotifications')) {
                            $unreadMobile = $user->unreadNotifications;
                            $unreadCountMobile = is_countable($unreadMobile) ? count($unreadMobile) : ($unreadMobile->count() ?? 0);
                        } else {
                            $unreadMobile = collect();
                            $unreadCountMobile = 0;
                        }
                    ?>

                    <button id="mobile-notif-button" aria-haspopup="true" aria-expanded="false"
                        class="inline-flex items-center justify-center p-2 rounded-xl text-white hover:bg-white/15 active:bg-white/25 nav-transition border border-white/20 backdrop-blur-sm">
                        <span class="relative inline-block">
                            <img src="<?php echo e(asset('icon/notif.ico')); ?>" alt="Notifikasi" class="w-5 h-5 icon-white" />
                            <?php if($unreadCountMobile > 0): ?>
                                <span class="absolute -top-1 -right-1 w-3 h-3 rounded-full bg-yellow-400 ring-1 ring-white"
                                    aria-hidden="true"></span>
                            <?php endif; ?>
                        </span>
                    </button>

                    <button id="mobile-menu-button"
                        class="inline-flex items-center justify-center p-3 rounded-xl text-white hover:bg-white/15 active:bg-white/25 nav-transition border border-white/20 backdrop-blur-sm">
                        <img id="menu-icon" src="<?php echo e(asset('icon/hamburger.ico')); ?>" alt="menu"
                            class="w-6 h-6 nav-transition icon-white" />
                        <img id="close-icon" src="<?php echo e(asset('icon/close.ico')); ?>" alt="close"
                            class="w-6 h-6 hidden nav-transition icon-white" />
                    </button>

                    
                    <div id="mobile-notif-list"
                        class="hidden absolute right-4 top-16 mt-2 max-h-60 overflow-y-auto rounded-lg bg-white text-gray-800 ring-1 ring-black ring-opacity-5 z-50">
                        <?php $__empty_1 = true; $__currentLoopData = ($unreadMobile ?: collect())->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $note): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $noteUrl = $note->data['url'] ?? '#';
                                $noReg = $note->data['lpk_no_reg'] ?? null;
                                $act = strtolower($note->data['action'] ?? '');
                                $actorRoleRaw = $note->data['actor_role'] ?? ($note->data['actorRole'] ?? '');
                                $noteText = $note->data['message'] ?? null;

                                $actionLabel = $act === 'approved' ? 'disetujui' : (($act === 'rejected') ? 'ditolak' : $act);

                                $actorRoleLower = strtolower($actorRoleRaw);
                                if (Str::contains($actorRoleLower, 'sect')) {
                                    $actorLabel = 'Kepala Seksi';
                                } elseif (Str::contains($actorRoleLower, 'dept')) {
                                    $actorLabel = 'Kepala Departemen';
                                } elseif (Str::contains($actorRoleLower, 'ppc')) {
                                    $actorLabel = 'Kepala PPC';
                                } elseif (Str::contains($actorRoleLower, 'qc') || Str::contains($actorRoleLower, 'quality')) {
                                    $actorLabel = 'Quality Control';
                                } else {
                                    $actorLabel = Str::title($actorRoleRaw ?: ($note->data['actor_name'] ?? ''));
                                }

                                if ($noReg && $actionLabel && $actorLabel) {
                                    $noteTitle = "LPK {$noReg} telah {$actionLabel} oleh {$actorLabel}";
                                    $noteMessage = $noteText ? $noteText : '';
                                } else {
                                    $noteTitle = $note->data['title'] ?? $note->data['message'] ?? ($note->type ?? 'Notifikasi');
                                    $noteMessage = $note->data['message'] ?? '';
                                }
                            ?>
                            <?php
                                $actorRole = strtolower($note->data['actor_role'] ?? '');
                                $action = strtolower($note->data['action'] ?? '');
                                $actorInitial = 'N';
                                if (Str::contains($actorRole, 'sect'))
                                    $actorInitial = 'S';
                                elseif (Str::contains($actorRole, 'dept'))
                                    $actorInitial = 'D';
                                elseif (Str::contains($actorRole, 'ppc'))
                                    $actorInitial = 'P';
                                elseif (Str::contains($actorRole, 'qc') || Str::contains($actorRole, 'quality'))
                                    $actorInitial = 'Q';

                                $badgeBg = 'bg-yellow-100 text-yellow-700';
                                if ($action === 'approved') {
                                    $badgeBg = 'bg-green-100 text-green-700';
                                } elseif ($action === 'rejected') {
                                    $badgeBg = 'bg-red-100 text-red-700';
                                }
                            ?>
                            <a href="<?php echo e($noteUrl); ?>" data-notif-id="<?php echo e($note->id); ?>"
                                class="px-4 py-3 border-b hover:bg-gray-50 flex items-start gap-3 <?php echo e($action === 'rejected' ? 'border-l-4 border-red-200 shadow-sm' : ($action === 'approved' ? 'border-l-4 border-green-200 shadow-sm' : '')); ?>">
                                <div
                                    class="w-8 h-8 <?php echo e($badgeBg); ?> rounded-full flex items-center justify-center text-sm font-semibold">
                                    <?php echo e($actorInitial); ?>

                                </div>
                                <div class="flex-1">
                                    <div class="text-sm font-medium"><?php echo e(Str::limit($noteTitle, 80)); ?></div>
                                    <?php if($noteMessage): ?>
                                        <div class="text-xs text-gray-500"><?php echo e(Str::limit($noteMessage, 120)); ?></div>
                                    <?php endif; ?>
                                </div>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="px-4 py-4 text-sm text-gray-500">Tidak ada notifikasi.</div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu"
            class="lg:hidden hidden mobile-menu mobile-menu-transition border-t border-red-700/50 backdrop-blur-md bg-red-600/95">
            <div class="max-w-7xl mx-auto mobile-optimized py-4 space-y-1 custom-scrollbar max-h-96 overflow-y-auto">

                <a href="<?php echo e(route($dashboardRouteName)); ?>"
                    class="flex items-center px-4 py-3 rounded-xl text-base font-semibold text-white hover:bg-white/15 active:bg-white/25 nav-transition <?php echo e(request()->routeIs($dashboardRouteName) ? 'bg-white/20 shadow-md' : ''); ?>">
                    DASHBOARD
                </a>

                <?php if(!$isVdd && !$isLimitedRole && !$isForeman): ?>
                    <a href="<?php echo e(Route::has($lpkRoute) ? route($lpkRoute) : '#'); ?>"
                        class="flex items-center px-4 py-3 rounded-xl text-base font-semibold text-white hover:bg-white/15 active:bg-white/25 nav-transition <?php echo e(Route::has($lpkRoute) && request()->routeIs($roleKey . '.lpk.*') ? 'bg-white/20 shadow-md' : ''); ?>">
                        LPK
                    </a>
                <?php endif; ?>

                <?php if($isVdd || $isForeman || !$isLimitedRole || $roleKey === 'procurement'): ?>
                    <a href="<?php echo e(Route::has($nqrRoute) ? route($nqrRoute) : '#'); ?>"
                        class="flex items-center px-4 py-3 rounded-xl text-base font-semibold text-white hover:bg-white/15 active:bg-white/25 nav-transition <?php echo e(Route::has($nqrRoute) && request()->routeIs($roleKey . '.nqr.*') && !request()->routeIs($dashboardRouteName) ? 'bg-white/20 shadow-md' : ''); ?>">
                        NQR
                    </a>
                <?php endif; ?>

                <?php if($isVdd || !$isForeman): ?>
                    <a href="<?php echo e($isLimitedRole ? route($roleKey . '.cmr.index') : (Route::has($cmrRoute) ? route($cmrRoute) : '#')); ?>"
                        class="flex items-center px-4 py-3 rounded-xl text-base font-semibold text-white hover:bg-white/15 active:bg-white/25 nav-transition <?php echo e(($isLimitedRole && request()->routeIs($roleKey . '.cmr.*')) || (Route::has($cmrRoute) && request()->routeIs($roleKey . '.cmr.*')) ? 'bg-white/20 shadow-md' : ''); ?>">
                        CMR
                    </a>
                <?php endif; ?>

                <!-- Mobile User -->
                <div class="pt-4 border-t border-white/20 mt-4">
                    <div class="px-4 py-3 rounded-xl bg-white/10 backdrop-blur-sm">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                                <img src="<?php echo e(asset('icon/akun.ico')); ?>" alt="User" class="w-6 h-6 icon-white" />
                            </div>
                            <div class="flex-1">
                                <div class="text-base font-semibold text-white"><?php echo e($user->name ?? 'Guest'); ?></div>
                                <div class="text-sm text-white/80 font-medium">Role:
                                    <?php echo e(ucfirst($user->role ?? 'unknown')); ?>

                                </div>
                                <div class="text-xs text-white/70 font-medium mt-1 text-right">
                                    <?php echo e($tanggalBaris1); ?>

                                    <div id="mobile-time"><?php echo e($jamBaris); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <form method="POST" action="<?php echo e(route('logout')); ?>" class="mt-3 logout-form">
                        <?php echo csrf_field(); ?>
                        <button type="submit"
                            class="w-full flex items-center justify-center px-4 py-3 bg-white/10 text-white rounded-xl text-sm font-semibold hover:bg-white/20 active:bg-white/30 nav-transition border border-white/30 backdrop-blur-sm shadow-md">
                            <img src="<?php echo e(asset('icon/logout.ico')); ?>" alt="Logout" class="w-4 h-4 mr-2 icon-white" />
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>
    <!-- Full-width decorative yellow stripe under the navbar (sharp corners) -->
    <div class="w-full h-2 bg-yellow-400 shadow-sm rounded-none" style="margin-top:-2px;"></div>

    <!-- Content -->
    <main class="w-full m-0 p-0">
        <div class="w-full">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </main>

    <!-- Mobile Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');
            const menuIcon = document.getElementById('menu-icon');
            const closeIcon = document.getElementById('close-icon');
            let isMenuOpen = false;

            mobileMenuButton.addEventListener('click', function () {
                if (!isMenuOpen) {
                    mobileMenu.classList.remove('hidden');
                    mobileMenu.classList.add('opening');
                    mobileMenu.classList.remove('closing');
                    menuIcon.classList.add('hidden');
                    closeIcon.classList.remove('hidden');
                    isMenuOpen = true;
                } else {
                    mobileMenu.classList.add('closing');
                    mobileMenu.classList.remove('opening');
                    menuIcon.classList.remove('hidden');
                    closeIcon.classList.add('hidden');

                    setTimeout(() => {
                        mobileMenu.classList.add('hidden');
                        mobileMenu.classList.remove('closing');
                        isMenuOpen = false;
                    }, 300);
                }
            });

            const mobileLinks = mobileMenu.querySelectorAll('a');
            mobileLinks.forEach(link => {
                link.addEventListener('click', function () {
                    mobileMenu.classList.add('closing');
                    mobileMenu.classList.remove('opening');
                    menuIcon.classList.remove('hidden');
                    closeIcon.classList.add('hidden');

                    setTimeout(() => {
                        mobileMenu.classList.add('hidden');
                        mobileMenu.classList.remove('closing');
                        isMenuOpen = false;
                    }, 300);
                });
            });
        });
    </script>

    <!-- Realtime Clock Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            function updateTime() {
                const now = new Date().toLocaleString('en-US', {
                    timeZone: 'Asia/Jakarta',
                    hour12: false,
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
                const desktopTime = document.getElementById('desktop-time');
                const mobileTime = document.getElementById('mobile-time');
                if (desktopTime) desktopTime.textContent = now;
                if (mobileTime) mobileTime.textContent = now;
            }

            updateTime(); // Initial call
            setInterval(updateTime, 1000); // Update every second
        });
    </script>

    <!-- Logout Confirmation Modal -->
    <div id="logout-modal" class="fixed inset-0 z-50 hidden" aria-hidden="true">
        <div class="absolute inset-0 modal-overlay bg-black/50 backdrop-blur-sm"></div>
        <!-- Centering container: absolute + flex ensures perfect centering regardless of page content -->
        <div class="absolute inset-0 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-lg w-full max-w-md sm:max-w-lg p-6 mx-2 ring-1 ring-gray-100">
                <div class="text-left">
                    <h3 class="text-lg font-semibold text-gray-900">Yakin ingin logout?</h3>
                    <p class="text-sm text-gray-600 mt-2">Anda akan dikeluarkan dari sesi saat ini.</p>
                </div>

                <div class="mt-6 border-t border-gray-100 pt-4 flex flex-col sm:flex-row sm:justify-end gap-3">
                    <button type="button" id="logout-cancel"
                        class="w-full sm:w-auto px-4 py-2 rounded-lg bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-gray-200">Batal</button>
                    <button type="button" id="logout-confirm"
                        class="w-full sm:w-auto px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-red-300">Keluar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Logout confirmation: intercept forms with .logout-form
        document.addEventListener('DOMContentLoaded', function () {
            const logoutForms = Array.from(document.querySelectorAll('form.logout-form'));
            const modal = document.getElementById('logout-modal');
            const confirmBtn = document.getElementById('logout-confirm');
            const cancelBtn = document.getElementById('logout-cancel');
            let pendingForm = null;

            if (!modal) return;

            logoutForms.forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    pendingForm = this;
                    modal.classList.remove('hidden');
                });
            });

            cancelBtn.addEventListener('click', function () {
                modal.classList.add('hidden');
                pendingForm = null;
            });

            confirmBtn.addEventListener('click', function () {
                if (pendingForm) {
                    // submit the original form programmatically
                    pendingForm.removeEventListener('submit', function () { });
                    pendingForm.submit();
                }
            });

            // Close modal when clicking the overlay
            const overlay = document.querySelector('.modal-overlay');
            if (overlay) {
                overlay.addEventListener('click', function () {
                    modal.classList.add('hidden');
                    pendingForm = null;
                });
            }

            // Close modal on Escape key
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                    modal.classList.add('hidden');
                    pendingForm = null;
                }
            });
        });
    </script>
    </script>

    
    <?php $__env->startPush('scripts'); ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                // Desktop notification dropdown
                const notifBtn = document.getElementById('notif-button');
                const notifDropdown = document.getElementById('notif-dropdown');

                if (notifBtn) {
                    notifBtn.addEventListener('click', function (e) {
                        e.stopPropagation();
                        const expanded = this.getAttribute('aria-expanded') === 'true';
                        if (!expanded) {
                            notifDropdown.classList.remove('hidden');
                            this.setAttribute('aria-expanded', 'true');
                        } else {
                            notifDropdown.classList.add('hidden');
                            this.setAttribute('aria-expanded', 'false');
                        }
                    });

                    // Close when clicking outside
                    document.addEventListener('click', function () {
                        if (notifDropdown) notifDropdown.classList.add('hidden');
                        notifBtn.setAttribute('aria-expanded', 'false');
                    });

                    // Prevent dropdown click from closing
                    if (notifDropdown) notifDropdown.addEventListener('click', function (e) { e.stopPropagation(); });
                }

                // Mobile notification toggle
                const mobileNotifBtn = document.getElementById('mobile-notif-button');
                const mobileNotifList = document.getElementById('mobile-notif-list');
                if (mobileNotifBtn) {
                    mobileNotifBtn.addEventListener('click', function (e) {
                        e.stopPropagation();
                        if (mobileNotifList.classList.contains('hidden')) {
                            mobileNotifList.classList.remove('hidden');
                        } else {
                            mobileNotifList.classList.add('hidden');
                        }
                    });

                    document.addEventListener('click', function () {
                        if (mobileNotifList) mobileNotifList.classList.add('hidden');
                    });

                    if (mobileNotifList) mobileNotifList.addEventListener('click', function (e) { e.stopPropagation(); });
                }

                // Close on Escape
                document.addEventListener('keydown', function (e) {
                    if (e.key === 'Escape') {
                        if (notifDropdown) notifDropdown.classList.add('hidden');
                        if (mobileNotifList) mobileNotifList.classList.add('hidden');
                        if (notifBtn) notifBtn.setAttribute('aria-expanded', 'false');
                    }
                });

                // Mark individual notification as read when clicked
                const notifAnchors = document.querySelectorAll('[data-notif-id]');
                notifAnchors.forEach(a => {
                    a.addEventListener('click', function (e) {
                        // Let link behave normally after marking as read
                        e.preventDefault();
                        const id = this.getAttribute('data-notif-id');
                        const href = this.getAttribute('href') || '#';
                        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
                        if (!id || !token) {
                            window.location.href = href;
                            return;
                        }

                        fetch(`<?php echo e(url('/notifications')); ?>/${id}/mark-read`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({})
                        }).then(res => res.json()).then(data => {
                            // On success, remove unread dot and sr-only text and navigate
                            if (data.status === 'ok') {
                                // remove dots
                                document.querySelectorAll('#notif-button .absolute.bg-yellow-400, #mobile-notif-button .absolute.bg-yellow-400').forEach(d => d.remove());
                                // remove sr-only
                                document.querySelectorAll('#notif-button .sr-only, #mobile-notif-button .sr-only').forEach(s => s.remove());
                            }
                            window.location.href = href;
                        }).catch(err => {
                            console.error(err);
                            window.location.href = href;
                        });
                    });
                });
            });
        </script>
    <?php $__env->stopPush(); ?>

    
    <?php echo $__env->yieldPushContent('scripts'); ?>

    <script>
        // Mark all notifications read via AJAX and update UI
        document.addEventListener('DOMContentLoaded', function () {
            const markAllBtn = document.getElementById('mark-all-read');
            const notifBtn = document.getElementById('notif-button');

            async function markAllRead() {
                try {
                    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    const res = await fetch("<?php echo e(route('notifications.markAllRead')); ?>", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({})
                    });

                    const data = await res.json();
                    if (res.ok && data.status === 'ok') {
                        // Remove visual unread indicator(s)
                        // Desktop: small dot (absolute element inside notif-button)
                        const dots = document.querySelectorAll('#notif-button .absolute.bg-yellow-400, #mobile-notif-button .absolute.bg-yellow-400');
                        dots.forEach(d => d.remove());

                        // remove sr-only text if present
                        const sr = document.querySelectorAll('#notif-button .sr-only, #mobile-notif-button .sr-only');
                        sr.forEach(s => s.remove());

                        // Optionally close dropdown
                        const notifDropdown = document.getElementById('notif-dropdown');
                        if (notifDropdown) notifDropdown.classList.add('hidden');

                        // Update aria-expanded
                        if (notifBtn) notifBtn.setAttribute('aria-expanded', 'false');
                    } else {
                        console.error('Failed to mark notifications read', data.message || data);
                        alert('Gagal menandai notifikasi sebagai dibaca. Silakan coba lagi.');
                    }
                } catch (err) {
                    console.error(err);
                    alert('Terjadi kesalahan saat menandai notifikasi.');
                }
            }

            if (markAllBtn) {
                markAllBtn.addEventListener('click', function (e) {
                    e.preventDefault();
                    markAllRead();
                });
            }
        });
    </script>

</body>

</html><?php /**PATH C:\Users\ilham\Documents\PROJEK-LPK\resources\views/layouts/navbar.blade.php ENDPATH**/ ?>