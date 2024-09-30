<?php

declare(strict_types=1);

use Validator\ContentImageValidator;
use Validator\ContentValidator;

class ContentController extends SecurityController
{
    public function editContent(string $id): void
    {
        $this->isAdmin();
        $view = new View();
        $view->render('public/sadrzaj',
            [
                'content' =>Content::get($id),
                'contentImages' => Content::allImages(3),
                'page' => $_GET['page']
            ]);
    }

    public function editContentImages(string $id): void
    {
        $this->isAdmin();
        $view = new View();
        $view->render('public/sadrzaj-slike',
            [
                'contentImages' => Content::allImages(3),
                'page' => $_GET['page'],
                'content' => Content::getOneContentImages($id)
            ]);
    }

    public function updateContent(): void
    {
        $this->isAdmin();
        $content = ContentValidator::generateFromRequest();
        Content::updateContent($content);
        header( 'Location:'.App::config('url').'Routes/index/'.$content->getPage());
    }

    /** @throws Exception */
    public function updateContentImages(): void
    {
        $this->isAdmin();
        Upload::UploadPhoto();
        if (Upload::GetFileName() !== NULL){
            $contentImage = ContentImageValidator::generateFromRequest(Upload::GetFileName());
            Content::updateContentImages($contentImage);
            header( 'Location:'.App::config('url').'Routes/index/'.$contentImage->getPage());
        } else {
            $contentImage = ContentImageValidator::generateFromRequest();
            Content::updateContentImageTitle($contentImage);
            header( 'Location:'.App::config('url').'Routes/index/'.$contentImage->getPage());
        }
    }
}
