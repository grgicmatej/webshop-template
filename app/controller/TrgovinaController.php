<?php

declare(strict_types=1);

class TrgovinaController
{
    public function index(): void
    {
        $view = new View();
        $view->render('public/trgovina',
            [
                'content' => Content::getContent(2),
                'contentImages' => Content::getContentImages(2),
                'categories' => Category::all()
            ]);
    }

    public function kategorija($id): void
    {
        $view = new View();
        $view->render('public/kategorija',
            [
                'content' => Content::getContent(2),
                'contentImages' => Content::getContentImages(2),
                'products' => ProductCategory::getProductsByCategory($id),
                'category' => CategoryNameTranslation::get($id)
            ]);
    }

    public function proizvod($id): void
    {
        $view = new View();
        $view->render('public/proizvod',
            [
                'product' => Product::allProductData($id),
                'products' => Product::allProductDataLimit(),
            ]);
    }
}