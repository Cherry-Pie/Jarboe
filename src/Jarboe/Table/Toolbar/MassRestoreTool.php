<?php

namespace Yaro\Jarboe\Table\Toolbar;

use Illuminate\Http\Request;
use Yaro\Jarboe\Table\CRUD;

class MassRestoreTool extends AbstractTool
{
    /**
     * Set CRUD object.
     *
     * @param CRUD $crud
     */
    public function setCrud(CRUD $crud)
    {
        parent::setCrud($crud);

        if ($this->check()) {
            $this->crud()->enableBatchCheckboxes(true);
        }
    }

    /**
     * Position where should tool be placed.
     */
    public function position()
    {
        return self::POSITION_BODY_TOP;
    }

    /**
     * Unique tool identifier.
     */
    public function identifier(): string
    {
        return 'mass-restore';
    }

    /**
     * Tool's view.
     */
    public function render()
    {
        return view('jarboe::crud.toolbar.mass_restore', [
            'tool' => $this,
        ]);
    }

    /**
     * Handle tool execution.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request)
    {
        $errors = [];
        $restored = [];
        foreach ($request->get('ids') as $id) {
            try {
                if (!$this->crud()->repo()->restore($id)) {
                    throw new \Exception(__('jarboe::toolbar.mass_restore.restore_event_failed'));
                }
                $restored[] = $id;
            } catch (\Exception $e) {
                $errors[$id] = $e->getMessage();
            }
        }

        return response()->json([
            'errors' => $errors,
            'restored' => $restored,
        ]);
    }

    /**
     * Check allowance to show and process tool.
     */
    public function check(): bool
    {
        return $this->crud()->isSoftDeleteEnabled();
    }
}
