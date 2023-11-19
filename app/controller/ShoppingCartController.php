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
    }

    public function get(): array
    {
        $view = new View();
        var_dump(ShoppingCart::get());
        die();
        $view->render('public/shopping_cart',
            [
                'shoppingCart' => ShoppingCart::get(),
            ]);
    }
}