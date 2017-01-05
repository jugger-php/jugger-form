<?php

namespace jugger\form;

use jugger\model\Model;

class FormField
{
    protected $name;
    protected $hint;
    protected $value;
    protected $label;

    public function __construct(string $name, $value, string $label, string $hint)
    {
        $this->name = $name;
        $this->hint = $hint;
        $this->value = $value;
        $this->label = $label;
    }
}
