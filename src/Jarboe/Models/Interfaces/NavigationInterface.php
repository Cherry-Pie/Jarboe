<?php

namespace Yaro\Jarboe\Models\Interfaces;


interface NavigationInterface
{
    public static function rebuild();
    public function moveToRightOf($node);
    public function moveToLeftOf($node);
    public function makeLastChildOf($node);
    public function getImmediateDescendants($columns = ['*']);
    public function isLeaf();
}