<?php

declare(strict_types=1);

class DashboardController extends SecurityController
{
    public function index(): void
    {
        $this->isAdmin();
        $view = new View();
        $view->render('admin/index',
            []);
    }

    public function products(): void
    {
        $this->isAdmin();
        $view = new View();
        $view->render('admin/products',
            []);
    }
}