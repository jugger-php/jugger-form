<?php

namespace jugger\form;

use jugger\form\field\BaseFormField;
use jugger\form\field\InputFormField;
use jugger\form\field\RadioListFormField;
use jugger\form\field\CheckboxListFormField;
use jugger\form\field\CallbackFormField;
use jugger\form\field\TextareaFormField;
use jugger\form\field\SelectFormField;

use jugger\html\Tag;
use jugger\model\Model;

class Form
{
    protected $model;
    protected $fields;
    protected $options;

    public function __construct(Model $model, array $options = [])
    {
        $this->model = $model;
        $this->options = $options;
    }

    public function fillField(BaseFormField $field): BaseFormField
    {
        $name = $field->getName();

        $field->value = $field->value ?? $this->model->$name;
        $field->error = $field->error ?? $this->model->getError($name);
        $field->label = $field->label ?? $this->model::getLabel($name);
        $field->hint  = $field->hint  ?? $this->model::getHint($name);

        return $field;
    }

    public function renderBegin(array $options = []): string
    {
        $options = array_merge($this->options, $options);
        $tag = new Tag('form', $options);
        return $tag->render();
    }

    public function renderEnd(): string
    {
        return "</form>";
    }

    public static function renderNow(Model $model, array $options): string
    {
        $fields = $options['fields'];
        $options = $options['options'];

        $form = new static($model, $options);
        $content  = $form->renderBegin();
        foreach ($fields as $field) {
            $content .= $form->fillField($field)->render();
        }

        return $content . $form->renderEnd();
    }

    public function __call($name, $arguments)
    {
        if (empty($arguments)) {
            throw new \ErrorException("Not found method '{$name}'");
        }

        $field = null;
        $fieldName = $arguments[0];
        $attributes = $arguments[1] ?? [];

        switch ($name) {
            case 'callback':
                $field = new CallbackFormField($fieldName, $attributes);
                break;
            case 'checkboxList':
                $field = new CheckboxListFormField($fieldName, $attributes);
                break;
            case 'select':
                $field = new SelectFormField($fieldName, $attributes);
                break;
            case 'textarea':
                $field = new TextareaFormField($fieldName, $attributes);
                break;
            case 'radioList':
                $field = new RadioListFormField($fieldName, $attributes);
                break;
            case 'color':
            case 'date':
            case 'email':
            case 'file':
            case 'hidden':
            case 'number':
            case 'password':
            case 'radio':
            case 'range':
            case 'tel':
            case 'text':
            case 'time':
            case 'url':
                $attributes['type'] = $name;
                $field = new InputFormField($fieldName, $attributes);
                break;
            default:
                throw new \ErrorException("Not found method '{$name}'");
        }

        return $this->fillField($field)->render();
    }
}
