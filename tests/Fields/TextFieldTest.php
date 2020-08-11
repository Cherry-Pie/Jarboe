<?php

namespace Yaro\Jarboe\Tests\Fields;

use Yaro\Jarboe\Table\Fields\AbstractField;
use Yaro\Jarboe\Table\Fields\Text;
use Yaro\Jarboe\Tests\Fields\Traits\ClipboardTests;
use Yaro\Jarboe\Tests\Fields\Traits\InlineTests;
use Yaro\Jarboe\Tests\Fields\Traits\MaskableTests;
use Yaro\Jarboe\Tests\Fields\Traits\MaxlengthTests;
use Yaro\Jarboe\Tests\Fields\Traits\NullableTests;
use Yaro\Jarboe\Tests\Fields\Traits\OrderableTests;
use Yaro\Jarboe\Tests\Fields\Traits\PlaceholderTests;
use Yaro\Jarboe\Tests\Fields\Traits\TooltipTests;
use Yaro\Jarboe\Tests\Fields\Traits\TranslatableTests;
use Yaro\Jarboe\Tests\Models\Model;

class TextFieldTest extends AbstractFieldTest
{
    use OrderableTests;
    use NullableTests;
    use TooltipTests;
    use ClipboardTests;
    use InlineTests;
    use TranslatableTests;
    use MaskableTests;
    use PlaceholderTests;
    use MaxlengthTests;

    protected function getFieldWithName(): AbstractField
    {
        return Text::make(self::NAME);
    }

    protected function getFieldWithNameAndTitle(): AbstractField
    {
        return Text::make(self::NAME, self::TITLE);
    }

    /**
     * @test
     */
    public function text_check_value()
    {
        $field = $this->field();

        $this->assertEquals('0', $field->value($this->createRequest([
            self::NAME => 0,
        ])));
        $this->assertIsString($field->value($this->createRequest([
            self::NAME => 0,
        ])));
        $this->assertEquals('10', $field->value($this->createRequest([
            self::NAME => 10,
        ])));
        $this->assertIsString($field->value($this->createRequest([
            self::NAME => 10,
        ])));
        $this->assertEquals('', $field->value($this->createRequest()));
        $this->assertIsString($field->value($this->createRequest()));

        $this->assertEquals(
            [
                'en' => 'value'
            ],
            $field->value($this->createRequest([
                self::NAME => [
                    'en' => 'value'
                ],
            ]))
        );
        $this->assertIsArray($field->value($this->createRequest([
            self::NAME => [
                'en' => 'value'
            ],
        ])));


        $field->nullable();

        $this->assertEquals('10', $field->value($this->createRequest([
            self::NAME => '10',
        ])));
        $this->assertEquals('10', $field->value($this->createRequest([
            self::NAME => 10,
        ])));

        $this->assertNull($field->value($this->createRequest([
            self::NAME => '0',
        ])));
        $this->assertNull($field->value($this->createRequest([
            self::NAME => 0,
        ])));
        $this->assertNull($field->value($this->createRequest()));
    }

    /**
     * @test
     */
    public function text_check_arrayable_value()
    {
        $name = 'meta[title]';
        $field = Text::make($name);

        $this->assertEquals('0', $field->value($this->createRequest([
            $name => 0,
        ])));
        $this->assertIsString($field->value($this->createRequest([
            $name => 0,
        ])));
        $this->assertEquals('10', $field->value($this->createRequest([
            $name => 10,
        ])));
        $this->assertIsString($field->value($this->createRequest([
            $name => 10,
        ])));
        $this->assertEquals('', $field->value($this->createRequest()));
        $this->assertIsString($field->value($this->createRequest()));


        $field->nullable();

        $this->assertEquals('10', $field->value($this->createRequest([
            $name => '10',
        ])));
        $this->assertEquals('10', $field->value($this->createRequest([
            $name => 10,
        ])));

        $this->assertNull($field->value($this->createRequest([
            $name => '0',
        ])));
        $this->assertNull($field->value($this->createRequest([
            $name => 0,
        ])));
        $this->assertNull($field->value($this->createRequest()));
    }

    /**
     * @test
     */
    public function check_dot_pattern_name()
    {
        $field = Text::make('meta[title]');

        $this->assertTrue($field->belongsToArray());
        $this->assertEquals('meta', $field->getAncestorName());
        $this->assertEquals('title', $field->getDescendantName());
        $this->assertEquals('meta.title', $field->getDotPatternName());


        $this->assertFalse($this->field()->belongsToArray());
    }

    /**
     * @test
     */
    public function check_get_attribute()
    {
        $field = Text::make('meta[title]');
        $model = Model::first();
        $model->setAttribute('meta', [
            'title' => 'test me',
            'description' => 'sasa lele',
        ]);

        $this->assertEquals('test me', $field->getAttribute($model));
    }

    /**
     * @test
     */
    public function check_old_or_get_attribute_without_old()
    {
        $field = Text::make('meta[title]');
        $model = Model::first();
        $model->setAttribute('meta', [
            'title' => 'test me',
            'description' => 'sasa lele',
        ]);

        $this->assertSame('test me', $field->oldOrAttribute($model));
    }

    /**
     * @test
     */
    public function check_old_or_get_attribute_with_old()
    {
        $field = Text::make('meta[title]');
        $model = Model::first();
        $model->setAttribute('meta', [
            'title' => 'test me',
            'description' => 'sasa lele',
        ]);

        $this->setOld('oldvalue', 'meta.title');
        $this->assertSame('oldvalue', $field->oldOrAttribute($model));
    }

    /**
     * @test
     */
    public function check_has_old()
    {
        $field = Text::make('meta[title]');

        $this->setOld('oldvalue', 'meta.title');
        $this->assertTrue($field->hasOld());
    }

    /**
     * @test
     */
    public function check_has_no_old()
    {
        $field = Text::make('meta[title]');

        $this->assertFalse($field->hasOld());
    }

    /**
     * @test
     */
    public function check_old()
    {
        $field = Text::make('meta[title]');
        $this->assertSame(old($field->getDotPatternName()), $field->old());

        $this->setOld('sasa lele', 'meta.title');
        $this->assertSame(old($field->getDotPatternName()), $field->old());
    }

    /**
     * @test
     */
    public function check_old_or_default()
    {
        $field = Text::make('meta[title]');

        // without old, without default
        $this->assertNull($field->oldOrDefault());

        // with old, without default
        $this->setOld('sasa lele', 'meta.title');
        $this->assertSame('sasa lele', $field->oldOrDefault());

        // with old, with default
        $this->setOld('sasa lele', 'meta.title');
        $field->default('default-value');
        $this->assertSame('sasa lele', $field->oldOrDefault());

        // without old, with default
        $this->flushOld();
        $field->default('default-value');
        $this->assertSame($field->getDefault(), $field->oldOrDefault());
    }
}
