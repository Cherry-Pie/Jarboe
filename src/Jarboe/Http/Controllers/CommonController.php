<?php

namespace Yaro\Jarboe\Http\Controllers;

use Illuminate\Routing\Controller;
use Yaro\Jarboe\Helpers\System;
use Yaro\Jarboe\Table\CRUD;

class CommonController extends Controller
{
    public function resetPanelSettings()
    {
        setcookie('body_class', null, -1, '/');

        /** @var CRUD $crud */
        $crud = app(CRUD::class);
        $crud->preferences()->resetAll();

        return redirect()->back();
    }

    public function refreshSystemValues()
    {
        $system = new System();

        $swapExplanation = 'NA';
        if (!is_null($system->swapTotal())) {
            $swapExplanation = $system->readableSize($system->swapUsed()) .' / '. $system->readableSize($system->swapTotal());
        }

        $memoryExplanation = 'NA';
        if (!is_null($system->memoryTotal())) {
            $memoryExplanation = $system->readableSize($system->memoryUsed()) .' / '. $system->readableSize($system->memoryTotal());
        }

        return response()->json([
            'swap' => [
                'explanation' => $swapExplanation,
                'percentage' => $system->swapPercentage(),
            ],
            'memory' => [
                'explanation' => $memoryExplanation,
                'percentage' => $system->memoryPercentage(),
            ],
            'load_average' => [
                'explanation' => implode(' ', $system->systemLoadSamples()),
                'percentages' => $system->systemLoadSamplesInPercentages(),
            ],
        ]);
    }

    public function notFoundPage()
    {
        return response()->view('jarboe::errors.404')->setStatusCode(404);
    }
}
