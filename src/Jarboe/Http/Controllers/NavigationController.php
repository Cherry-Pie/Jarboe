<?php

namespace Yaro\Jarboe\Http\Controllers;


use Illuminate\Routing\Controller;
use Yaro\Jarboe\Http\Requests\Navigation\CreateNodeRequest;
use Yaro\Jarboe\Http\Requests\Navigation\DeleteNodeRequest;
use Yaro\Jarboe\Http\Requests\Navigation\MoveNodeRequest;
use Yaro\Jarboe\Http\Requests\Navigation\PatchNodeRequest;
use Yaro\Jarboe\Http\Requests\Navigation\UpdateNodeRequest;
use Yaro\Jarboe\Models\Navigation;

class NavigationController extends Controller
{

    public function show()
    {
        $root = Navigation::root();

        return view('jarboe::navigation.navigation', compact('root'));
    }

    public function createNode(CreateNodeRequest $request)
    {
        $root = Navigation::root();

        Navigation::create([
            'name' => $request->get('name'),
            'slug' => $request->get('slug'),
            'icon' => (string) $request->get('icon'),
            'is_active' => (bool) $request->get('is_active'),
        ])->makeChildOf($root);

        Navigation::rebuild();

        return redirect()->back();
    }

    public function updateNode(UpdateNodeRequest $request)
    {
        Navigation::find($request->get('id'))->update([
            'name' => $request->get('name'),
            'slug' => $request->get('slug'),
            'icon' => (string) $request->get('icon'),
            'is_active' => (bool) $request->get('is_active'),
        ]);

        return redirect()->back();
    }

    public function patchNode(PatchNodeRequest $request)
    {
        $data = [];
        if ($request->has('is_active')) {
            // cuz its value sends as string
            $data['is_active'] = $request->get('is_active') == 'true';
        }

        Navigation::find($request->get('id'))->update($data);

        return response()->json([
            'ok' => true,
        ]);
    }

    public function deleteNode(DeleteNodeRequest $request)
    {
        Navigation::destroy($request->get('id'));

        Navigation::rebuild();

        return redirect()->back();
    }

    public function moveNode(MoveNodeRequest $request)
    {
        $node = Navigation::find($request->get('id'));

        if ($request->get('left_id')) {
            $left = Navigation::find($request->get('left_id'));
            $node->moveToRightOf($left);
        } elseif ($request->get('right_id')) {
            $right = Navigation::find($request->get('right_id'));
            $node->moveToLeftOf($right);
        } else {
            $idRoot = $request->get('root_id');
            $root = $idRoot ? Navigation::find($idRoot) : Navigation::root();

            $node->makeLastChildOf($root);
        }

        Navigation::rebuild();

        return response()->json([
            'ok' => true,
        ]);
    }

}