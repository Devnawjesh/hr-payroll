<?php

namespace App\Providers;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('partials.template.sidebar', function ($view): void {
            $user = auth()->user();
            $can = fn (string $permission): bool => $user?->hasPermission($permission) ?? false;
            $canAny = fn (array $permissions): bool => $user?->hasAnyPermission($permissions) ?? false;

            $view->with('sidebarState', [
                'isDashboard' => request()->routeIs('dashboard'),
                'isEmployees' => request()->routeIs('departments.*') || request()->routeIs('designations.*'),
                'isUserManagement' => request()->routeIs('users.*') || request()->routeIs('roles.*') || request()->routeIs('permissions.*'),
                'isSettings' => request()->routeIs('settings.*'),

                'canDepartmentView' => $can('department.view'),
                'canDepartmentCreate' => $can('department.create'),
                'canDesignationView' => $can('designation.view'),
                'canDesignationCreate' => $can('designation.create'),

                'canRoleView' => $can('role.view'),
                'canRoleCreate' => $can('role.create'),
                'canRoleUpdate' => $can('role.update'),
                'canRoleAssign' => $can('role.assign'),

                'canUserList' => $canAny(['role.view', 'role.assign']),
                'canUserCreate' => $can('role.assign'),
                'canPermissionsMenu' => $can('role.update'),
                'canUserManagementMenu' => $canAny(['role.view', 'role.create', 'role.update', 'role.assign']),
                'canSettingsView' => $can('settings.view'),
            ]);
        });

        try {
            if (! Schema::hasTable('system_settings')) {
                return;
            }
        } catch (\Throwable) {
            return;
        }

        $settings = SystemSetting::autoloaded();

        if (! empty($settings['app_name'])) {
            Config::set('app.name', $settings['app_name']);
        }

        if (! empty($settings['company_logo'])) {
            Config::set('madpos_ui.logo', $settings['company_logo']);
        }

        if (! empty($settings['company_favicon'])) {
            Config::set('madpos_ui.favicon', $settings['company_favicon']);
        }

        if (! empty($settings['time_zone'])) {
            Config::set('app.timezone', $settings['time_zone']);
            date_default_timezone_set($settings['time_zone']);
        }

        if (! empty($settings['mail_mailer'])) {
            Config::set('mail.default', $settings['mail_mailer']);
        }

        Config::set('mail.mailers.smtp.host', $settings['mail_host'] ?? Config::get('mail.mailers.smtp.host'));
        Config::set('mail.mailers.smtp.port', $settings['mail_port'] ?? Config::get('mail.mailers.smtp.port'));
        Config::set('mail.mailers.smtp.username', $settings['mail_username'] ?? Config::get('mail.mailers.smtp.username'));
        Config::set('mail.mailers.smtp.password', $settings['mail_password'] ?? Config::get('mail.mailers.smtp.password'));
        Config::set('mail.mailers.smtp.encryption', $settings['mail_encryption'] ?? Config::get('mail.mailers.smtp.encryption'));
        Config::set('mail.from.address', $settings['mail_from_address'] ?? Config::get('mail.from.address'));
        Config::set('mail.from.name', $settings['mail_from_name'] ?? Config::get('mail.from.name'));

        View::share('appSettings', $settings);
    }
}
