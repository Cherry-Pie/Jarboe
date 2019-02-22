<?php

namespace Yaro\Jarboe\Http\Controllers;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Request as RequestFacade;
use Yaro\Jarboe\Exceptions\PermissionDenied;
use Yaro\Jarboe\Table\CRUD;
use Yaro\Jarboe\Table\Fields\AbstractField;
use Yaro\Jarboe\Table\Fields\Select;
use Yaro\Jarboe\Table\Toolbar\Interfaces\ToolInterface;
use Yaro\Jarboe\Table\Toolbar\TranslationLocalesSelectorTool;

abstract class AbstractTableController
{
    use ValidatesRequests;

    protected $viewCrudList = 'jarboe::crud.list';
    protected $viewCrudCreate = 'jarboe::crud.create';
    protected $viewCrudEdit = 'jarboe::crud.edit';

    /**
     * @var CRUD
     */
    protected $crud;

    public function __construct()
    {
        $this->crud = new CRUD();
        $this->crud->tableIdentifier(crc32(static::class));
        $this->crud->formClass(config('jarboe.crud.form_class'));
    }

    /**
     * Handle search action.
     */
    public function search(Request $request)
    {
        $this->init();
        $this->bound();

        $this->crud->saveSearchFilterParams($request->get('search', []));

        return redirect($this->crud->listUrl());
    }

