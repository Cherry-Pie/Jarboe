<?php

namespace Yaro\Jarboe\Tests\Fields;

use Yaro\Jarboe\Table\Fields\AbstractField;
use Yaro\Jarboe\Table\Fields\Password;
use Yaro\Jarboe\Tests\Fields\Traits\NullableTests;
use Yaro\Jarboe\Tests\Fields\Traits\OrderableTests;
use Yaro\Jarboe\Tests\Fields\Traits\PlaceholderTests;
use Yaro\Jarboe\Tests\Fields\Traits\TooltipTests;

class PasswordFieldTest extends AbstractFieldTest
{
    use OrderableTests;
    use NullableTests;
    use TooltipTests;
    use PlaceholderTests;

    protected function getFieldWithName(): AbstractField
    {
        return Password::make(self::NAME);
    }

    protected function getFieldWithNameAndTitle(): AbstractField
    {
        return Password::make(self::NAME, self::TITLE);
    }

    /**
     * @test
     */
    public function default_password_hash()
    {
        $field = $this->field();

        $this->assertEquals(
            bcrypt('password'),
            $field->value($this->createRequest([
                self::NAME => 'password',
            ]))
        );
    }

    /**
     * @test
     */
    public function custom_password_hash_function()
    {
        $field = $this->field()->hash('md5');

        $this->assertEquals(
            md5('password'),
            $field->value($this->createRequest([
                self::NAME => 'password',
            ]))
        );
    }

    /**
     * @test
     */
    public function custom_password_hash_lambda()
    {
        $field = $this->field()->hash(function () {
            return 111;
        });

        $this->assertEquals(
            111,
            $field->value($this->createRequest([
                self::NAME => 'password',
            ]))
        );
    }

    /**
     * @test
     */
    public function password_hash_for_non_existing_function()
    {
        $this->expectException(\RuntimeException::class);
        $this->field()->hash('non_existing_function');
    }

    /**
     * @test
     */
    public function password_should_skip_without_value()
    {
        $field = $this->field();

        $this->assertTrue($field->shouldSkip(
            $this->createRequest([
                self::NAME => '',
            ]))
        );
    }

    /**
     * @test
     */
    public function password_should_not_skip_with_value()
    {
        $field = $this->field();

        $this->assertFalse($field->shouldSkip(
            $this->createRequest([
                self::NAME => 'value',
            ]))
        );
    }
}
