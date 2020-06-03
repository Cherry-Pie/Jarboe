<?php

namespace Yaro\Jarboe\Http\Controllers\Traits\Handlers;

use Illuminate\Http\Request;
use Yaro\Jarboe\Table\CRUD;
use Yaro\Jarboe\Table\Fields\Select;

trait SearchRelationHandlerTrait
{
    /**
     * Handle relation search action.
     * Currently used for SelectField with type `select2` and `ajax = true`.
     *
     * @param string $field
     * @param string $page
     * @param string $term
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchRelation(Request $request)
    {
        $this->beforeInit();
        $this->init();
        $this->bound();

        // TODO:
        $perPage = 10;

        $query = $request->get('term');
        $fieldName = $request->get('field');
        $page = (int) $request->get('page');

        /** @var Select $field */
        $field = $this->crud()->getFieldByName($fieldName);
        if (!$field) {
            throw new \RuntimeException(sprintf('Field [%s] not setted to crud', $fieldName));
        }

        $total = 0;
        $results = [];
        if ($field->isGroupedRelation()) {
            foreach ($field->getRelations() as $index => $group) {
                $options = $field->getOptions($page, $perPage, $query, $total, $index);
                array_walk($options, function (&$item, $key) use ($group) {
                    $item = [
                        'id'   => crc32($group['group']) .'~~~'. $key,
                        'text' => $item,
                    ];
                });
                if ($options) {
                    $results[] = [
                        'text'     => $group['group'],
                        'children' => array_values($options),
                    ];
                }
            }
        } else {
            $results = $field->getOptions($page, $perPage, $query, $total);
            array_walk($results, function (&$item, $key) {
                $item = [
                    'id'   => $key,
                    'text' => $item,
                ];
            });
        }

        return response()->json([
            'results' => array_values($results),
            'pagination' => [
                'more' => $total > $page * $perPage,
            ]
        ]);
    }

    abstract protected function beforeInit();
    abstract protected function init();
    abstract protected function bound();
    abstract protected function crud(): CRUD;
    abstract protected function can($action): bool;
}
