<?php

namespace SecurityComponent\Interactor\Request;

use SecurityComponent\Interactor\Request;

class GetCompanyRequest implements Request
{
    /** @var int */
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }
} 