<?php

declare(strict_types=1);

class ShoppingCartController extends SecurityController
{
    public function add($id): void
    {
        $product = Product::get($id);
        $shoppingCartExist = ShoppingCart::checkIfExist();
        if (false === $shoppingCartExist) {
            ShoppingCart::add($product);
        } else {
            ShoppingCart::add($product, $shoppingCartExist['order_id']);
        }

        header( 'Location:'.App::config('url').'/Store/Product/'.$id.'?m=1');
    }

    public function get(): void
    {
        $view = new View();
        $view->render('public/shopping_cart',
            [
                'shoppingCart' => ShoppingCart::get(),
            ]);
    }

    public function remove($id): void
    {
        ShoppingCart::remove($id);
        header( 'Location:'.App::config('url').'/Store/Product/'.$id.'?m=2');

    }
}