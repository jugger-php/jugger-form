# FormField

Поля формы - это виджеты, в которых хранятся данные модели и занимаются только одной задачей: отображением полей. Каждое поле это экземпляр класса `jugger\form\field\BaseFormField`.

## Создание поля

Для создания поля нужно просто создать нужный экземпляр:

```php
use jugger\form\field\InputFormField;

// в качестве параметра передается имя поля
$input = new InputFormField('name');
$input->getId(); // 'name-id'
$input->getName(); // 'name'

// установка параметров
$input->label = "My label";
$input->value = "My value";
$input->error = "My error";
$input->hint = "My hint";

// установка параметров при создании
$input = new InputFormField('name', [
    "label" => "My label",
    "value" => "My value",
    "error" => "My error",
    "hint" => "My hint",
]);
```

## Вывод поля

Вывод напрямую зависит от установленных параметров поля:

```php
$input = new InputFormField('name');

// <input id='name-id' name='name' type='text'>
$input->render();

// <label for='name-id'>My label</label>
// <input id='name-id' name='name' value='My value' type='text'>
$input->label = "My label";
$input->value = "My value";
$input->render();

// <label for='name-id'>My label</label>
// <input id='name-id' name='name' value='My value' type='text'>
// <div class='error-block'>My error</div>
$input->error = "My error";
$input->render();

// <label for='name-id'>My label</label>
// <input id='name-id' name='name' value='My value' type='text'>
// <div class='error-block'>My error</div>
// <div class='hint-block'>My hint</div>
$input->hint = "My hint";
$input->render();

// при необходимости можно выводить данные отдельно
$input->renderLabel();
$input->renderValue();
$input->renderError();
$input->renderHint();
```

Также можно настраивать атрибуты частей поля:

```php
$input = new InputFormField('name', [
    "label" => "My label",
    'labelOptions' => [
        'aria-hidden' => 'true',
    ],
    "error" => "My error",
    'errorOptions' => [
        'selected' => true,
    ],
    "value" => "My value",
    'valueOptions' => [
        'class' => 'form-value',
    ],
]);
$input->hint = "My hint";
$input->hintOptions = [
    'data' => [
        'id' => '123',
    ],
];

$input->renderLabel()); // <label for='name-id' aria-hidden='true'>My label</label>
$input->renderValue()); // <input id='name-id' name='name' value='My value' class='form-value' type='text'>
$input->renderError()); // <div selected>My error</div>
$input->renderHint());  // <div data-id='123'>My hint</div>
```

