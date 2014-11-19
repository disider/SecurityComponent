<?php

namespace Diside\SecurityComponent\Interactor\Request;

use Diside\SecurityComponent\Interactor\Request;

class GetCompanyRequest implements Request
{
    /** @var int */
    public $id;

    public function __construct($id)
    {
        $this->id = $id;
    }
} 