    /**
     * Handle relation search action.
     * Currently used for SelectField with type `select2` and `ajax = true`.
     *
     * @param string $field
     * @param string $page
     * @param string $term
     */
    public function searchRelation(Request $request)
    {
        $this->init();
        $this->bound();

        // TODO:
        $perPage = 10;

        $query = $request->get('term');
        $fieldName = $request->get('field');
        $page = (int) $request->get('page');

        /** @var Select $field */
        $field = $this->crud->getFieldByName($fieldName);
        if (!$field) {
            throw new \RuntimeException(sprintf('Field [%s] not setted to crud', $fieldName));
        }

        $total = 0;
        $results = [];
        if ($field->isGroupedRelation()) {
            foreach ($field->getRelations() as $index => $group) {
                $options = $field->getOptions($page, $perPage, $query, $total, $index);
                array_walk($options, function(&$item, $key) use($group) {
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
            array_walk($results, function(&$item, $key) {
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

    /**
     * Save direction by column.
     *
     * @param $column
     * @param $direction
     */
    public function orderBy($column, $direction)
    {
        $this->crud->saveOrderFilterParam($column, $direction);

        return redirect($this->crud->listUrl());
    }

    /**
     * Handle store action.
     */
    public function handleStore(Request $request)
    {
        $this->init();
        $this->bound();

        if (!$this->action('create')->isAllowed()) {
            throw new PermissionDenied();
        }

        $this->crud->repo()->store($request);

        return redirect($this->crud->listUrl());
    }

    /**
     * Handle inline update action.
     */
    public function inline(Request $request)
    {
        $this->init();
        $this->bound();

        $id = $request->get('_pk');
        $value = $request->get('_value');
        $field = $this->crud->getFieldByName($request->get('_name'));
        $locale = $request->get('_locale');

        if (!$field->isInline() && !$this->action('edit')->isAllowed()) {
            throw new PermissionDenied();
        }

        if (method_exists($this, 'update')) {
            $reflection = new \ReflectionClass(get_class($this));
            $parameters = $reflection->getMethod('update')->getParameters();
            $firstParam = $parameters[0] ?? null;
            if ($firstParam && $firstParam->getClass()) {
                $formRequestClass = $firstParam->getClass()->getName();
                /** @var FormRequest $formRequest */
                $formRequest = new $formRequestClass();
                if (method_exists($formRequest, 'rules')) {
                    foreach ($formRequest->rules() as $param => $rules) {
                        if (preg_match('~^'. preg_quote($field->name()) .'(\.\*)?$~', $param)) {
                            $this->validate(
                                $request,
                                [$field->name() => $rules],
                                $formRequest->messages(),
                                $formRequest->attributes()
                            );
                            break;
                        }
                    }
                }
            }
        }

        // change app locale, so translatable model's column will be set properly
        if ($locale) {
            app()->setLocale($locale);
        }

        $model = $this->crud->repo()->updateField($id, $request, $field, $value);

        return response()->json([
            'value' => $model->{$field->name()},
        ]);
    }

    /**
     * Handle update action.
     */
    public function handleUpdate(Request $request, $id)
    {
        $this->init();
        $this->bound();

        if (!$this->action('edit')->isAllowed()) {
            throw new PermissionDenied();
        }

        $this->crud->repo()->update($id, $request);

        return redirect($this->crud->listUrl());
    }

    /**
     * Handle delete action.
     */
    public function handleDelete(Request $request, $id)
    {
        $this->init();
        $this->bound();

        if (!$this->action('delete')->isAllowed()) {
            throw new PermissionDenied();
        }

        if ($this->crud->repo()->delete($id)) {
            return response()->json([
                'message' => __('jarboe::common.list.delete_success_message', ['id' => $id]),
            ]);
        }

        return response()->json([
            'message' => __('jarboe::common.list.delete_failed_message', ['id' => $id]),
        ], 422);
    }

    /**
     * Handle setting per page param.
     */
    public function perPage($perPage)
    {
        $this->init();
        $this->bound();

        $this->crud->setPerPageParam((int) $perPage);

        return redirect($this->crud->listUrl());
    }

    /**
     * Show table list page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function list()
    {
        $this->init();
        $this->bound();

        return view($this->viewCrudList, [
            'crud' => $this->crud,
            'items' => $this->crud->repo()->get(),
        ]);
    }

    /**
     * Show edit form page.
     *
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $this->init();
        $this->bound();

        if (!$this->action('edit')->isAllowed()) {
            throw new PermissionDenied();
        }

        return view($this->viewCrudEdit, [
            'crud' => $this->crud,
            'item' => $this->crud->repo()->find($id),
        ]);
    }

    /**
     * Show create form page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $this->init();
        $this->bound();

        if (!$this->action('create')->isAllowed()) {
            throw new PermissionDenied();
        }

        return view($this->viewCrudCreate, [
            'crud' => $this->crud,
        ]);
    }

    /**
     * Handle toolbar's tool request.
     *
     * @param Request $request
     * @param $identifier
     * @return mixed
     * @throws PermissionDenied
     */
    public function toolbar(Request $request, $identifier)
    {
        $this->init();
        $this->bound();

        /** @var ToolInterface $tool */
        $tool = $this->crud->getTool($identifier);
        if (!$tool->check()) {
            throw new PermissionDenied();
        }

        return $tool->handle($request);
    }


    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */
    protected function addTools(array $tools)
    {
        foreach ($tools as $tool) {
            $this->addTool($tool);
        }
    }

    protected function addTool(ToolInterface $tool)
    {
        $tool->setCrud($this->crud);
        $this->crud->addTool($tool);
    }

    /**
     * @param string|AbstractField $column
     */
    protected function addColumn($column)
    {
        $this->crud->addColumn($column);
    }

    protected function addColumns(array $columns)
    {
        foreach ($columns as $column) {
            $this->addColumn($column);
        }
    }

    protected function addField(AbstractField $field)
    {
        $this->crud->addField($field);
    }

    protected function addFields(array $fields)
    {
        foreach ($fields as $field) {
            $this->addField($field);
        }
    }

    protected function addTab($title, array $fields)
    {
        foreach ($fields as $field) {
            $field->tab($title);
            $this->addField($field);
        }
    }

    protected function setModel($model)
    {
        $this->crud->setModel($model);
    }

    protected function paginate($perPage)
    {
        $this->crud->paginate($perPage);
    }

    protected function order(string $column, string $direction = 'asc')
    {
        $this->crud->order($column, $direction);
    }

    protected function filter(\Closure $callback)
    {
        $this->crud->filter($callback);
    }

    protected function removeAction($ident)
    {
        $this->crud->removeAction($ident);
    }

    protected function action($ident)
    {
        return $this->crud->action($ident);
    }

    /**
     * Add locales for all translatable fields.
     * @param array $locales
     */
    protected function locales(array $locales)
    {
        $this->crud->locales($locales);
    }

    public function __call($name, $arguments)
    {
        $request = RequestFacade::instance();

        switch ($name) {
            case 'update':
                return $this->handleUpdate($request, $arguments[0]);
            case 'store':
                return $this->handleStore($request);
            case 'delete':
                return $this->handleDelete($request, $arguments[0]);

            default:
                throw new \RuntimeException('Invalid method '. $name);
        }
    }

    /**
     * Bound fields/tools/etc with global data.
     */
    public function bound()
    {
        /** @var AbstractField $field */
        foreach ($this->crud->getFields() as $field) {
            $field->prepare($this->crud);
        }

        /** @var AbstractField $field */
        foreach ($this->crud->getFieldsWithoutMarkup() as $field) {
            if ($field->isTranslatable()) {
                $this->addTool(new TranslationLocalesSelectorTool());
                break;
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | Abstract
    |--------------------------------------------------------------------------
    */

    abstract protected function init();
}
