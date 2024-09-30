<?php

declare(strict_types=1);

class IndexController
{
    public function index(): void
    {
        $view = new View();
        $view->render('public/index',
            [
                'content' => Content::all(1),
                'contentImages' => Content::allImages(1),
                'products' => Product::getProductsByFeaturedStatus(),
                'popular' => Product::getProductsByPopularity(true, 6),
                'shoppingCart' => ShoppingCart::get(),
                'shoppingCartSum' => ShoppingCart::sum(),
                'numOfShoppingCartItems' => count(ShoppingCart::get())
            ]);
    }
}