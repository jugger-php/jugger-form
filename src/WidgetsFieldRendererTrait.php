<?php

namespace jugger\form;

use jugger\html\Tag;
use jugger\html\ContentTag;

trait WidgetsFieldRendererTrait
{
    public function input(string $type, array $options = [])
    {
        $options['id'] = $this->getId();
        $options['type'] = $type;
        $options['value'] = $this->value;

        $this->callback = function() use($options) {
            return (new Tag('input', $options))->render();
        };
        return $this;
    }

    public function text(array $options = [])
    {
        return $this->input('text', $options);
    }

    public function password(array $options = [])
    {
        return $this->input('password', $options);
    }

    public function email(array $options = [])
    {
        return $this->input('email', $options);
    }

    public function checkbox(array $options = [])
    {
        return $this->input('checkbox', $options);
    }

    public function radio(array $options = [])
    {
        return $this->input('radio', $options);
    }

    public function hidden(array $options = [])
    {
        return $this->input('hidden', $options);
    }

    public function select(array $values, array $options = [])
    {
        return $this;
    }

    public function radioList(array $values, array $options = [])
    {
        return $this;
    }

    public function checkboxList(array $values, array $options = [])
    {
        return $this;
    }

    public function textarea(array $options = [])
    {
        return $this;
    }
}
