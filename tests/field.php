<?php

use PHPUnit\Framework\TestCase;

use jugger\ui\Widget;
use jugger\form\field\InputFormField;
use jugger\form\field\PasswordFormField;
use jugger\form\field\WidgetFormField;
use jugger\form\field\CallbackFormField;
use jugger\form\field\RadioListFormField;
use jugger\form\field\CheckboxListFormField;
use jugger\form\field\SelectFormField;
use jugger\form\field\UrlFormField;
use jugger\form\field\TimeFormField;
use jugger\form\field\TelFormField;
use jugger\form\field\TextareaFormField;
use jugger\form\field\RangeFormField;
use jugger\form\field\NumberFormField;
use jugger\form\field\EmailFormField;
use jugger\form\field\DateFormField;
use jugger\form\field\ColorFormField;
use jugger\form\field\RadioFormField;
use jugger\form\field\HiddenFormField;
use jugger\form\field\FileFormField;
use jugger\form\field\CheckboxFormField;

class MyWidget extends Widget
{
    public $name;
    public $value;

    public function run()
    {
        return "
name: {$this->name};
value: {$this->value};
";
    }
};

class FieldTest extends TestCase
{
    public function testCreate()
    {
        // по частям
        $input = new InputFormField('name');

        $this->assertEquals($input->getId(), 'name-id');
        $this->assertEquals($input->getName(), 'name');
        $this->assertNull($input->label);
        $this->assertNull($input->value);
        $this->assertNull($input->error);
        $this->assertNull($input->hint);

        $input->label = "My label";
        $input->value = "My value";
        $input->error = "My error";
        $input->hint = "My hint";
        $this->assertEquals($input->label, "My label");
        $this->assertEquals($input->value, "My value");
        $this->assertEquals($input->error, "My error");
        $this->assertEquals($input->hint, "My hint");

        // сразу
        $input = new InputFormField('name', [
            "label" => "My label",
            "value" => "My value",
            "error" => "My error",
            "hint" => "My hint",
        ]);
        $this->assertEquals($input->getId(), 'name-id');
        $this->assertEquals($input->getName(), 'name');
        $this->assertEquals($input->label, "My label");
        $this->assertEquals($input->value, "My value");
        $this->assertEquals($input->error, "My error");
        $this->assertEquals($input->hint, "My hint");

        return $input;
    }

    public function testEmptyRender()
    {
        $input = new InputFormField('name');
        $content = "<input id='name-id' name='name' type='text'>";
        $this->assertEquals($content, $input->render());

        $input = new InputFormField('name');
        $input->label = "My label";
        $input->value = "My value";

        $content = "<label for='name-id'>My label</label><input id='name-id' name='name' value='My value' type='text'>";
        $this->assertEquals($content, $input->render());

        $input->error = "My error";
        $content .= "<div class='error-block'>My error</div>";
        $this->assertEquals($content, $input->render());

        $input->hint = "My hint";
        $content .= "<div class='hint-block'>My hint</div>";
        $this->assertEquals($content, $input->render());
    }

    /**
     * @depends testCreate
     */
    public function testInput(InputFormField $input)
    {
        $label = "<label for='name-id'>My label</label>";
        $this->assertEquals($label, $input->renderLabel());

        $value = "<input id='name-id' name='name' value='My value' type='text'>";
        $this->assertEquals($value, $input->renderValue());

        $error = "<div class='error-block'>My error</div>";
        $this->assertEquals($error, $input->renderError());

        $hint = "<div class='hint-block'>My hint</div>";
        $this->assertEquals($hint, $input->renderHint());

        $this->assertEquals(
            $input->render(),
            $label . $value . $error . $hint
        );

        $input->type = 'password';
        $value = "<input id='name-id' name='name' value='My value' type='password'>";
        $this->assertEquals($value, $input->renderValue());
    }

    public function testPassword()
    {
        $input = new InputFormField('field', [
            'type' => 'password',
            'value' => 'My value',
        ]);
        $field = new PasswordFormField('field', [
            'value' => 'My value',
        ]);

        $content = "<input id='field-id' name='field' value='My value' type='password'>";
        $this->assertEquals($field->render(), $input->render());
        $this->assertEquals($field->render(), $content);
    }

    public function testCheckbox()
    {
        $input = new InputFormField('field', [
            'type' => 'checkbox',
            'value' => 'My value',
        ]);
        $field = new CheckboxFormField('field', [
            'value' => 'My value',
        ]);

        $content = "<input id='field-id' name='field' value='My value' type='checkbox'>";
        $this->assertEquals($field->render(), $input->render());
        $this->assertEquals($field->render(), $content);
    }

