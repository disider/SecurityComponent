<?php

namespace SecurityComponent\Interactor\Request;

use SecurityComponent\Interactor\Request;

class SaveShareRequestsRequest implements Request
{
    /** @var int */
    public $userId;

    /** @var int */
    public $checklistTemplateId;

    /** @var array */
    public $emails;

    public function __construct($userId, $checklistTemplateId, array $emails)
    {
        $this->userId = $userId;
        $this->checklistTemplateId = $checklistTemplateId;
        $this->emails = $emails;
    }

}