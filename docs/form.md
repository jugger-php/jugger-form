# Form

Для удобства работы с формами существует класс `Form`. Данный класс позволяет преобразовать модель ([Model](https://github.com/jugger-php/jugger-model/blob/master/docs/README.md)) в HTML форму.

## Создание формы

Допустим у нас есть модель `LoginForm`:

```php
use jugger\model\Model;

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
```

Создание формы будет выглядеть так:

```php
use jugger\form\Form;

$model = new LoginForm();
$model->setValues($_POST);

$form = new Form($model);
```

И соответствующий вывод формы:

```php
<?= $form->renderBegin() ?>

<div class='form-group'>
    <?= $form->text('username') ?>
</div>
<div class='form-group'>
    <?= $form->password('password') ?>
</div>
<div class='form-group'>
    <?= $form->password('password_repeat') ?>
</div>
<div class='form-group'>
    <button type='submit'>Вход</button>
</div>

<?= $form->renderEnd() ?>
```

На выходе будет следущий код:

```HTML
<form>
    <div class='form-group'>
        <label for='username-id'>username</label>
        <input id='username-id' name='username' type='text'>
    </div>
    <div class='form-group'>
        <label for='password-id'>Password label</label>
        <input id='password-id' name='password' type='password'>
    </div>
    <div class='form-group'>
        <label for='password_repeat-id'>Password repeat label</label>
        <input id='password_repeat-id' name='password_repeat' type='password'>
        <div class='hint-block'>Password and Password repeat must be equals</div>
    </div>
    <div class='form-group'>
        <button type='submit'>Вход</button>
    </div>
</form>
```

## Параметры формы

Для того чтобы задать атрибуты формы, необходимо их передать в качетсве второго параметра при создании:

```php
// <form action='/' method='post' class='form'>
$form = new Form($model, [
    'action' => '/',
    'method' => 'post',
    'class' => 'form',
]);
```

## RenderNow

Для того, чтобы сразу вывести форму, нужно вызвать метод `renderNow` и в качестве возвращаемого заначения будет возвращен HTML код:

```php
Form::renderNow($model, [
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
```

## Поля формы

Как можно заметить в примере выше, каждое поле формы - это объект, который реализует класс `BaseFormField`. При использовании объекта формы, этот код сокращается:

```php
$form->text('username', [
    'label' => 'My label'
]);
// равносильно
$input = new InputFormField('username', [
    'type' => 'text',
    'label' => 'My label'
]);
```

[Подробнее про поля формы](form-field.md).
