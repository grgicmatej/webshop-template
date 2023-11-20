<?php

declare(strict_types=1);

class ContentController extends SecurityController
{
    public function editContent($id): void
    {
        $this->isAdmin();
        $view = new View();
        $view->render('public/sadrzaj',
            [
                'content' =>(array) Content::getOneContent(intval($id)),
                'page' => $_GET['page']
            ]);
    }

    public function editContentImages($id): void
    {
        $this->isAdmin();
        $view = new View();
        $view->render('public/slike',
            [
                'content' =>(array) Content::getOneContentImages(intval($id)),
                'page' => $_GET['page']
            ]);
    }

    public function updateContent(): void
    {
        $this->isAdmin();
        Content::updateContent();
        header( 'Location:'.App::config('url').'/Routes/index/'.Request::post('page'));
    }

    /** @throws Exception */
    public function updateContentImages(): void
    {
        $this->isAdmin();
        Upload::UploadPhoto();
        if (Upload::GetFileName() !== NULL){
            Content::updateContentImages(Upload::GetFileName());
        } else {
            throw new Exception('upload error');
        }
        header( 'Location:'.App::config('url').'/Routes/index/'.Request::post('page'));
    }
}
