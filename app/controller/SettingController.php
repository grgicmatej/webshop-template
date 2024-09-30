<?php

declare(strict_types=1);

use Validator\SettingValidator;

class SettingController extends SecurityController
{
    public function getCategory(string $id): void
    {
        $this->isAdmin();
        $view = new View();
        $view->render('admin/setting',
            [
                'setting' => Settings::get($id)
            ]);
    }

    public function createSetting(): void
    {
        $this->isAdmin();
        Settings::create(SettingValidator::generateFromRequest());
        header( 'Location:'.App::config('url').'Dashboard/Settings');
    }

    public function updateSetting(string $id): void
    {
        $this->isAdmin();
        Settings::update(SettingValidator::generateFromRequest($id));
        header( 'Location:'.App::config('url').'Dashboard/Settings');
    }

    public function deleteSetting(string $id): void
    {
        $this->isAdmin();
        Settings::delete(SettingValidator::generateFromRequest($id));
        header( 'Location:'.App::config('url').'Dashboard/Settings');
    }
}