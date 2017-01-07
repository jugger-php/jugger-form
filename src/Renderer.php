<?php

namespace jugger\form;

class Renderer
{
    protected $form;
    protected $classField;

    public function __construct(Form $form, string $classField = '\jugger\form\renderer\Field')
    {
        $this->form = $form;
        $this->classField = $classField;
    }

    public function field(string $name)
    {
        $class = $this->classField;
        $field = new $class($name);
        $field->hint = $this->form->getHint($name) ?? null;
        $field->value = $this->form->getValue($name) ?? null;
        $field->label = $this->form->getLabel($name) ?? null;
        $field->error = $this->form->getError($name) ?? null;
        return $field;
    }
}
