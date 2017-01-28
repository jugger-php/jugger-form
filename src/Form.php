<?php

namespace jugger\form;

use jugger\model\Model;

abstract class Form extends Model
{
    public static function getName()
    {
        return (new \ReflectionClass(get_called_class()))->getShortName();
    }

    public static function load(array $request)
    {
        $form = new static();
        $form->setValues($request[$form::getName()] ?? $request);
        return $form;
    }

    public function submit(): array
    {
        if (!$this->validate()) {
            return $this->getErrors();
        }

        $result = $this->handle();
        if ($result->isSuccess()) {
            return [];
        }
        else {
            return [$result->getMessage()];
        }
    }
}
