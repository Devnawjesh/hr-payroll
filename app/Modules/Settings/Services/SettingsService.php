<?php

namespace App\Modules\Settings\Services;

use App\Modules\Settings\Repositories\SettingsRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class SettingsService
{
    public function __construct(
        private readonly SettingsRepository $settingsRepository,
        private readonly BrandingAssetService $brandingAssetService
    ) {
    }

    /**
     * @param array<string, mixed> $validated
     */
    public function updateSettings(array $validated, ?UploadedFile $companyLogo, ?UploadedFile $companyFavicon): void
    {
        $newLogoPath = $this->brandingAssetService->store($companyLogo, 'logo');
        $newFaviconPath = $this->brandingAssetService->store($companyFavicon, 'favicon');

        try {
            DB::transaction(function () use ($validated, $newLogoPath, $newFaviconPath): void {
                $this->settingsRepository->upsertMany($this->settingMeta(), $validated);

                if (! empty($validated['mail_password'])) {
                    $this->settingsRepository->put('mail_password', $validated['mail_password'], [
                        'group_name' => 'smtp',
                        'is_encrypted' => true,
                    ]);
                }

                if ($newLogoPath !== null) {
                    $oldLogoPath = $this->settingsRepository->getValue('company_logo');
                    $this->settingsRepository->put('company_logo', $newLogoPath, ['group_name' => 'company']);
                    $this->brandingAssetService->deleteByRelativePath($oldLogoPath);
                }

                if ($newFaviconPath !== null) {
                    $oldFaviconPath = $this->settingsRepository->getValue('company_favicon');
                    $this->settingsRepository->put('company_favicon', $newFaviconPath, ['group_name' => 'company']);
                    $this->brandingAssetService->deleteByRelativePath($oldFaviconPath);
                }
            });
        } catch (\Throwable $exception) {
            if ($newLogoPath !== null) {
                $this->brandingAssetService->deleteByRelativePath($newLogoPath);
            }

            if ($newFaviconPath !== null) {
                $this->brandingAssetService->deleteByRelativePath($newFaviconPath);
            }

            throw $exception;
        }

        $this->settingsRepository->forgetCache();
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function settingMeta(): array
    {
        return [
            'app_name' => ['group_name' => 'general'],
            'company_name' => ['group_name' => 'company'],
            'company_email' => ['group_name' => 'company'],
            'company_phone' => ['group_name' => 'company'],
            'company_address' => ['group_name' => 'company', 'type' => 'text'],
            'currency_prefix' => ['group_name' => 'localization'],
            'employee_code_prefix' => ['group_name' => 'localization'],
            'invoice_prefix' => ['group_name' => 'localization'],
            'date_format' => ['group_name' => 'localization'],
            'time_zone' => ['group_name' => 'localization'],
            'mail_mailer' => ['group_name' => 'smtp'],
            'mail_host' => ['group_name' => 'smtp'],
            'mail_port' => ['group_name' => 'smtp'],
            'mail_username' => ['group_name' => 'smtp'],
            'mail_encryption' => ['group_name' => 'smtp'],
            'mail_from_address' => ['group_name' => 'smtp'],
            'mail_from_name' => ['group_name' => 'smtp'],
        ];
    }

}
