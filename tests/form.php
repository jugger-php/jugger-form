<?php

use PHPUnit\Framework\TestCase;

use jugger\form\Form;
use jugger\form\fields\TextField;
use jugger\form\fields\ButtonField;
use jugger\form\fields\PasswordField;

class LoginForm extends Form
{
    public static function getSchema()
    {
        return [];
    }
}

class FormTest extends TestCase
{
    public function testBase()
    {
        // backend
        $form = LoginForm::load($_POST);
        $errors = $form->submit();
        if (empty($errors)) {
            // success
        }

        // frontend
        $renderer = new FormRenderer($form);

        $renderer->field('username')->text();
        $renderer->field('password')->password();
        $renderer->field('password_repeat')->password()->label('повторите пароль')->hint('подсказка для поля');

        $renderer->captcha(new ReCapctha());
    }
}
