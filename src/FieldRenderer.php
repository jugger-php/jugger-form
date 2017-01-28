<?php

namespace jugger\form;

use jugger\base\GetSetTrait;
use jugger\html\Tag;
use jugger\html\ContentTag;

class FieldRenderer
{
    use GetSetTrait;
    use WidgetsFieldRendererTrait;

    protected $name;
    protected $form;
    protected $widget;
    protected $callback;

    public $hint;
    public $label;
    public $error;
    public $value;

    public function __construct(string $name, Form $form)
    {
        $this->name = $name;
        $this->form = $form;
    }

    public function getId()
    {
        return "{$this->form->getName()}-{$this->name}-id";
    }

    public function getName()
    {
        return $this->name;
    }

    public function hint($value)
    {
        $this->hint = $value;
        return $this;
    }

    public function label($value)
    {
        $this->label = $value;
        return $this;
    }

    public function error($value)
    {
        $this->error = $value;
        return $this;
    }

    public function value($value)
    {
        $this->value = $value;
        return $this;
    }

    public function callback(\Closure $value)
    {
        $this->callback = $value;
        return $this;
    }

    public function widget(string $class, array $config = [])
    {
        $this->widget = compact('class', 'config');
        return $this;
    }

    public function __toString()
    {
        return $this->render();
    }

    public function render()
    {
        $content  = "<div class='form-group'>";
        $content .= $this->renderLabel();
        $content .= $this->renderField();
        $content .= $this->renderError();
        $content .= $this->renderHint();
        $content .= "</div>";
        return $content;
    }

    public function renderLabel()
    {
        if (!$this->label) {
            return "";
        }

        $tag = new ContentTag('label', $this->label, [
            'for' => $this->getId(),
        ]);
        return $tag->render();
    }

    public function renderField()
    {
        if ($this->widget) {
            $class = $this->widget['class'];
            $config = $this->widget['config'];
            $config['field'] = $this;

            return $class::widget([$config]);
        }
        elseif ($this->callback) {
            return ($this->callback)($this);
        }
        else {
            $this->text();
            return ($this->callback)($this);
        }
    }

    public function renderError()
    {
        if (!$this->error) {
            return "";
        }

        $tag = new ContentTag('div', $this->error, [
            'class' => 'error-block',
        ]);
        return $tag->render();
    }

    public function renderHint()
    {
        if (!$this->hint) {
            return "";
        }

        $tag = new ContentTag('div', $this->hint, [
            'class' => 'help-block',
        ]);
        return $tag->render();
    }
}
