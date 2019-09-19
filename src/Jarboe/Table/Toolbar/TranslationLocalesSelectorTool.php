<?php

namespace Yaro\Jarboe\Table\Toolbar;

use Illuminate\Http\Request;

class TranslationLocalesSelectorTool extends AbstractTool
{
    /**
     * Position where should tool be placed.
     */
    public function position()
    {
        return self::POSITION_HEADER;
    }

    /**
     * Unique tool identifier.
     */
    public function identifier(): string
    {
        return 'translation_locales_selector';
    }

    /**
     * Tool's view.
     */
    public function render()
    {
        return view('jarboe::crud.toolbar.translation_locales_selector', [
            'tool' => $this,
            'locales' => $this->crud()->getLocales(),
        ])->render();
    }

    /**
     * Handle tool execution.
     * @param Request $request
     */
    public function handle(Request $request)
    {
        $this->crud()->saveCurrentLocale($request->get('locale'));

        return response()->json([
            'ok' => true,
        ]);
    }

    /**
     * Check allowance to show and process tool.
     */
    public function check(): bool
    {
        return true;
    }

    public function isCurrentLocale($locale)
    {
        return $this->crud()->getCurrentLocale() == $locale;
    }
}
