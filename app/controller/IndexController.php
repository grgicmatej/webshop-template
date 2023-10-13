<?php

declare(strict_types=1);

class IndexController
{
    private Session $session;
    private Cookie $cookie;

    public function __construct(Session $session, Cookie $cookie)
    {
        $this->session = $session;
        $this->cookie = $cookie;
    }


    public function index(): void
    {
        $view = new View();
        $view->render('index',
            []);
    }
}