<?php

namespace Yaro\Jarboe\Table\Toolbar;

use Illuminate\Http\Request;

class ShowHideColumnsTool extends AbstractTool
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
        return 'show_hide_columns';
    }

    /**
     * Tool's view.
     */
    public function render()
    {
        return view('jarboe::crud.toolbar.show_hide_columns', [
            'tool' => $this,
        ]);
    }

    /**
     * Handle tool execution.
     * @param Request $request
     */
    public function handle(Request $request)
    {
        // dummy
    }

    /**
     * Check allowance to show and process tool.
     */
    public function check(): bool
    {
        return true;
    }
}
