<?php

namespace Yaro\Jarboe\Table\Toolbar\Interfaces;

use Illuminate\Http\Request;
use Yaro\Jarboe\Table\CRUD;

interface ToolInterface
{
    const POSITION_HEADER = 'header';
    const POSITION_BODY_BOTH = 'body_both';
    const POSITION_BODY_TOP = 'body_top';
    const POSITION_BODY_BOTTOM = 'body_bottom';

    /**
     * Position where should tool be placed.
     */
    public function position();

    /**
     * Unique tool identifier.
     */
    public function identifier(): string;

    /**
     * Tool's view.
     */
    public function render();

    /**
     * Handle tool execution.
     * @param Request $request
     * @return mixed
     */
    public function handle(Request $request);

    /**
     * Check allowance to show and process tool.
     */
    public function check(): bool;

    /**
     * Set CRUD object.
     *
     * @param CRUD $crud
     */
    public function setCrud(CRUD $crud);

    /**
     * Get url for tool handling.
     */
    public function getUrl(): string;
}
