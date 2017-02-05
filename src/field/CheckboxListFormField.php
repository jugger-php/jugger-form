<?php

namespace jugger\form\field;

use jugger\html\Tag;
use jugger\html\ContentTag;

class CheckboxListFormField extends InputFormField
{
    public $assoc = true;
    public $values = [];
    public $valueOptions = [
        'class' => 'values-block',
    ];

    public function renderValue(array $options = [])
    {
        $i = 0;
        $content = "";
        $options = array_merge(
            $this->valueOptions,
            $options
        );

        foreach ($this->values as $key => $value) {
            $i++;
            $name = $this->getName();
            $input = new Tag('input', [
                'id' => "{$name}-{$i}-id",
                'type' => 'checkbox',
                'name' => "{$name}[]",
                'value' => $value,
            ]);
            $label = new ContentTag('label', $value, [
                'for' => $input->id,
            ]);

            if ($this->assoc) {
                $input->value = $key;
            }
            if ($this->assoc && $this->value == $key || $this->value == $value) {
                $input->checked = true;
            }

            $content .= $input->render() ." ". $label->render();
        }

        return (new ContentTag('div', $content, $options))->render();
    }
}
