<?php

declare(strict_types=1);

class AdminController extends SecurityController
{
    public function index(): void
    {
        $this->isAdmin();
        $view = new View();
        $view->render('admin/index',
            []);
    }
}