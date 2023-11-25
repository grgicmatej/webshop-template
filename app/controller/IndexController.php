<?php

declare(strict_types=1);

class IndexController
{
    public function index(): void
    {
        $view = new View();
        $view->render('public/index',
            [
                'content' => Content::getContent(1),
                'contentImages' => Content::getContentImages(1),
            ]);
    }
}