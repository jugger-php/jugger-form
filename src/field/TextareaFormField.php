<?php

namespace jugger\form\field;

use jugger\html\ContentTag;

class TextareaFormField extends BaseFormField
{
    public function renderValue(array $options = [])
    {
        $options = array_merge(
            [
                'name' => $this->getName(),
            ],
            $this->valueOptions,
            $options
        );
        $tag = new ContentTag('textarea', $this->value, $options);
        return $tag->render();
    }
}
