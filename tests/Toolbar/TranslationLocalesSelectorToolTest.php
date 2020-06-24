<?php

namespace Yaro\Jarboe\Tests\Toolbar;

use Illuminate\Http\JsonResponse;
use Yaro\Jarboe\Table\Toolbar\AbstractTool;
use Yaro\Jarboe\Table\Toolbar\TranslationLocalesSelectorTool;

class TranslationLocalesSelectorToolTest extends AbstractToolTest
{
    protected function tool(): AbstractTool
    {
        $tool = new TranslationLocalesSelectorTool();
        $tool->setCrud($this->crud());

        return $tool;
    }

    /**
     * @test
     */
    public function test_position()
    {
        $this->assertSame(AbstractTool::POSITION_HEADER, $this->tool()->position());
    }

    /**
     * @test
     */
    public function test_identifier()
    {
        $this->assertSame('translation_locales_selector', $this->tool()->identifier());
    }

    /**
     * @test
     */
    public function test_check()
    {
        $this->assertTrue($this->tool()->check());
    }

    /**
     * @test
     */
    public function test_handle()
    {
        $locale = 'en';
        $tool = $this->tool();

        $this->assertNull($tool->crud()->getCurrentLocale());
        /** @var JsonResponse $response */
        $response = $tool->handle($this->createRequest([
            'locale' => $locale,
        ]));

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertSame('{"ok":true}', $response->content());

        $this->assertSame($locale, $tool->crud()->getCurrentLocale());

        $this->assertTrue($tool->isCurrentLocale($locale));
        $this->assertFalse($tool->isCurrentLocale('ulala'. $locale));
    }

    /**
     * @test
     */
    public function test_get_url()
    {
        $this->assertSame('http://localhost/~/toolbar/translation_locales_selector', $this->tool()->getUrl());
    }
}
