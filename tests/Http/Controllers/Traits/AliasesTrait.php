<?php

namespace Yaro\Jarboe\Tests\Http\Controllers\Traits;

trait AliasesTrait
{
    /**
     * @test
     */
    public function check_locales_alias()
    {
        $locales = [
            'en' => 'EN',
            'JP' => 'JP',
        ];
        $this->controller->locales($locales);

        $this->assertEquals($locales, $this->controller->getCrud()->getLocales());


        $locales = [
            'en',
            'JP',
        ];
        $this->controller->locales($locales);

        $this->assertEquals(array_combine($locales, $locales), $this->controller->getCrud()->getLocales());
    }
}
