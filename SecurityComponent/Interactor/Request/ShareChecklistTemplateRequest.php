<?php
/**
 * Created by PhpStorm.
 * User: neilarmstrong
 * Date: 11/09/14
 * Time: 17:22
 */

namespace SecurityComponent\Interactor\Request;

use SecurityComponent\Interactor\Request;

class ShareChecklistTemplateRequest implements Request
{
    /** @var int */
    public $userId;

    /** @var string */
    public $token;

    public function __construct($userId, $token)
    {
        $this->userId = $userId;
        $this->token = $token;
    }
}
