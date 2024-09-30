<?php

declare(strict_types=1);

use Validator\ColorValidator;

class ColorController extends SecurityController
{
    /**
     * @throws Exception
     */
    public function createColor(): void
    {
        $this->isAdmin();
        if (true === Color::create(ColorValidator::generateFromRequest())) {
            header( 'Location:'.App::config('url').'Dashboard/Colors');
        } else {
            throw new Exception('upload error');
        }
    }

    public function updateColor(string $id): void
    {
        $this->isAdmin();
        Color::update(ColorValidator::generateFromRequest($id));
        header( 'Location:'.App::config('url').'Dashboard/Colors');
    }

    public function deleteColor(string $id): void
    {
        $this->isAdmin();
        Color::delete(ColorValidator::generateFromRequest($id));
        header( 'Location:'.App::config('url').'Dashboard/Colors');
    }
}
