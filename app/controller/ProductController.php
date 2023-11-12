<?php

declare(strict_types=1);

class ProductController extends SecurityController
{
    public function getProduct($id): void
    {
        $this->isAdmin();
        $view = new View();
        $view->render('admin/product',
            [
                'product' => Product::get($id),
                'productNameTranslation' => ProductNameTranslation::get($id),
                'productTranslation' => ProductTranslation::get($id),
                'productQuantity' => ProductQuantity::get($id)
            ]);
    }

    /**
     * @throws Exception
     */
    public function createProduct(): void
    {
        $this->isAdmin();
        Upload::UploadPhoto(true);
        if (Upload::GetFileName() !== NULL){
            $id = Uuid::generateUuid();
            Product::create($id, Upload::GetFileName());

            $this->createProductTranslation($id);
            $this->createProductNameTranslation($id);
            $this->createProductQuantity($id);

            header( 'Location:'.App::config('url').'/Dashboard/Products');
        } else {
            throw new Exception('upload error');
        }
    }

    private function createProductTranslation($productId): void
    {
        $this->isAdmin();
        if (is_string(Request::post('description_hr')) && strlen(Request::post('description_hr')) > 0) {
            ProductTranslation::create($productId, $this->getHrLocale());
        }

        if (is_string(Request::post('description_en')) && strlen(Request::post('description_en')) > 0) {
            ProductTranslation::create($productId, $this->getEnLocale());
        }
    }

    private function createProductNameTranslation($productId): void
    {
        $this->isAdmin();
        if (is_string(Request::post('name_hr')) && strlen(Request::post('name_hr')) > 0) {
            ProductNameTranslation::create($productId, $this->getHrLocale());
        }

        if (is_string(Request::post('name_en')) && strlen(Request::post('name_en')) > 0) {
            ProductNameTranslation::create($productId, $this->getEnLocale());
        }
    }

    private function createProductQuantity($productId): void
    {
        $this->isAdmin();
        ProductQuantity::create($productId);
    }

    public function updateProduct($id): void
    {
        $this->isAdmin();
        Upload::UploadPhoto(true);
        $uploadImage = null;
        if (Upload::GetFileName() !== NULL) {
            $uploadImage = Upload::GetFileName();
        }

        Product::update($id, $uploadImage);
        $this->updateProductTranslation($id);
        $this->updateProductQuantity($id);
        $this->updateProductNameTranslation($id);
        header( 'Location:'.App::config('url').'/Dashboard/Products');

    }

    private function updateProductTranslation($productId): void
    {
        $this->isAdmin();
        if (is_string(Request::post('description_hr')) && strlen(Request::post('description_hr')) > 0) {
            ProductTranslation::update($productId, $this->getHrLocale());
        }

        if (is_string(Request::post('description_en')) && strlen(Request::post('description_en')) > 0) {
            ProductTranslation::update($productId, $this->getEnLocale());
        }
    }

    private function updateProductQuantity($productId): void
    {
        $this->isAdmin();
        ProductQuantity::update($productId);

    }

    private function updateProductNameTranslation($productId): void
    {
        $this->isAdmin();
        $this->isAdmin();
        if (is_string(Request::post('name_hr')) && strlen(Request::post('name_hr')) > 0) {
            ProductNameTranslation::update($productId, $this->getHrLocale());
        }

        if (is_string(Request::post('name_en')) && strlen(Request::post('name_en')) > 0) {
            ProductNameTranslation::update($productId, $this->getEnLocale());
        }
    }

    public function deleteProduct($id): void
    {
        $this->isAdmin();
        Product::delete($id);
        header( 'Location:'.App::config('url').'/Dashboard');
    }
}
