<?php

namespace SecurityComponent\Interactor\Request;

use SecurityComponent\Interactor\Request;

class SaveCompanyRequest implements Request
{
    /** @var int */
    public $id;

    /** @var string */
    public $name;

    public function __construct($id, $name)
    {
        $this->id = $id;
        $this->name = $name;
    }

}