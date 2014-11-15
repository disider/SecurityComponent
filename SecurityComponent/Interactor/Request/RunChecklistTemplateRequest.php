<?php
/**
 * Created by PhpStorm.
 * User: neilarmstrong
 * Date: 11/09/14
 * Time: 17:22
 */

namespace SecurityComponent\Interactor\Request;

use SecurityComponent\Interactor\Request;

class RunChecklistTemplateRequest implements Request
{
    /** @var int */
    public $id;

    /** @var int */
    public $userId;

    public function __construct($ownerId, $id)
    {
        $this->userId = $ownerId;
        $this->id = $id;
    }
}
