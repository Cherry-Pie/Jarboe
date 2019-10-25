<?php

namespace Yaro\Jarboe\Table\Fields\Traits;

trait Hidden
{
    protected $hidden = [
        'list'   => false,
        'edit'   => false,
        'create' => false,
    ];

    public function hide(bool $edit = false, bool $create = false, bool $list = false)
    {
        $this->hidden = [
            'list'   => $list,
            'edit'   => $edit,
            'create' => $create,
        ];

        return $this;
    }

    public function hideList(bool $hide = false)
    {
        $this->hidden['list'] = $hide;

        return $this;
    }

    public function hideEdit(bool $hide = false)
    {
        $this->hidden['edit'] = $hide;

        return $this;
    }

    public function hideCreate(bool $hide = false)
    {
        $this->hidden['create'] = $hide;

        return $this;
    }

    public function hidden(string $type)
    {
        if (!array_key_exists($type, $this->hidden)) {
            throw new \RuntimeException(sprintf("Wrong type [%s] for field's hidden attribute", $type));
        }

        return $this->hidden[$type];
    }
}
