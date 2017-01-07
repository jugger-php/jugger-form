<?php

namespace jugger\form\renderer;

use jugger\html\Html;

class Field
{
    protected $name;
    protected $type = 'text';

    public $hint;
    public $label;
    public $error;
    public $value;
    public $values = [];

    protected $hintOptions = [
        'class' => 'form-field__hint'
    ];
    protected $inputOptions = [
        'class' => 'form-field__input'
    ];
    protected $errorOptions = [
        'class' => 'form-field__error'
    ];
    protected $labelOptions = [
        'class' => 'form-field__label'
    ];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function hint(string $hint, array $options = []): Field
    {
        $this->hint = $hint;
        $this->hintOptions = array_merge($this->hintOptions, $options);
        return $this;
    }

    public function error(string $error, array $options = []): Field
    {
        $this->error = $error;
        $this->errorOptions = array_merge($this->errorOptions, $options);
        return $this;
    }

    public function label(string $label, array $options = []): Field
    {
        $this->label = $label;
        $this->labelOptions = array_merge($this->labelOptions, $options);
        return $this;
    }

    public function input(string $type, array $options = []): Field
    {
        $this->type = $type;
        $this->inputOptions = array_merge($this->inputOptions, $options);
        return $this;
    }

    public function text(array $options = []): Field
    {
        return $this->input('text', $options);
    }

    public function email(array $options = []): Field
    {
        return $this->input('email', $options);
    }

    public function password(array $options = []): Field
    {
        return $this->input('password', $options);
    }

    public function checkbox(array $options = []): Field
    {
        return $this->input('checkbox', $options);
    }

    public function checkboxList(array $values, array $options = []): Field
    {
        $this->values = $values;
        return $this->input('checkboxList', $options);
    }

    public function radio(array $options = []): Field
    {
        return $this->input('radio', $options);
    }

    public function radioList(array $values, array $options = []): Field
    {
        $this->values = $values;
        return $this->input('radioList', $options);
    }

    public function textarea(array $options = []): Field
    {
        return $this->input('textarea', $options);
    }

    public function render(): string
    {
        $hint = $this->renderHint();
        $label = $this->renderLabel();
        $input = $this->renderInput();
        $error = $this->renderError();

        return "<div class='form-field'>{$label}{$input}{$error}{$hint}</div>";
    }

    public function getId()
    {
        return "{$this->name}-id";
    }

    public function renderLabel(): string
    {
        if ($this->label) {
            return Html::label($this->label, $this->getId(), $this->labelOptions);
        }
        return "";
    }

    public function renderInput(): string
    {
        $options = array_merge(
            [
                'id' => $this->getId()
            ],
            $this->inputOptions
        );
        switch ($this->type) {
            case 'textarea':
                return Html::textarea($this->name, $this->value, $options);
            case 'select':
                return Html::select($this->name, $this->values, $this->value, $options);
            case 'checkboxList':
                $options['checked'] = $this->value;
                return Html::checkboxList("{$this->name}[]", $this->values, $options);
            case 'radioList':
                $options['checked'] = $this->value;
                return Html::radioList($this->name, $this->values, $options);
            default:
                $options['value'] = $this->value;
                return Html::input($this->name, $this->type, $options);
        }
        return "Why this code running?";
    }

    public function renderHint(): string
    {
        if ($this->hint) {
            return Html::small($this->hint, $this->hintOptions);
        }
        return "";
    }

    public function renderError(): string
    {
        if ($this->error) {
            return Html::div($this->error, $this->errorOptions);
        }
        return "";
    }

    public function __toString()
    {
        return $this->render();
    }
}
