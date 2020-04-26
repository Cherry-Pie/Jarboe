<?php

namespace Yaro\Jarboe\Table\Fields\Traits;

use Closure;

trait Filename
{
    private $filenameClosure = null;

    /**
     * Set closure for changing filename upon upload.
     *
     * @param Closure $filenameClosure
     * @return $this
     */
    public function filename(Closure $filenameClosure)
    {
        $this->filenameClosure = $filenameClosure;

        return $this;
    }
}
