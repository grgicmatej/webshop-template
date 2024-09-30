<?php

declare(strict_types=1);

use Model\CategoryModel;
use Validator\CategoryNameTranslationValidator;
use Validator\CategoryValidator;

class CategoryController extends SecurityController
{
    public function getCategory(string $id): void
    {
        $this->isAdmin();
        $view = new View();
        $view->render('admin/category',
            [
                'category' => Category::get($id),
                'categoryNameTranslation' => CategoryNameTranslation::getByCategory(CategoryValidator::generateFromRequest(null, $id)),
                'productCount' => count(Product::allActive()),
                'categoryCount' => count(Category::all()),
                'orderCount' => count(Order::all()),
                'financeCount' => Order::getTotalAmounts()
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
            $category = CategoryValidator::generateFromRequest(Upload::GetFileName());
            Category::create($category);
            $this->createCategoryNameTranslation($category->getId());
            header( 'Location:'.App::config('url').'Dashboard/Categories');
        } else {
            throw new Exception('upload error');
        }
    }

    private function createCategoryNameTranslation(string $categoryId): void
    {
        $this->isAdmin();
        if (is_string(Request::post('name_hr')) && strlen(Request::post('name_hr')) > 0) {
            $categoryNameTranslation = CategoryNameTranslationValidator::generateFromRequest($categoryId, $this->getHrLocale(), Request::post('name_hr'));
            CategoryNameTranslation::create($categoryNameTranslation);
        }

        if (is_string(Request::post('name_en')) && strlen(Request::post('name_en')) > 0) {
            $categoryNameTranslation = CategoryNameTranslationValidator::generateFromRequest($categoryId, $this->getEnLocale(), Request::post('name_en'));
            CategoryNameTranslation::create($categoryNameTranslation);
        }
    }

    private function updateCategoryNameTranslation(string $categoryId): void
    {
        $this->isAdmin();
        if (is_string(Request::post('name_hr')) && strlen(Request::post('name_hr')) > 0) {
            $categoryNameTranslation = CategoryNameTranslationValidator::generateFromRequest($categoryId, $this->getHrLocale(), Request::post('name_hr'));
            CategoryNameTranslation::update($categoryNameTranslation);
        }

        if (is_string(Request::post('name_en')) && strlen(Request::post('name_en')) > 0) {
            $categoryNameTranslation = CategoryNameTranslationValidator::generateFromRequest($categoryId, $this->getEnLocale(), Request::post('name_en'));
            CategoryNameTranslation::update($categoryNameTranslation);        }
    }

    public function updateCategory(string $id): void
    {
        $this->isAdmin();
        Upload::UploadPhoto(true);
        $uploadImage = null;
        if (Upload::GetFileName() !== NULL) {
            $uploadImage = Upload::GetFileName();
        }
        $category = CategoryValidator::generateFromRequest($uploadImage, $id);
        Category::update($category);
        $this->updateCategoryNameTranslation($id);
        header( 'Location:'.App::config('url').'Dashboard/Categories');

    }

    public function deleteCategory(string $id): void
    {
        $this->isAdmin();
        $category = CategoryValidator::generateFromRequest(null, $id);
        CategoryNameTranslation::delete($category);
        Category::delete($category);
        header( 'Location:'.App::config('url').'Dashboard/Categories');
    }
}