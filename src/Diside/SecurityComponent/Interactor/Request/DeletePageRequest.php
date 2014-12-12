<?php

namespace Diside\SecurityComponent\Interactor\Request;

use Diside\SecurityComponent\Interactor\Request;

class DeletePageRequest implements Request
{
    /** @var int */
    public $executorId;

    /** @var int */
    public $id;

    public function __construct($executorId, $id)
    {
        $this->executorId = $executorId;
        $this->id = $id;
    }
} 