<?php

declare(strict_types=1);

class SettingController extends SecurityController
{
    public function getCategory($id): void
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
        Settings::create();
        header( 'Location:'.App::config('url').'/Dashboard/Settings');
    }

    public function updateSetting($id): void
    {
        $this->isAdmin();
        Settings::update($id);
        header( 'Location:'.App::config('url').'/Dashboard/Settings');
    }

    public function deleteSetting($id): void
    {
        $this->isAdmin();
        Settings::delete($id);
        header( 'Location:'.App::config('url').'/Dashboard/Settings');
    }
}