Параметры с постфиксом `*options` формируеются также, как и для HTML тегов.
[Подробнее HTML тэги](https://github.com/jugger-php/jugger-html/blob/master/docs/README.md).

Все поля отличаются между собой только выводом значения `renderValue`, поэтому ниже будет рассматриваться только данные метод.

## InputFormField

```php
$input = new InputFormField('field', [
    'type' => 'text',
    'value' => 'My value',
]);
$input->renderValue(); // <input id='field-id' name='field' value='My value' type='text'>
```

## PasswordFormField

```php
$field = new PasswordFormField('field', [
    'value' => 'My value',
]);
$field->renderValue(); // <input id='field-id' name='field' value='My value' type='password'>
```

## CheckboxFormField

```php
$field = new CheckboxFormField('field', [
    'value' => 'My value',
]);
$field->renderValue(); // <input id='field-id' name='field' value='My value' type='checkbox'>
```

## FileFormField

```php
$field = new FileFormField('field', [
    'value' => 'My value',
]);
$field->renderValue(); // <input id='field-id' name='field' value='My value' type='file'>
```

## HiddenFormField

```php
$field = new HiddenFormField('field', [
    'value' => 'My value',
]);
$field->renderValue(); // <input id='field-id' name='field' value='My value' type='hidden'>
```

## RadioFormField

```php
$field = new RadioFormField('field', [
    'value' => 'My value',
]);
$field->renderValue(); // <input id='field-id' name='field' value='My value' type='radio'>
```

## ColorFormField

```php
$field = new ColorFormField('field', [
    'value' => 'My value',
]);
$field->renderValue(); // <input id='field-id' name='field' value='My value' type='color'>
```

## DateFormField

```php
$field = new DateFormField('field', [
    'value' => 'My value',
]);
$field->renderValue(); // <input id='field-id' name='field' value='My value' type='date'>
```

## EmailFormField

```php
$field = new EmailFormField('field', [
    'value' => 'My value',
]);
$field->renderValue(); // <input id='field-id' name='field' value='My value' type='email'>
```

## NumberFormField

```php
$field = new NumberFormField('field', [
    'value' => 'My value',
]);
$field->renderValue(); // <input id='field-id' name='field' value='My value' type='number'>
```

## RangeFormField

```php
$field = new RangeFormField('field', [
    'value' => 'My value',
]);
$field->renderValue(); // <input id='field-id' name='field' value='My value' type='range'>
```

## TelFormField

```php
$field = new TelFormField('field', [
    'value' => 'My value',
]);
$field->renderValue(); // <input id='field-id' name='field' value='My value' type='tel'>
```

## TimeFormField

```php
$field = new TimeFormField('field', [
    'value' => 'My value',
]);
$field->renderValue(); // <input id='field-id' name='field' value='My value' type='time'>
```

## UrlFormField

```php
$field = new UrlFormField('field', [
    'value' => 'My value',
]);
$field->renderValue(); // <input id='field-id' name='field' value='My value' type='url'>
```

## TextareaFormField

```php
$field = new TextareaFormField('field', [
    'value' => 'My value',
]);
$field->renderValue(); // <textarea name='field'>My value</textarea>
```

## SelectFormField

```php
// не ассоциативный список
$field = new SelectFormField('field', [
    'assoc' => false,
    'values' => ['yes', 'no'],
]);
$field->renderValue();
// output:
//
// <select name='field'>
// <option>yes</option>
// <option>no</option>
// </select>

// ассоциативный список
$field->assoc = true;
$field->value = 'k2';
$field->values = [
    'k1' => 'value1',
    'k2' => 'value2',
    'k3' => 'value3',
];
$field->renderValue();
// output:
//
// <select name='field'>
// <option value='k1'>value1</option>
// <option value='k2' selected>value2</option>
// <option value='k3'>value3</option>
// </select>
```

## CheckboxListFormField

```php
// не ассоциативный список
$field = new CheckboxListFormField('field', [
    'assoc' => false,
    'values' => ['yes', 'no'],
]);
$field->renderValue();
// output:
//
// <div class='values-block'>
// <input id='field-1-id' type='checkbox' name='field[]' value='yes'> <label for='field-1-id'>yes</label>
// <input id='field-2-id' type='checkbox' name='field[]' value='no'> <label for='field-2-id'>no</label>
// </div>

// ассоциативный список
$field->assoc = true;
$field->value = 'k2';
$field->values = [
    'k1' => 'value1',
    'k2' => 'value2',
    'k3' => 'value3',
];
$field->renderValue();
// output:
//
// <div class='values-block'>
// <input id='field-1-id' type='checkbox' name='field[]' value='k1'> <label for='field-1-id'>value1</label>
// <input id='field-2-id' type='checkbox' name='field[]' value='k2' checked> <label for='field-2-id'>value2</label>
// <input id='field-3-id' type='checkbox' name='field[]' value='k3'> <label for='field-3-id'>value3</label>
// </div>
```

## RadioListFormField

```php
// не ассоциативный список
$field = new RadioListFormField('field', [
    'assoc' => false,
    'values' => ['yes', 'no'],
]);
$field->renderValue();
// output:
//
// <div class='values-block'>
// <input id='field-1-id' type='radio' name='field' value='yes'> <label for='field-1-id'>yes</label>
// <input id='field-2-id' type='radio' name='field' value='no'> <label for='field-2-id'>no</label>
// </div>

// ассоциативный список
$field->assoc = true;
$field->value = 'k2';
$field->values = [
    'k1' => 'value1',
    'k2' => 'value2',
    'k3' => 'value3',
];
$field->renderValue();
// output:
//
// <div class='values-block'>
// <input id='field-1-id' type='radio' name='field' value='k1'> <label for='field-1-id'>value1</label>
// <input id='field-2-id' type='radio' name='field' value='k2' checked> <label for='field-2-id'>value2</label>
// <input id='field-3-id' type='radio' name='field' value='k3'> <label for='field-3-id'>value3</label>
// </div>
```

## Динамическое отображение

Для динамического отображения можно использовать `CallbackFormField` или `WidgetFormField`

### CallbackFormField

```php
$field = new CallbackFormField('field', [
    'callback' => function($value) {
        return "<span>{$value}</span>";
    },
]);
$field->renderValue(); // <span></span>
```

### WidgetFormField

Для отображение виджета, нужен собственно виджет. Для отображения используется только статический метод `widget` класса `jugger\ui\Widget`, внутри класс не проверяется, поэтому можно использовать утиную типизацию.

```php
// класс виджета
class MyWidget extends Widget
{
    public $name;
    public $value;

    public function run()
    {
        return "name: {$this->name}; value: {$this->value};";
    }
}

$field = new WidgetFormField('field', [
    'value' => '456',
    'class' => MyWidget::class,
]);
$field->renderValue(); // name: field; value: 456;
```
