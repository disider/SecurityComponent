<?php

namespace SecurityComponent\Interactor\Request;

use SecurityComponent\Interactor\Request;

class GetChecklistTemplateRequest implements Request
{
    public $userId;
    public $id;

    public function __construct($ownerId, $id)
    {
        $this->userId = $ownerId;
        $this->id = $id;
    }
} 