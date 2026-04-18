<?php

namespace App\Modules\Settings\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Modules\Settings\Http\Requests\UpdateSettingsRequest;
use App\Modules\Settings\Services\SettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function __construct(private readonly SettingsService $settingsService)
    {
    }

    public function edit(): View
    {
        $settings = SystemSetting::autoloaded();

        return view('hr.settings.index', [
            'settings' => $settings,
            'timezones' => timezone_identifiers_list(),
        ]);
    }

    public function update(UpdateSettingsRequest $request): RedirectResponse
    {
        $this->settingsService->updateSettings(
            $request->validated(),
            $request->file('company_logo'),
            $request->file('company_favicon')
        );

        return back()->with('success', 'Settings updated successfully.');
    }
}
