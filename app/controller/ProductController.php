<?php

declare(strict_types=1);

use Model\ProductModel;
use Validator\ProductCategoryValidator;
use Validator\ProductImageValidator;
use Validator\ProductNameTranslationValidator;
use Validator\ProductQuantityValidator;
use Validator\ProductTranslationValidator;
use Validator\ProductValidator;

class ProductController extends SecurityController
{
    public function getProduct(string $id): void
    {
        $this->isAdmin();

        $product = Product::get($id);
        $view = new View();
        $view->render('admin/product',
            [
                'product' => $product,
                'productNameTranslation' => ProductNameTranslation::all($product),
                'productTranslation' => ProductTranslation::get($product),
                'productQuantity' => ProductQuantity::get($product),
                'productCount' => count(Product::allActive()),
                'categoryCount' => count(Category::all()),
                'orderCount' => count(Order::all()),
                'financeCount' => Order::getTotalAmounts()
            ]);
    }

    /**
     * @throws Exception
     */
    public function createProduct(): void
    {
        $this->isAdmin();
        $id = Uuid::generateUuid();

        $product = ProductValidator::generateFromRequest($id);
        if (false === Product::create($product)) {
            throw new Exception('Creation error');
        }

        $this->createProductTranslation($product);
        $this->createProductNameTranslation($product);

        header( 'Location:'.App::config('url').'Dashboard/Products');
    }

    private function createProductTranslation(ProductModel $product): void
    {
        $this->isAdmin();
        if (is_string(Request::post('description_hr'))) {
            $productTranslation = ProductTranslationValidator::generateFromRequest(null, $product, $this->getHrLocale(), Request::post('description_hr'));
            ProductTranslation::create($productTranslation);
        }

        if (is_string(Request::post('description_en'))) {
            $productTranslation = ProductTranslationValidator::generateFromRequest(null, $product, $this->getEnLocale(), Request::post('description_en'));
            ProductTranslation::create($productTranslation);
        }
    }

    private function createProductNameTranslation(ProductModel $product): void
    {
        $this->isAdmin();
        if (is_string(Request::post('name_hr')) && strlen(Request::post('name_hr')) > 0) {
            $productNameTranslation = ProductNameTranslationValidator::generateFromRequest(null, $product, $this->getHrLocale(), Request::post('name_hr'));
            ProductNameTranslation::create($productNameTranslation);
        }

        if (is_string(Request::post('name_en')) && strlen(Request::post('name_en')) > 0) {
            $productNameTranslation = ProductNameTranslationValidator::generateFromRequest(null, $product, $this->getEnLocale(), Request::post('name_en'));
            ProductNameTranslation::create($productNameTranslation);
        }
    }

    public function createProductQuantity(string $productId): void
    {
        $this->isAdmin();
        $product = Product::get($productId);
        $productQuantity = ProductQuantityValidator::generateFromRequest(null, $product, Color::get(Request::post('color')), Size::get(Request::post('size')), (int)Request::post('available'));
        if (true === ProductQuantity::checkIfExist($productQuantity)) {
            header( 'Location:'.App::config('url').'Dashboard/product/'.$product->getId().'?e=1');
        } else {
            ProductQuantity::create($productQuantity);
            header( 'Location:'.App::config('url').'Dashboard/product/'.$product->getId());
        }
    }

    public function createProductImage(string $productId): void
    {
        $this->isAdmin();
        $product = Product::get($productId);
        Upload::UploadPhoto(true);
        if (NULL !== Upload::GetFileName()){
            $productImage = ProductImageValidator::generateFromRequest(Upload::GetFileName(), $product);
            ProductImage::create($productImage);
            $images = ProductImage::getByProductId($product);
            if (1 === count($images)) {
                ProductImage::setPrimaryImage($productImage);
            }
        }
        header( 'Location:'.App::config('url').'Dashboard/product/'.$product->getId());
    }

    public function updateProduct(string $id): void
    {
        $this->isAdmin();
        $product = ProductValidator::generateFromRequest($id);
        Product::update($product);
        $this->updateProductNameTranslation($product);
        header( 'Location:'.App::config('url').'Dashboard/product/'.$product->getId());

    }

