<?php

use PHPUnit\Framework\TestCase;

use jugger\form\Form;
use jugger\form\FormRenderer;
use jugger\form\FieldRenderer;
use jugger\model\field\TextField;
use jugger\model\validator\RepeatValidator;
use jugger\model\validator\RequireValidator;

class LoginForm extends Form
{
    protected function init()
    {
        parent::init();
        $this->getField('password_repeat')->addValidator(new RepeatValidator("password", $this));
    }

    public static function getSchema(): array
    {
        return [
            new TextField([
                'name' => 'username',
                'validators' => [
                    new RequireValidator()
                ],
            ]),
            new TextField([
                'name' => 'password',
                'validators' => [
                    new RequireValidator()
                ],
            ]),
            new TextField([
                'name' => 'password_repeat',
                'validators' => [
                    new RequireValidator(),
                ],
            ]),
        ];
    }

    public static function getLabels(): array
    {
        return [
            'password' => 'Password label',
            'password_repeat' => 'Password repeat label',
        ];
    }

    public static function getHints(): array
    {
        return [
            'password_repeat' => 'Password and Password repeat must be equals',
        ];
    }
}

class FormTest extends TestCase
{
    public function testBase()
    {
        // empty
        $form = LoginForm::load([
            'username' => 'login',
            'password' => 'password',
            'password_repeat' => 'password',
        ]);
        $errors = $form->submit();
        $this->assertEmpty($errors);

        // filling
        $form = LoginForm::load($_POST);
        $errors = $form->submit();
        $this->assertEquals($errors['username'], "Поле 'username': обязательно для заполнения");
        $this->assertEquals($errors['password'], "Поле 'Password label': обязательно для заполнения");
        $this->assertEquals($errors['password_repeat'], "Поле 'Password repeat label': обязательно для заполнения");

        // repeat validator
        $form = new LoginForm();
        $form->username = 'login';
        $form->password = 'password';
        $form->password_repeat = 'password repeat';
        $errors = $form->submit();
        $this->assertFalse(isset($errors['username']));
        $this->assertFalse(isset($errors['password']));
        $this->assertEquals($errors['password_repeat'], "Поле 'Password repeat label': значение должно совпадать с полем 'Password label'");
    }

    public function testRender()
    {
        $model = LoginForm::load([]);
        $model->submit();

        $form = new FormRenderer($model);
        $this->assertEquals(
            $form->field('username')->render(),
            "<div class='form-group'><label for='LoginForm-username-id'>username</label><input id='LoginForm-username-id' type='text'><div class='error-block'>Поле 'username': обязательно для заполнения</div></div>"
        );
        $this->assertEquals(
            $form->field('password')->render(),
            "<div class='form-group'><label for='LoginForm-password-id'>Password label</label><input id='LoginForm-password-id' type='text'><div class='error-block'>Поле 'Password label': обязательно для заполнения</div></div>"
        );
        $this->assertEquals(
            $form->field('password_repeat')->render(),
            "<div class='form-group'><label for='LoginForm-password_repeat-id'>Password repeat label</label><input id='LoginForm-password_repeat-id' type='text'><div class='error-block'>Поле 'Password repeat label': обязательно для заполнения</div><div class='help-block'>Password and Password repeat must be equals</div></div>"
        );

        // fileds

        $this->assertEquals(
            $form->field('username')->renderField(),
            "<input id='LoginForm-username-id' type='text'>"
        );
        $this->assertEquals(
            $form->field('username')->input('email')->renderField(),
            "<input id='LoginForm-username-id' type='email'>"
        );
        $this->assertEquals(
            $form->field('username')->text(['class' => 'form-control'])->renderField(),
            "<input class='form-control' id='LoginForm-username-id' type='text'>"
        );
        $this->assertEquals(
            $form->field('username')->callback(function(FieldRenderer $field){
                return "name: {$field->name}; value: {$field->value}";
            })->renderField(),
            "name: username; value: "
        );
    }
}
