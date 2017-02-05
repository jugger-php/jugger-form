<?php

namespace jugger\form\field;

class WidgetFormField extends InputFormField
{
    public $class;

    public function renderValue(array $options = [])
    {
        $options = array_merge(
            [
                'name' => $this->name,
                'value' => $this->value,
            ],
            $options
        );

        return $this->class::widget($options);
    }
}
