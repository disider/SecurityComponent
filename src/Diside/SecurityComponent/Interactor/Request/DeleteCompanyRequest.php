<?php

namespace Diside\SecurityComponent\Interactor\Request;

use Diside\SecurityComponent\Interactor\Request;

class DeleteCompanyRequest implements Request
{
    /** @var int */
    public $superadminId;

    /** @var int */
    public $id;

    public function __construct($superadminId, $id)
    {
        $this->superadminId = $superadminId;
        $this->id = $id;
    }
} 