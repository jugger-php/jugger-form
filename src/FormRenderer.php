<?php

namespace jugger\form;

use jugger\model\Model;

class FormRenderer
{
    protected $form;

    public function __construct(Form $form)
    {
        $this->form = $form;
    }

    public function field(string $name)
    {
        $hint = $form->getHint($name);
        $value = $form->getValue($name);
        $label = $form->getLabel($name);
        return new FormField($name, $value, $label, $hint);
    }

    public function captcha(Captcha $captcha)
    {
        
    }
}