    public function updateDescription(string $productId): void
    {
        $this->isAdmin();
        $product = Product::get($productId);
        if (is_string(Request::post('description_hr')) && strlen(Request::post('description_hr')) > 0) {
            $productTranslation = ProductTranslationValidator::generateFromRequest(null, $product, $this->getHrLocale(), Request::post('description_hr'));
            ProductTranslation::update($productTranslation);
        }

        if (is_string(Request::post('description_en')) && strlen(Request::post('description_en')) > 0) {
            $productTranslation = ProductTranslationValidator::generateFromRequest(null, $product, $this->getEnLocale(), Request::post('description_en'));
            ProductTranslation::update($productTranslation);
        }

        header( 'Location:'.App::config('url').'Dashboard/product/'.$product->getId());
    }

    private function updateProductNameTranslation(ProductModel $product): void
    {
        $this->isAdmin();
        if (is_string(Request::post('name_hr')) && strlen(Request::post('name_hr')) > 0) {
            $productNameTranslation = ProductNameTranslationValidator::generateFromRequest(null,$product,$this->getHrLocale(), Request::post('name_hr'));
            ProductNameTranslation::update($productNameTranslation);
        }

        if (is_string(Request::post('name_en')) && strlen(Request::post('name_en')) > 0) {
            $productNameTranslation = ProductNameTranslationValidator::generateFromRequest(null,$product,$this->getEnLocale(), Request::post('name_en'));
            ProductNameTranslation::update($productNameTranslation);
        }
    }

    public function deleteProduct(string $id): void
    {
        $this->isAdmin();
        Product::delete(ProductValidator::generateFromRequest($id));
        header( 'Location:'.App::config('url').'Dashboard/Products');
    }

    public function createProductCategory(string $productId): void
    {
        $this->isAdmin();
        $productCategory = ProductCategoryValidator::generateFromRequest(Product::get($productId), Category::get(Request::post('category_id')));
        ProductCategory::create($productCategory);
        header( 'Location:'.App::config('url').'Dashboard/product/'.$productId);
    }

    public function deleteProductCategory(string $productCategoryId): void
    {
        $this->isAdmin();
        $productCategory = ProductCategory::getById($productCategoryId);
        if (null !== $productCategory) {
            ProductCategory::delete($productCategory);
        }

        $product = Product::get($_GET['p']);
        header( 'Location:'.App::config('url').'Dashboard/product/'.$product->getId());
    }

    public function resetSoldNumber(string $productQuantityId): void
    {
        $this->isAdmin();
        ProductQuantity::deleteSoldNumber(ProductQuantity::getById($productQuantityId));

        $product = Product::get($_GET['p']);
        header( 'Location:'.App::config('url').'Dashboard/product/'.$product->getId());
    }

    public function editAvailableNumber(string $productQuantityId): void
    {
        $this->isAdmin();
        $productQuantityModel = ProductQuantity::getById($productQuantityId);
        $productQuantity = ProductQuantityValidator::generateFromRequest(
            $productQuantityModel->getId(),
            $productQuantityModel->getProduct(),
            $productQuantityModel->getColor(),
            $productQuantityModel->getSize(),
            intval(Request::post('available')),
            $productQuantityModel->getReserved(),
            $productQuantityModel->getSold()
        );
        ProductQuantity::editAvailableNumber($productQuantity);

        $product = Product::get($_GET['p']);
        header( 'Location:'.App::config('url').'Dashboard/product/'.$product->getId());
    }

    public function deleteQuantity(string $productQuantityId): void
    {
        $this->isAdmin();
        ProductQuantity::deleteQuantity(ProductQuantity::getById($productQuantityId));

        $product = Product::get($_GET['p']);
        header( 'Location:'.App::config('url').'Dashboard/product/'.$product->getId());
    }

    public function deleteImage(string $productImageId): void
    {
        $this->isAdmin();
        if (true === Upload::deletePhoto($_GET['path'])) {
            $productImage = ProductImage::getByImageId($productImageId);
            ProductImage::deleteImage($productImage);
        }

        $product = Product::get($_GET['p']);
        header( 'Location:'.App::config('url').'Dashboard/product/'.$product->getId());
    }

    public function setImageAsPrimary(string $productImageId): void
    {
        $this->isAdmin();
        $productImage = ProductImage::getByImageId($productImageId);

        $product = Product::get($_GET['p']);
        ProductImage::removePrimaryImage($product);
        ProductImage::setPrimaryImage($productImage);

        header( 'Location:'.App::config('url').'Dashboard/product/'.$product->getId());
    }

    public function productDescriptionImage(): void
    {
        $this->isAdmin();
        ProductDescriptionImage::imageUpload();
    }
}
