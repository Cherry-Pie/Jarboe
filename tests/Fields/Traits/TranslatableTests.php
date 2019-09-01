<?php

namespace Yaro\Jarboe\Tests\Fields\Traits;

use Yaro\Jarboe\Table\Fields\AbstractField;

trait TranslatableTests
{
    /**
     * @test
     */
    public function default_translatable()
    {
        $field = $this->field();

        $this->assertFalse($field->isTranslatable());
    }

    /**
     * @test
     */
    public function enabled_translatable()
    {
        $field = $this->field()->translatable();

        $this->assertTrue($field->isTranslatable());
    }

    /**
     * @test
     */
    public function set_and_get_translatable_locales()
    {
        $locales = [
            'en' => 'eng',
            'de' => 'deu',
        ];
        $field = $this->field()->locales($locales);

        $this->assertEquals($locales, $field->getLocales());
    }

    /**
     * @test
     */
    public function check_translatable_current_locale()
    {
        $field = $this->field();
        $field->setCurrentLocale('en');

        $this->assertEquals('en', $field->getCurrentLocale());
        $this->assertTrue($field->isCurrentLocale('en'));
        $this->assertFalse($field->isCurrentLocale('de'));
    }

    abstract protected function field(): AbstractField;
}
