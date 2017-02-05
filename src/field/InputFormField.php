<?php

namespace jugger\form\field;

use jugger\html\tag;

class InputFormField extends BaseFormField
{
    public $type = 'text';

    public function renderValue(array $options = [])
    {
        $options = array_merge(
            [
                'id' => $this->getId(),
                'name' => $this->name,
                'value' => $this->value,
            ],
            $this->valueOptions,
            $options
        );

        $input = new tag\Input($this->type, $options);
        return $input->render();
    }
}