    public function testFile()
    {
        $input = new InputFormField('field', [
            'type' => 'file',
            'value' => 'My value',
        ]);
        $field = new FileFormField('field', [
            'value' => 'My value',
        ]);

        $content = "<input id='field-id' name='field' value='My value' type='file'>";
        $this->assertEquals($field->render(), $input->render());
        $this->assertEquals($field->render(), $content);
    }

    public function testHidden()
    {
        $input = new InputFormField('field', [
            'type' => 'hidden',
            'value' => 'My value',
        ]);
        $field = new HiddenFormField('field', [
            'value' => 'My value',
        ]);

        $content = "<input id='field-id' name='field' value='My value' type='hidden'>";
        $this->assertEquals($field->render(), $input->render());
        $this->assertEquals($field->render(), $content);
    }

    public function testRadio()
    {
        $input = new InputFormField('field', [
            'type' => 'radio',
            'value' => 'My value',
        ]);
        $field = new RadioFormField('field', [
            'value' => 'My value',
        ]);

        $content = "<input id='field-id' name='field' value='My value' type='radio'>";
        $this->assertEquals($field->render(), $input->render());
        $this->assertEquals($field->render(), $content);
    }

    public function testColor()
    {
        $input = new InputFormField('field', [
            'type' => 'color',
            'value' => 'My value',
        ]);
        $field = new ColorFormField('field', [
            'value' => 'My value',
        ]);

        $content = "<input id='field-id' name='field' value='My value' type='color'>";
        $this->assertEquals($field->render(), $input->render());
        $this->assertEquals($field->render(), $content);
    }

    public function testDate()
    {
        $input = new InputFormField('field', [
            'type' => 'date',
            'value' => 'My value',
        ]);
        $field = new DateFormField('field', [
            'value' => 'My value',
        ]);

        $content = "<input id='field-id' name='field' value='My value' type='date'>";
        $this->assertEquals($field->render(), $input->render());
        $this->assertEquals($field->render(), $content);
    }

    public function testEmail()
    {
        $input = new InputFormField('field', [
            'type' => 'email',
            'value' => 'My value',
        ]);
        $field = new EmailFormField('field', [
            'value' => 'My value',
        ]);

        $content = "<input id='field-id' name='field' value='My value' type='email'>";
        $this->assertEquals($field->render(), $input->render());
        $this->assertEquals($field->render(), $content);
    }

    public function testNumber()
    {
        $input = new InputFormField('field', [
            'type' => 'number',
            'value' => 'My value',
        ]);
        $field = new NumberFormField('field', [
            'value' => 'My value',
        ]);

        $content = "<input id='field-id' name='field' value='My value' type='number'>";
        $this->assertEquals($field->render(), $input->render());
        $this->assertEquals($field->render(), $content);
    }

    public function testRange()
    {
        $input = new InputFormField('field', [
            'type' => 'range',
            'value' => 'My value',
        ]);
        $field = new RangeFormField('field', [
            'value' => 'My value',
        ]);

        $content = "<input id='field-id' name='field' value='My value' type='range'>";
        $this->assertEquals($field->render(), $input->render());
        $this->assertEquals($field->render(), $content);
    }

    public function testTel()
    {
        $input = new InputFormField('field', [
            'type' => 'tel',
            'value' => 'My value',
        ]);
        $field = new TelFormField('field', [
            'value' => 'My value',
        ]);

        $content = "<input id='field-id' name='field' value='My value' type='tel'>";
        $this->assertEquals($field->render(), $input->render());
        $this->assertEquals($field->render(), $content);
    }

    public function testTime()
    {
        $input = new InputFormField('field', [
            'type' => 'time',
            'value' => 'My value',
        ]);
        $field = new TimeFormField('field', [
            'value' => 'My value',
        ]);

        $content = "<input id='field-id' name='field' value='My value' type='time'>";
        $this->assertEquals($field->render(), $input->render());
        $this->assertEquals($field->render(), $content);
    }

    public function testUrl()
    {
        $input = new InputFormField('field', [
            'type' => 'url',
            'value' => 'My value',
        ]);
        $field = new UrlFormField('field', [
            'value' => 'My value',
        ]);

        $content = "<input id='field-id' name='field' value='My value' type='url'>";
        $this->assertEquals($field->render(), $input->render());
        $this->assertEquals($field->render(), $content);
    }

    public function testTextarea()
    {
        $field = new TextareaFormField('field', [
            'value' => 'value text',
            'valueOptions' => [
                'class' => 'form-control',
            ],
        ]);

        $content = "<textarea name='field' class='form-control'>value text</textarea>";
        $this->assertEquals($field->render(), $content);
    }

