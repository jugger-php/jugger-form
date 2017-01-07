<?php

use PHPUnit\Framework\TestCase;

use jugger\form\Form;
use jugger\form\Renderer;
use jugger\model\Model;
use jugger\model\field\TextField;
use jugger\model\validator\RepeatValidator;
use jugger\model\validator\RequireValidator;

class LoginForm extends Form
{
    public static function getSchema(Model $model): array
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
                    new RepeatValidator("password", $model),
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
        $this->assertEquals($errors['username'], RequireValidator::class);
        $this->assertEquals($errors['password'], RequireValidator::class);
        $this->assertEquals($errors['password_repeat'], RequireValidator::class);

        // repeat validator
        $form = new LoginForm();
        $form->username = 'login';
        $form->password = 'password';
        $form->password_repeat = 'password repeat';
        $errors = $form->submit();
        $this->assertFalse(isset($errors['username']));
        $this->assertFalse(isset($errors['password']));
        $this->assertEquals($errors['password_repeat'], RepeatValidator::class);
    }

    public function testRender()
    {
        $form = LoginForm::load([]);
        $form->submit();
        $renderer = new Renderer($form);

        /*
         * label
         */
        $this->assertEquals(
            $renderer->field('username')->renderLabel(),
            "<label class='form-field__label' for='username-id'>username</label>"
        );
        $this->assertEquals(
            $renderer->field('password')->renderLabel(),
            "<label class='form-field__label' for='password-id'>Password label</label>"
        );
        $this->assertEquals(
            $renderer->field('password')->label('New label', ['class' => 'form-control-label'])->renderLabel(),
            "<label class='form-control-label' for='password-id'>New label</label>"
        );

        /*
         * hint
         */
        $this->assertEquals(
            $renderer->field('username')->renderHint(),
            ""
        );
        $this->assertEquals(
            $renderer->field('password_repeat')->renderHint(),
            "<small class='form-field__hint'>Password and Password repeat must be equals</small>"
        );
        $this->assertEquals(
            $renderer->field('password_repeat')->hint('New hint', ['class' => 'form-text'])->renderHint(),
            "<small class='form-text'>New hint</small>"
        );

        /*
         * input
         */
        $this->assertEquals(
            $renderer->field('username')->renderInput(),
            "<input type='text' id='username-id' class='form-field__input' name='username'>"
        );
        $types = ['text', 'email', 'password', 'checkbox', 'radio'];
        foreach ($types as $type) {
            $this->assertEquals(
                $renderer->field('username')->$type()->renderInput(),
                "<input type='{$type}' id='username-id' class='form-field__input' name='username'>"
            );
        }
        $this->assertEquals(
            $renderer->field('username')->textarea()->renderInput(),
            "<textarea id='username-id' class='form-field__input' name='username'></textarea>"
        );
        $this->assertEquals(
            $renderer->field('username')->checkboxList(['value1', 'value2', 'value3'])->renderInput(),
            "<div id='username-id' class='form-field__input'><label><input type='checkbox' name='username[]' value='value1'> value1</label><label><input type='checkbox' name='username[]' value='value2'> value2</label><label><input type='checkbox' name='username[]' value='value3'> value3</label></div>"
        );
        $this->assertEquals(
            $renderer->field('username')->radioList(['value1', 'value2', 'value3'], ['class' => 'form-group'])->renderInput(),
            "<div id='username-id' class='form-group'><label><input type='radio' name='username' value='value1'> value1</label><label><input type='radio' name='username' value='value2'> value2</label><label><input type='radio' name='username' value='value3'> value3</label></div>"
        );

        /*
         * input with value
         */
        $form->username = 'value1';
        $this->assertEquals(
            $renderer->field('username')->renderInput(),
            "<input type='text' id='username-id' class='form-field__input' name='username' value='value1'>"
        );
        $this->assertEquals(
            $renderer->field('username')->textarea()->renderInput(),
            "<textarea id='username-id' class='form-field__input' name='username'>value1</textarea>"
        );
        $this->assertEquals(
            $renderer->field('username')->checkboxList(['value1', 'value2'])->renderInput(),
            "<div id='username-id' class='form-field__input'><label><input type='checkbox' name='username[]' value='value1' checked> value1</label><label><input type='checkbox' name='username[]' value='value2'> value2</label></div>"
        );

        /*
         * error
         */
        $form->username = "value";
        $form->submit();
        $this->assertEquals(
            $renderer->field('username')->renderError(),
            ""
        );
        
        $form->username = null;
        $form->submit();
        $this->assertEquals(
            $renderer->field('username')->renderError(),
            "<div class='form-field__error'>jugger\\model\\validator\\RequireValidator</div>"
        );

        /*
         * all
         */
        $this->assertEquals(
            $renderer->field('username')->render(),
            "<div class='form-field'><label class='form-field__label' for='username-id'>username</label><input type='text' id='username-id' class='form-field__input' name='username'><div class='form-field__error'>jugger\\model\\validator\\RequireValidator</div></div>"
        );
    }
}
