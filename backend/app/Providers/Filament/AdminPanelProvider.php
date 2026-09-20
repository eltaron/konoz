<?php

namespace App\Providers\Filament;

use App\Filament\Widgets\LatestStudents;
use App\Filament\Widgets\RecentCertificatesTable;
use App\Filament\Widgets\SessionsChart;
use App\Filament\Widgets\StatsOverview;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use App\Filament\Admin\Dashboard;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Blade;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::hex('#0f6d80'),
                'accent' => Color::hex('#d89b1d'),
            ])
            ->brandName('منصة كُنوز التعليمية')
            ->brandLogo(asset('images/logo.png'))
            ->brandLogoHeight('2.5rem')
            ->favicon(asset('favicon.ico'))
            ->font('Cairo')
            ->darkMode(true)
            ->sidebarCollapsibleOnDesktop()
            ->navigationGroups([
                NavigationGroup::make('إدارة التعليم')
                    ->icon('heroicon-o-book-open'),
                NavigationGroup::make('الطلاب والكادر')
                    ->icon('heroicon-o-user-group'),
                NavigationGroup::make('واجهة الموقع')
                    ->icon('heroicon-o-globe-alt'),
                NavigationGroup::make('الدعم والتواصل')
                    ->icon('heroicon-o-chat-bubble-left-right'),
                NavigationGroup::make('الإعدادات')
                    ->icon('heroicon-o-cog-6-tooth'),
            ])
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->globalSearch(true)

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                \App\Filament\Widgets\StatsOverview::class,
                \App\Filament\Widgets\SessionsChart::class,
                \App\Filament\Widgets\LatestStudents::class,
                RecentCertificatesTable::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }

    public function boot(): void
    {
        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_START,
            fn() => Blade::render(
                <<<'BLADE'
<link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
:root {
    --accent: #d89b1d;
    --accent-rgb: 216, 155, 29;
    --primary: #0f6d80;
    --primary-hover: #0c5665;
    --font-family: 'Cairo', 'Tajawal', sans-serif !important;
}
* { font-family: 'Cairo', 'Tajawal', sans-serif !important; }
[dir="rtl"] .fi-logo { gap: 0.5rem !important; }
.fi-logo img { border-radius: 8px; }
[dir="rtl"] .fi-sidebar-header { background: linear-gradient(135deg, #042a33, #094c5a) !important; border-bottom: 1px solid rgba(255,255,255,0.08) !important; }
[dir="rtl"] .fi-sidebar-item-active { background: rgba(15, 109, 128, 0.15) !important; }
[dir="rtl"] .fi-sidebar-item-active .fi-sidebar-item-label,
[dir="rtl"] .fi-sidebar-item-active .fi-icon { color: #0f6d80 !important; }
[dir="rtl"] .fi-topbar { border-bottom: 1px solid rgba(0,0,0,0.05) !important; }
.dark [dir="rtl"] .fi-topbar { background: #1f2937 !important; }
.dark [dir="rtl"] .filament-main { background: #111827; }
[dir="rtl"] .fi-global-search-input { border-radius: 10px !important; }
[dir="rtl"] .fi-btn { border-radius: 10px !important; }
.fi-header-sidebar-toggle-ctn { display: none; align-items: center; width: 2.25rem; margin-inline-end: 0.5rem; }
.fi-topbar-brand-name { font-weight: 800; font-size: 1.05rem; color: #0f6d80; white-space: nowrap; margin-inline-start: 0.75rem; letter-spacing: -0.01em; }
.dark .fi-topbar-brand-name { color: #ffffff; }
@media (min-width: 1024px) {
    .fi-header-sidebar-toggle-ctn { display: flex; }
    .fi-topbar-open-sidebar-btn { display: none !important; }
    .fi-topbar-collapse-sidebar-btn-ctn { display: none !important; }
}
</style>
<meta name="description" content="منصة كُنوز التعليمية - نظام إدارة المنصة التعليمية لتحفيظ القرآن الكريم">
<meta name="author" content="منصة كُنوز التعليمية">
<meta property="og:title" content="منصة كُنوز التعليمية - لوحة التحكم">
<meta property="og:description" content="نظام إدارة المنصة التعليمية لتحفيظ القرآن الكريم">
<meta property="og:image" content="{{ asset('images/logo.png') }}">
<meta name="twitter:card" content="summary_large_image">
BLADE
            ),
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::BODY_START,
            fn() => '<script>document.documentElement.dir="rtl";document.documentElement.lang="ar";</script>',
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_LOGO_AFTER,
            fn() => Blade::render(
                <<<'BLADE'
@if ($brandName = filament()->getBrandName())
<span class="fi-topbar-brand-name">{{ $brandName }}</span>
@endif
BLADE
            ),
        );

        FilamentView::registerRenderHook(
            PanelsRenderHook::TOPBAR_START,
            fn() => Blade::render(
                <<<'BLADE'
<div class="fi-header-sidebar-toggle-ctn">
    <x-filament::icon-button
        color="gray"
        icon="heroicon-m-bars-3"
        icon-size="lg"
        :label="__('filament-panels::layout.actions.sidebar.expand.label')"
        x-cloak
        x-data="{}"
        x-on:click="$store.sidebar.open()"
        x-show="! $store.sidebar.isOpen"
        class="fi-header-sidebar-toggle-btn"
    />
    <x-filament::icon-button
        color="gray"
        icon="heroicon-m-x-mark"
        icon-size="lg"
        :label="__('filament-panels::layout.actions.sidebar.collapse.label')"
        x-cloak
        x-data="{}"
        x-on:click="$store.sidebar.close()"
        x-show="$store.sidebar.isOpen"
        class="fi-header-sidebar-toggle-btn"
    />
</div>
BLADE
            ),
        );
    }
}
