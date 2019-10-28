<?php

namespace Yaro\Jarboe\Table\Repositories;

use Illuminate\Http\Request;
use Yaro\Jarboe\Table\CRUD;
use Yaro\Jarboe\Table\Fields\AbstractField;

interface ModelRepositoryInterface
{
    public function setCrud(CRUD $crud);
    public function get();
    public function find($id);
    public function delete($id);
    public function store(Request $request);
    public function update($id, Request $request);
    public function updateField($id, Request $request, AbstractField $field, $value);
    public function reorder($id, $idPrev, $idNext);
    public function restore($id);
    public function forceDelete($id);
    public function filter(\Closure $callback);
    public function order(string $column, string $direction);
    public function perPage(int $perPage = null);
}
