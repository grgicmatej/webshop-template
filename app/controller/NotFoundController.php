<?php

declare(strict_types=1);

class NotFoundController
{
    public function index(): void
    {
        $view = new View();
        $view->render('public/404',
            []);
    }
}