<?php

namespace jugger\form;

class FormRenderer
{
    protected $form;
    protected $fieldClass;

    public function __construct(Form $form, string $fieldClass = '\jugger\form\FieldRenderer')
    {
        $this->form = $form;
        $this->fieldClass = $fieldClass;
    }

    public function begin(array $options)
    {
        return "<form>";
    }

    public function field(string $name)
    {
        $class = $this->fieldClass;
        $field = new $class($name, $this->form);
        $field->hint = $this->form->getHint($name) ?? null;
        $field->value = $this->form->getValue($name) ?? null;
        $field->label = $this->form->getLabel($name) ?? null;
        $field->error = $this->form->getError($name) ?? null;
        return $field;
    }

    public function end()
    {
        return "</form>";
    }
}
