<?php

declare(strict_types=1);

use Model\ProductQuantityModel;

class ShoppingcartController extends SecurityController
{
    /**
     * @throws Exception
     */
    public function add(string $id): void
    {
        $size = Size::getByValue($_POST['size']);
        $color = Color::getByValue($_POST['color']);
        $product = Product::get($id);

        if (null === $product) {
            throw new Exception('Invalid product');
        }

        $productQuantity = ProductQuantity::getByValues($product, $size, $color);

        if (null === $productQuantity || false === $this->hasEnoughQuantity($productQuantity)) {
            header( 'Location:'.App::config('url').'Trgovina/proizvod/'.$product->getId().'?m=0');
        } else {
            $shoppingCartExist = ShoppingCart::checkIfExist();
            if (null === $shoppingCartExist) {
                ShoppingCart::add($product, $productQuantity);
            } else {
                ShoppingCart::add($product, $productQuantity,$shoppingCartExist);
            }

            ProductQuantity::updateReservedNumber($productQuantity);

            header( 'Location:'.App::config('url').'Trgovina/proizvod/'.$product->getId().'?m=1');
        }
    }

    public function get(): void
    {
        $view = new View();
        $view->render('public/shopping_cart',
            [
                'shoppingCart' => ShoppingCart::get(),
            ]);
    }

    public function remove(string $id): void
    {
        $shoppingCart = ShoppingCart::getById($id);
        ProductQuantity::updateReservedNumberAfterDeletedShoppingCart($shoppingCart);
        ShoppingCart::remove($shoppingCart);
        header( 'Location:'.App::config('url').'Trgovina/proizvod/'.$shoppingCart->getProduct()->getId().'?m=2');
    }

    private function hasEnoughQuantity(ProductQuantityModel $productQuantityModel): bool
    {
        $available = $productQuantityModel->getAvailable();
        $reserved = $productQuantityModel->getReserved();
        $sold = $productQuantityModel->getSold();

        return 0 < $available - $reserved - $sold;
    }
}
