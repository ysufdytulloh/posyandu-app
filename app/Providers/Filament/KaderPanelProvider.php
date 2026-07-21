<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class KaderPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('kader')
            ->path('kader')
            ->login(\App\Filament\Kader\Pages\Login::class)

            ->colors([
                'primary' => Color::Blue,
            ])
            ->darkMode(false)
            ->brandName('SIP Posyandu')
            ->sidebarCollapsibleOnDesktop()
            ->discoverResources(in: app_path('Filament/Kader/Resources'), for: 'App\\Filament\\Kader\\Resources')
            ->discoverPages(in: app_path('Filament/Kader/Pages'), for: 'App\\Filament\\Kader\\Pages')
            ->discoverWidgets(in: app_path('Filament/Kader/Widgets'), for: 'App\\Filament\\Kader\\Widgets')
            ->pages([
                \App\Filament\Kader\Pages\Dashboard::class,
            ])
            ->navigationGroups([
                \Filament\Navigation\NavigationGroup::make('Posyandu')
                    ->icon('heroicon-o-building-office-2')
                    ->collapsed(true),
                \Filament\Navigation\NavigationGroup::make('Kesehatan Ibu & Anak')
                    ->icon('heroicon-o-heart')
                    ->collapsed(true),
                \Filament\Navigation\NavigationGroup::make('Kesehatan Lansia')
                    ->icon('heroicon-o-user-group')
                    ->collapsed(true),
                \Filament\Navigation\NavigationGroup::make('PMT')
                    ->icon('heroicon-o-cake')
                    ->collapsed(true),
                \Filament\Navigation\NavigationGroup::make('Laporan')
                    ->icon('heroicon-o-document-chart-bar')
                    ->collapsed(true),
            ])
            ->widgets([
                \App\Filament\Kader\Widgets\KaderStatsOverview::class,
                \App\Filament\Kader\Widgets\KaderTimbangChart::class,
            ])
            ->authGuard('web')
            ->authMiddleware([
                Authenticate::class,
                \App\Http\Middleware\EnsureUserIsKader::class,
            ])
            ->widgets([])
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
            ])
            ->databaseNotifications()
            ->databaseNotificationsPolling('30s')
            ->userMenuItems([
                'edit-profil' => \Filament\Navigation\MenuItem::make()
                    ->label('Edit Profil')
                    ->url(fn () => \App\Filament\Kader\Pages\EditProfileKader::getUrl())
                    ->icon('heroicon-o-pencil-square'),
            ])

            ->viteTheme([
                'resources/css/filament/kader/theme.css',
                'resources/js/app.js',
            ]);
    }
}
