<?php

declare(strict_types=1);

class View{

    private string $layout;

    public function __construct($layout='layout')
    {
        $this->layout = basename($layout);
    }

    public function render($name, $args=[]): static
    {
        ob_start();
        extract($args);
        include BP . 'app/view/'.$name.'.phtml';
        $content = ob_get_clean();
        if($this->layout) {
            include BP . 'app/view/'.$this->layout.'.phtml';
        }else{
            echo $content;
        }
        return $this;
    }
}
