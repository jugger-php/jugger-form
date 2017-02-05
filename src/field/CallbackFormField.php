<?php

namespace jugger\form\field;

class CallbackFormField extends BaseFormField
{
    protected $callback;

    public function renderValue(array $options = [])
    {
        $options = array_merge($this->valueOptions, $options);
        return ($this->callback)($this->value, $options);
    }
}
