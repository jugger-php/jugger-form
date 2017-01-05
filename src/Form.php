<?php

namespace jugger\form;

use jugger\model\Model;

class Form extends Model
{
    public static function load(array $request)
    {
        $form = new static();
        $form->setValues($request[$form->name] ?? $request);
        return $form;
    }

    public static function getLabel(string $name)
    {
        return static::getLabels()[$name] ?? $name;
    }

    public static function getLabels(): array
    {
        return [];
    }

    public static function getHint(string $name)
    {
        return static::getHints()[$name] ?? null;
    }

    public static function getHints(): array
    {
        return [];
    }

    public function submit()
    {
        if (!$this->validate()) {
            return false;
        }

        $result = $this->handle();
        if ($result->isSuccess()) {
            return true;
        }
        else {
            throw $result->getException();
        }
    }
}
