<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Navigation\NavigationGroup;
use App\Filament\Pages\Auth\Login;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->unsavedChangesAlerts()
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->globalSearch(true)
            ->globalSearchKeyBindings(['command+k', 'ctrl+k'])
            ->globalSearchDebounce('300ms')
            ->brandName('SIP Posyandu')
            ->sidebarCollapsibleOnDesktop()
            ->darkMode(false)
           ->userMenuItems([
                'edit-profil' => \Filament\Navigation\MenuItem::make()
                    ->label('Edit Profil')
                    ->url(fn () => \App\Filament\Pages\EditProfile::getUrl())
                    ->icon('heroicon-o-pencil-square'),
            ])
            ->viteTheme([
                'resources/css/filament/admin/theme.css',
                'resources/js/app.js',
            ])
            ->colors([
                'primary' => Color::hex('#059669'),
            ])
            ->navigationGroups([
                \Filament\Navigation\NavigationGroup::make('Posyandu')
                    ->icon('heroicon-o-building-office'),
                \Filament\Navigation\NavigationGroup::make('Kesehatan Ibu & Anak')
                    ->icon('heroicon-o-heart'),
                \Filament\Navigation\NavigationGroup::make('Kesehatan Lansia')
                    ->icon('heroicon-o-user-circle'),
                \Filament\Navigation\NavigationGroup::make('PMT')
                    ->icon('heroicon-o-gift'),
                \Filament\Navigation\NavigationGroup::make('Laporan')
                    ->icon('heroicon-o-document-chart-bar'),
                \Filament\Navigation\NavigationGroup::make('Pengaturan')
                    ->icon('heroicon-o-cog-6-tooth'),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                \App\Filament\Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
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
}
