<?php

declare(strict_types=1);

class CategoryController extends SecurityController
{
    public function getCategory($id): void
    {
        $this->isAdmin();
        $view = new View();
        $view->render('admin/category',
            [
                'category' => Category::get($id),
                'categoryNameTranslation' => CategoryNameTranslation::get($id),
                'productCount' => count(Product::all()),
                'categoryCount' => count(Category::all()),
                'orderCount' => count(Order::all()),
                'financeCount' => number_format(floatval(Order::getTotalAmounts()['total']), 2)
            ]);
    }

    /**
     * @throws Exception
     */
    public function createCategory(): void
    {
        $this->isAdmin();
        Upload::UploadPhoto(true);
        if (Upload::GetFileName() !== NULL) {
            $category = \Validator\CategoryValidator::generateFromRequest(Upload::GetFileName());
            Category::create($category);
            $this->createCategoryNameTranslation($category->getId());
            header( 'Location:'.App::config('url').'/Dashboard/Categories');
        } else {
            throw new Exception('upload error');
        }
    }

    private function createCategoryNameTranslation(string $categoryId): void
    {
        $this->isAdmin();
        if (is_string(Request::post('name_hr')) && strlen(Request::post('name_hr')) > 0) {
            CategoryNameTranslation::create($categoryId, $this->getHrLocale());
        }

        if (is_string(Request::post('name_hr')) && strlen(Request::post('name_hr')) > 0) {
            CategoryNameTranslation::create($categoryId, $this->getEnLocale());
        }
    }

    private function updateCategoryNameTranslation($categoryId): void
    {
        $this->isAdmin();
        if (is_string(Request::post('name_hr')) && strlen(Request::post('name_hr')) > 0) {
            CategoryNameTranslation::update($categoryId, $this->getHrLocale());
        }

        if (is_string(Request::post('name_en')) && strlen(Request::post('name_en')) > 0) {
            CategoryNameTranslation::update($categoryId, $this->getEnLocale());
        }
    }

    public function updateCategory($id): void
    {
        $this->isAdmin();
        Upload::UploadPhoto(true);
        $uploadImage = null;
        if (Upload::GetFileName() !== NULL) {
            $uploadImage = Upload::GetFileName();
        }

        Category::update($id, $uploadImage);
        $this->updateCategoryNameTranslation($id);
        header( 'Location:'.App::config('url').'/Dashboard/Categories');

    }
}