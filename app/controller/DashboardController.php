<?php

declare(strict_types=1);

use Validator\CategoryValidator;

class DashboardController extends SecurityController
{
    public function index(): void
    {
        $this->isAdmin();
        $view = new View();
        $view->render('admin/index',
            [
                'productCount' => count(Product::allActive()),
                'categoryCount' => count(Category::all()),
                'orderCount' => count(Order::all()),
                'financeCount' => Order::getTotalAmounts(),
                'popularProducts' => Product::getProductsByPopularity(false, 0),
                'orders' => Order::getAllNewOrders()
            ]);
    }

    public function products(): void
    {
        $this->isAdmin();
        $view = new View();
        $view->render('admin/products',
            [
                'products' => Product::all(),
                'productCount' => count(Product::allActive()),
                'categoryCount' => count(Category::all()),
                'orderCount' => count(Order::all()),
                'financeCount' => Order::getTotalAmounts()
            ]);
    }

    public function colors(): void
    {
        $view = new View();
        $view->render('admin/colors',
            [
                'colors' => Color::all(),
                'productCount' => count(Product::allActive()),
                'categoryCount' => count(Category::all()),
                'orderCount' => count(Order::all()),
                'financeCount' => Order::getTotalAmounts()
            ]);
    }

    public function color(string $id): void
    {
        $this->isAdmin();
        $view = new View();
        $view->render('admin/color',
            [
                'color' => Color::get($id),
                'productCount' => count(Product::allActive()),
                'categoryCount' => count(Category::all()),
                'orderCount' => count(Order::all()),
                'financeCount' => Order::getTotalAmounts()
            ]);
    }

    public function sizes(): void
    {
        $view = new View();
        $view->render('admin/sizes',
            [
                'sizes' => Size::all(),
                'productCount' => count(Product::allActive()),
                'categoryCount' => count(Category::all()),
                'orderCount' => count(Order::all()),
                'financeCount' => Order::getTotalAmounts()
            ]);
    }

    public function size(string $id): void
    {
        $this->isAdmin();
        $view = new View();
        $view->render('admin/size',
            [
                'size' => Size::get($id),
                'productCount' => count(Product::allActive()),
                'categoryCount' => count(Category::all()),
                'orderCount' => count(Order::all()),
                'financeCount' => Order::getTotalAmounts()
            ]);
    }

    public function product(string $id): void
    {
        $product = Product::get($id);
        $this->isAdmin();
        $view = new View();
        $view->render('admin/product',
            [
                'id' => $id,
                'product' => Product::get($id),
                'productTranslation' => ProductTranslation::get($product),
                'productNameTranslation' => ProductNameTranslation::all($product),
                'productQuantity' => ProductQuantity::get($product),
                'productImage' => ProductImage::getByProductId($product),
                'productCount' => count(Product::allActive()),
                'categoryCount' => count(Category::all()),
                'orderCount' => count(Order::all()),
                'financeCount' => Order::getTotalAmounts(),
                'productCategories' => ProductCategory::get($product),
                'categories' => Category::all(),
                'colors' => Color::all(),
                'sizes' => Size::all(),
            ]);
    }

    public function categories(): void
    {
        $this->isAdmin();
        $view = new View();
        $view->render('admin/categories',
            [
                'categories' => Category::all(),
                'productCount' => count(Product::allActive()),
                'categoryCount' => count(Category::all()),
                'orderCount' => count(Order::all()),
                'financeCount' => Order::getTotalAmounts()
            ]);
    }

    public function category(string $id): void
    {
        $this->isAdmin();
        $view = new View();
        $view->render('admin/category',
            [
                'id' => $id,
                'category' => Category::get($id),
                'categoryNameTranslation' => CategoryNameTranslation::getByCategory(CategoryValidator::generateFromRequest(null, $id)),
                'productCount' => count(Product::allActive()),
                'categoryCount' => count(Category::all()),
                'orderCount' => count(Order::all()),
                'financeCount' => Order::getTotalAmounts()
            ]);
    }

    public function settings(): void
    {
        $this->isAdmin();
        $view = new View();
        $view->render('admin/settings',
            [
                'settings' => Settings::all(),
                'productCount' => count(Product::allActive()),
                'categoryCount' => count(Category::all()),
                'orderCount' => count(Order::all()),
                'financeCount' => Order::getTotalAmounts()
            ]);
    }

    public function setting(string $id): void
    {
        $this->isAdmin();
        $view = new View();
        $view->render('admin/setting',
            [
                'setting' => Settings::get($id),
                'productCount' => count(Product::allActive()),
                'categoryCount' => count(Category::all()),
                'orderCount' => count(Order::all()),
                'financeCount' => Order::getTotalAmounts()
            ]);
    }

    public function orders(): void
    {
        $this->isAdmin();
        $view = new View();
        $view->render('admin/orders',
            [
                'productCount' => count(Product::allActive()),
                'categoryCount' => count(Category::all()),
                'orderCount' => count(Order::all()),
                'financeCount' => Order::getTotalAmounts(),
                'orders' => Order::all()
            ]);
    }

    public function order(string $id): void
    {
        $order = Order::get($id);
        $this->isAdmin();
        $view = new View();
        $view->render('admin/order',
            [
                'productCount' => count(Product::allActive()),
                'categoryCount' => count(Category::all()),
                'orderCount' => count(Order::all()),
                'financeCount' => Order::getTotalAmounts(),
                'orderProducts' => ShoppingCart::getShoppingCartProductsForCompletedOrder($order),
                'orderDetails' => $order,
                'user' => $order->getUser(),
                'userDetails' => Users::findDetailsForUser($order->getUser())
            ]);
    }
}
