<?php

declare(strict_types=1);

use Validator\SizeValidator;

class SizeController extends SecurityController
{
    /**
     * @throws Exception
     */
    public function createSize(): void
    {
        $this->isAdmin();
        if (true === Size::create(SizeValidator::generateFromRequest())) {
            header( 'Location:'.App::config('url').'Dashboard/Sizes');
        } else {
            throw new Exception('upload error');
        }
    }

    public function updateSize(string $id): void
    {
        $this->isAdmin();
        Size::update(SizeValidator::generateFromRequest($id));
        header( 'Location:'.App::config('url').'Dashboard/Sizes');
    }

    public function deleteSize(string $id): void
    {
        $this->isAdmin();
        Size::delete(SizeValidator::generateFromRequest($id));
        header( 'Location:'.App::config('url').'Dashboard/Sizes');
    }
}