    public function testSelect()
    {
        $field = new SelectFormField('field', [
            'assoc' => false,
            'values' => ['yes', 'no'],
        ]);

        $content = join([
            "<select name='field'>",
            "<option>yes</option>",
            "<option>no</option>",
            "</select>"
        ]);
        $this->assertEquals($field->render(), $content);

        $field->assoc = true;
        $field->value = 'k2';
        $field->values = [
            'k1' => 'value1',
            'k2' => 'value2',
            'k3' => 'value3',
        ];
        $content =
            "<select name='field'>".
            "<option value='k1'>value1</option>".
            "<option value='k2' selected>value2</option>".
            "<option value='k3'>value3</option>".
            "</select>";
        $this->assertEquals($field->render(), $content);
    }

    public function testCheckboxList()
    {
        $field = new CheckboxListFormField('field', [
            'assoc' => false,
            'values' => ['yes', 'no'],
        ]);

        $content = join([
            "<div class='values-block'>",
                "<input id='field-1-id' type='checkbox' name='field[]' value='yes'> <label for='field-1-id'>yes</label>",
                "<input id='field-2-id' type='checkbox' name='field[]' value='no'> <label for='field-2-id'>no</label>",
            "</div>"
        ]);
        $this->assertEquals($field->render(), $content);

        $field->assoc = true;
        $field->value = 'k2';
        $field->values = [
            'k1' => 'value1',
            'k2' => 'value2',
            'k3' => 'value3',
        ];
        $content = join([
            "<div class='values-block'>",
                "<input id='field-1-id' type='checkbox' name='field[]' value='k1'> <label for='field-1-id'>value1</label>",
                "<input id='field-2-id' type='checkbox' name='field[]' value='k2' checked> <label for='field-2-id'>value2</label>",
                "<input id='field-3-id' type='checkbox' name='field[]' value='k3'> <label for='field-3-id'>value3</label>",
            "</div>"
        ]);
        $this->assertEquals($field->render(), $content);

        $field->hint = 'My hint';
        $field->label = 'My label';
        $field->error = 'My error';
        $field->assoc = false;
        $field->values = ['yes', 'no'];
        $content = join([
            "<label for='field-id'>My label</label>",
            "<div class='values-block'>",
                "<input id='field-1-id' type='checkbox' name='field[]' value='yes'> <label for='field-1-id'>yes</label>",
                "<input id='field-2-id' type='checkbox' name='field[]' value='no'> <label for='field-2-id'>no</label>",
            "</div>",
            "<div class='error-block'>My error</div>",
            "<div class='hint-block'>My hint</div>",
        ]);
        $this->assertEquals($field->render(), $content);
    }

    public function testRadioList()
    {
        $field = new RadioListFormField('field', [
            'assoc' => false,
            'value' => 'no',
            'values' => ['yes', 'no'],
        ]);

        $content = join([
            "<div class='values-block'>",
            "<input id='field-1-id' type='radio' name='field' value='yes'> <label for='field-1-id'>yes</label>",
            "<input id='field-2-id' type='radio' name='field' value='no' checked> <label for='field-2-id'>no</label>",
            "</div>",
        ]);
        $this->assertEquals($field->render(), $content);

        $field->assoc = true;
        $field->value = 'k2';
        $field->values = [
            'k1' => 'value1',
            'k2' => 'value2',
            'k3' => 'value3',
        ];
        $content = join([
            "<div class='values-block'>",
            "<input id='field-1-id' type='radio' name='field' value='k1'> <label for='field-1-id'>value1</label>",
            "<input id='field-2-id' type='radio' name='field' value='k2' checked> <label for='field-2-id'>value2</label>",
            "<input id='field-3-id' type='radio' name='field' value='k3'> <label for='field-3-id'>value3</label>",
            "</div>",
        ]);
        $this->assertEquals($field->render(), $content);

        $field->hint = 'My hint';
        $field->label = 'My label';
        $field->error = 'My error';
        $field->assoc = false;
        $field->values = ['yes', 'no'];
        $content = join([
            "<label for='field-id'>My label</label>",
            "<div class='values-block'>",
                "<input id='field-1-id' type='radio' name='field' value='yes'> <label for='field-1-id'>yes</label>",
                "<input id='field-2-id' type='radio' name='field' value='no'> <label for='field-2-id'>no</label>",
            "</div>",
            "<div class='error-block'>My error</div>",
            "<div class='hint-block'>My hint</div>",
        ]);
        $this->assertEquals($field->render(), $content);
    }

    public function testCallback()
    {
        $field = new CallbackFormField('field', [
            'callback' => function($value) {
                return "slkfnskjfhkrgekrg230tgoi34nkdf";
            },
        ]);

        $content = "slkfnskjfhkrgekrg230tgoi34nkdf";
        $this->assertEquals($field->render(), $content);
    }

    public function testWidget()
    {
        $field = new WidgetFormField('field', [
            'value' => '456',
            'class' => MyWidget::class,
        ]);

        $content = "
name: field;
value: 456;
";
        $this->assertEquals($field->render(), $content);
    }
}
