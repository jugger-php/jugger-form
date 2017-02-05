<?php

use PHPUnit\Framework\TestCase;

use jugger\form\Form;
use jugger\form\field\InputFormField;
use jugger\form\field\PasswordFormField;
use jugger\form\field\CallbackFormField;

use jugger\model\Model;
use jugger\model\field\TextField;
use jugger\model\validator\RepeatValidator;
use jugger\model\validator\RequireValidator;

class LoginForm extends Model
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
    public function getModel()
    {
        $model = new LoginForm([
            'username' => 'login',
            'password' => 'password',
            'password_repeat' => 'password',
        ]);
        return $model;
    }

    public function testEasyForm()
    {
        $model = $this->getModel();
        $form = new Form($model);

        ob_start();

        echo $form->renderBegin();
        echo $form->text('username');
        echo $form->password('password');
        echo $form->password('password_repeat');
        echo $form->renderEnd();

        $content = ob_get_clean();
        $this->assertEquals(
            $content,
            "<form>".
            "<label for='username-id'>username</label>".
            "<input id='username-id' name='username' value='login' type='text'>".
            "<label for='password-id'>Password label</label>".
            "<input id='password-id' name='password' value='password' type='password'>".
            "<label for='password_repeat-id'>Password repeat label</label>".
            "<input id='password_repeat-id' name='password_repeat' value='password' type='password'>".
            "<div class='hint-block'>Password and Password repeat must be equals</div>".
            "</form>"
        );
    }

    public function testBuilder()
    {
        $model = $this->getModel();
        $content = Form::renderNow($model, [
            'options' => [
                'action' => '/',
                'method' => 'post',
                'class' => 'form',
            ],
            'fields' => [
                new InputFormField('username', [
                    'type' => 'text',
                    'label' => 'My label',
                ]),
                new PasswordFormField('password', [
                    'error' => 'her',
                ]),
                new CallbackFormField('password_repeat', [
                    'callback' => function($value) {
                        return "password_repeat: {$value}";
                    },
                ]),
            ],
        ]);

        $this->assertEquals(
            $content,
            join([
                "<form action='/' method='post' class='form'>",
                    // username
                    "<label for='username-id'>My label</label>",
                    "<input id='username-id' name='username' value='login' type='text'>",
                    // password
                    "<label for='password-id'>Password label</label>",
                    "<input id='password-id' name='password' value='password' type='password'>",
                    "<div class='error-block'>her</div>",
                    // password repeat
                    "<label for='password_repeat-id'>Password repeat label</label>",
                    "password_repeat: password",
                    "<div class='hint-block'>Password and Password repeat must be equals</div>",
                "</form>",
            ])
        );
    }

    public function testObject()
    {
        $model = $this->getModel();

        $form = new Form($model, [
            'action' => '/',
            'method' => 'post',
            'class' => 'form',
        ]);

        ob_start();

        echo $form->renderBegin();
        echo $form->text('username', [
            'label' => 'My label',
        ]);
        echo $form->password('password', [
            'error' => 'her',
        ]);
        echo $form->callback('password_repeat', [
            'callback' => function($value) {
                return "password_repeat: {$value}";
            },
        ]);
        echo $form->renderEnd();

        $content = ob_get_clean();

        $this->assertEquals(
            $content,
            join([
                "<form action='/' method='post' class='form'>",
                    // username
                    "<label for='username-id'>My label</label>",
                    "<input id='username-id' name='username' value='login' type='text'>",
                    // password
                    "<label for='password-id'>Password label</label>",
                    "<input id='password-id' name='password' value='password' type='password'>",
                    "<div class='error-block'>her</div>",
                    // password repeat
                    "<label for='password_repeat-id'>Password repeat label</label>",
                    "password_repeat: password",
                    "<div class='hint-block'>Password and Password repeat must be equals</div>",
                "</form>",
            ])
        );
    }
}
