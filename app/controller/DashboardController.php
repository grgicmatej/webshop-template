<?php

declare(strict_types=1);

class DashboardController extends SecurityController
{
    public function index(): void
    {
        $this->isAdmin();
        $view = new View();
        $view->render('admin/index',
            [
                'productCount' => count(Product::all()),
                'categoryCount' => count(Category::all())
            ]);
    }

    public function products(): void
    {
        $this->isAdmin();
        $view = new View();
        $view->render('admin/products',
            [
                'products' => Product::all(),
                'productCount' => count(Product::all()),
                'categoryCount' => count(Category::all())
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
                'productCount' => count(Product::all()),
                'categoryCount' => count(Category::all())
            ]);
    }

    public function categories(): void
    {
        $this->isAdmin();
        $view = new View();
        $view->render('admin/categories',
            [
                'categories' => Category::all(),
                'productCount' => count(Product::all()),
                'categoryCount' => count(Category::all())
            ]);
    }

    public function category($id): void
    {
        $this->isAdmin();
        $view = new View();
        $view->render('admin/category',
            [
                'id' => $id,
                'category' => Category::get($id),
                'categoryNameTranslation' => CategoryNameTranslation::get($id),
                'productCount' => count(Product::all()),
                'categoryCount' => count(Category::all())
            ]);
    }

}