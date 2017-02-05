<?php

namespace jugger\form\field;

use jugger\html\ContentTag;

class SelectFormField extends InputFormField
{
    public $assoc = true;
    public $values = [];

    public function renderValue(array $options = [])
    {
        $options = array_merge(
            [
                'name' => $this->getName(),
            ],
            $this->valueOptions,
            $options
        );
        $content = "";
        foreach ($this->values as $key => $value) {
            $op = new ContentTag('option', $value);

            if ($this->assoc) {
                $op->value = $key;
            }
            if ($this->assoc && $this->value == $key || $this->value == $value) {
                $op->selected = true;
            }

            $content .= $op->render();
        }

        return (new ContentTag('select', $content, $options))->render();
    }
}
