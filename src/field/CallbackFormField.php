<?php

namespace jugger\form\field;

class CallbackFormField extends BaseFormField
{
    protected $callback;

    public function renderValue(array $options = [])
    {
        return ($this->callback)($this->value);
    }
}
