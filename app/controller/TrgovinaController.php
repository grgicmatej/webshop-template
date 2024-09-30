<?php

declare(strict_types=1);

use Enum\SettingKeyEnum;
use Model\ProductQuantityModel;
use Validator\ProductValidator;

class TrgovinaController
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

    public function kategorija(string $id): void
    {
        $view = new View();
        $view->render('public/kategorija',
            [
                'content' => Content::all(2),
                'contentImages' => Content::allImages(2),
                'products' => ProductCategory::getProductsByCategory(Category::get($id)),
                'category' => CategoryNameTranslation::getByCategory(Category::get($id)),
                'shoppingCart' => ShoppingCart::get(),
                'shoppingCartSum' => ShoppingCart::sum(),
                'numOfShoppingCartItems' => count(ShoppingCart::get())
            ]);
    }

    public function proizvod(string $id): void
    {
        $availableSizes = [];
        $quantities = ProductQuantity::get(Product::get($id));

        /** @var ProductQuantityModel $quantity */
        foreach ($quantities as $quantity) {
            if (0 === $quantity->getAvailable() || 0 === ($quantity->getAvailable() - $quantity->getReserved() - $quantity->getSold())) {
                continue;
            }

            if (false === key_exists($quantity->getColor()->getName(), $availableSizes)) {
                $availableSizes[$quantity->getColor()->getName()] = [$quantity->getSize()->getName()];
            } else {
                $availableSizes[$quantity->getColor()->getName()][] = $quantity->getSize()->getName();
            }
        }

        $ordered_sizes = ['S', 'M', 'L', 'XL', '2XL', '3XL'];

        foreach ($availableSizes as $color => &$sizes) {
            usort($sizes, function($a, $b) use ($ordered_sizes) {
                $posA = array_search($a, $ordered_sizes);
                $posB = array_search($b, $ordered_sizes);
                return $posA - $posB;
            });
        }

        Product::updatePopularity(ProductValidator::generateFromRequest($id));

        $view = new View();
        $view->render('public/proizvod',
            [
                'product' => Product::allProductData($id),
                'productQuantity' => $availableSizes,
                'productImage' => ProductImage::getByProductId(Product::get($id)),
                'shoppingCart' => ShoppingCart::get(),
                'shoppingCartSum' => ShoppingCart::sum(),
                'numOfShoppingCartItems' => count(ShoppingCart::get())
            ]);
    }

    public function kontakt(): void
    {
        $view = new View();
        $view->render('public/kontakt',
            [
                'content' => Content::all(3),
                'contentImages' => Content::allImages(3),
                'shoppingCart' => ShoppingCart::get(),
                'shoppingCartSum' => ShoppingCart::sum(),
                'numOfShoppingCartItems' => count(ShoppingCart::get())
            ]);
    }

    public function privatnost(): void
    {
        $view = new View();
        $view->render('public/privatnost',
            [
                'content' => Content::all(4),
                'contentImages' => Content::allImages(4),
                'shoppingCart' => ShoppingCart::get(),
                'shoppingCartSum' => ShoppingCart::sum(),
                'numOfShoppingCartItems' => count(ShoppingCart::get())
            ]);

    }
    public function uvjeti(): void
    {
        $view = new View();
        $view->render('public/uvjeti',
            [
                'content' => Content::all(5),
                'contentImages' => Content::allImages(5),
                'shoppingCart' => ShoppingCart::get(),
                'shoppingCartSum' => ShoppingCart::sum(),
                'numOfShoppingCartItems' => count(ShoppingCart::get())
            ]);

    }

    public function informacije(): void
    {
        $view = new View();
        $view->render('public/ugovor',
            [
                'content' => Content::all(6),
                'contentImages' => Content::allImages(6),
                'shoppingCart' => ShoppingCart::get(),
                'shoppingCartSum' => ShoppingCart::sum(),
                'numOfShoppingCartItems' => count(ShoppingCart::get())
            ]);

    }

    public function kolacici(): void
    {
        $view = new View();
        $view->render('public/kolacici',
            [
                'content' => Content::all(7),
                'contentImages' => Content::allImages(7),
                'shoppingCart' => ShoppingCart::get(),
                'shoppingCartSum' => ShoppingCart::sum(),
                'numOfShoppingCartItems' => count(ShoppingCart::get())
            ]);
    }

    public function oNama(): void
    {
        $view = new View();
        $view->render('public/o-nama',
            [
                'content' => Content::all(8),
                'contentImages' => Content::allImages(8),
                'shoppingCart' => ShoppingCart::get(),
                'shoppingCartSum' => ShoppingCart::sum(),
                'numOfShoppingCartItems' => count(ShoppingCart::get())
            ]);
    }

    public function upit(): void
    {
        $settings = Settings::getByKey(SettingKeyEnum::getContactEmailKey());
        if (true === Contact::send($settings->getValue())) {
            header( 'Location:'.App::config('url').'Trgovina/kontakt/?m=1');
        } else {
            header( 'Location:'.App::config('url').'Trgovina/kontakt/?m=0');
        }
    }

    public function zavrsenaKupovina(): void
    {
        $view = new View();
        $view->render('public/success',
            [
                'content' => Content::all(9),
                'contentImages' => Content::allImages(9),
                'shoppingCart' => ShoppingCart::get(),
                'shoppingCartSum' => ShoppingCart::sum(),
                'numOfShoppingCartItems' => count(ShoppingCart::get())
            ]);
    }
}
