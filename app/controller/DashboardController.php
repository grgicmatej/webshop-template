<?php

declare(strict_types=1);

class DashboardController extends SecurityController
{
    public function index(): void
    {
        $this->isAdmin();
        $view = new View();
        $view->render('admin/index',
            []);
    }

    public function products(): void
    {
        $this->isAdmin();
        $view = new View();
        $view->render('admin/products',
            [
                'products' => Product::all()
            ]);
    }

    public function product($id): void
    {
        $this->isAdmin();
        $view = new View();
        $view->render('admin/product',
            [
                'id' => $id,
                'product' => Product::get($id),
                'productTranslation' => ProductTranslation::get($id),
                'productNameTranslation' => ProductNameTranslation::get($id),
                'productQuantity' => ProductQuantity::get($id),
            ]);
    }
}