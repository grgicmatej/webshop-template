<?php

declare(strict_types=1);

class AdminController
{
    public function index(): void
    {
        $view = new View();
        $view->render('admin/index',
            []);
    }
}