<?php

namespace jugger\form\field;

use jugger\html\ContentTag;

abstract class BaseFormField
{
    protected $name;

    public $label;
    public $value;
    public $error;
    public $hint;

    public $labelOptions = [];
    public $valueOptions = [];
    public $errorOptions = [
        'class' => 'error-block',
    ];
    public $hintOptions = [
        'class' => 'hint-block',
    ];

    public function __construct(string $name, array $options = [])
    {
        $this->name = $name;
        $this->initAttributes($options);
    }

    public function getId()
    {
        return "{$this->name}-id";
    }

    public function getName()
    {
        return $this->name;
    }

    protected function initAttributes(array $options)
    {
        foreach ($options as $name => $value) {
            if (property_exists($this, $name)) {
                $this->$name = $value;
            }
        }
    }

    public function render()
    {
        $content = "";
        if ($this->label) {
            $content .= $this->renderLabel();
        }

        $content .= $this->renderValue();
        
        if ($this->error) {
            $content .= $this->renderError();
        }
        if ($this->hint) {
            $content .= $this->renderHint();
        }
        return $content;
    }

    abstract public function renderValue(array $options = []);

    public function renderLabel(array $options = [])
    {
        $options = array_merge(
            [
                'for' => $this->getId(),
            ],
            $this->labelOptions,
            $options
        );
        $tag = new ContentTag('label', $this->label, $options);
        return $tag->render();
    }

    public function renderError(array $options = [])
    {
        $options = array_merge($this->errorOptions, $options);
        $tag = new ContentTag('div', $this->error, $options);
        return $tag->render();
    }

    public function renderHint(array $options = [])
    {
        $options = array_merge($this->hintOptions, $options);
        $tag = new ContentTag('div', $this->hint, $options);
        return $tag->render();
    }
}
