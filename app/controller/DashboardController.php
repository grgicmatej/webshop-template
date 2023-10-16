<?php

declare(strict_types=1);

class DashboardController
{
    public function index(): void
    {
        $view = new View();
        $view->render('admin/index',
            []);
    }
}