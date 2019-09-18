<?php

namespace Yaro\Jarboe\Table\Toolbar;

use Illuminate\Http\Request;
use Yaro\Jarboe\Table\CRUD;

class MassDeleteTool extends AbstractTool
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
        return 'mass-delete';
    }

    /**
     * Tool's view.
     */
    public function render()
    {
        return view('jarboe::crud.toolbar.mass_delete', [
            'tool' => $this,
            'crud' => $this->crud(),
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
        $hidden = [];
        $removed = [];
        foreach ($request->get('ids') as $id) {
            try {
                if (!$this->crud()->repo()->delete($id)) {
                    throw new \Exception(__('jarboe::toolbar.mass_delete.delete_event_failed'));
                }

                try {
                    $this->crud()->repo()->find($id);
                    $hidden[] = $id;
                } catch (\Exception $e) {
                    $removed[] = $id;
                }
            } catch (\Exception $e) {
                $errors[$id] = $e->getMessage();
            }
        }

        return response()->json([
            'errors' => $errors,
            'hidden' => $hidden,
            'removed' => $removed,
        ]);
    }

    /**
     * Check allowance to show and process tool.
     */
    public function check(): bool
    {
        return true;
    }
}
