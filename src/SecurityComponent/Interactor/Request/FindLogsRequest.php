<?php

namespace SecurityComponent\Interactor\Request;


use SecurityComponent\Interactor\Request;

class FindLogsRequest implements Request
{

    /** @var int The user making the request*/
    public $userId;

    /** @var int */
    public $pageIndex;

    /** @var int */
    public $pageSize;

    /** @var array */
    public $filters;

    public function __construct($userId, $pageIndex = 0, $pageSize = PHP_INT_MAX, array $filters = array())
    {
        $this->userId = $userId;
        $this->pageIndex = $pageIndex;
        $this->pageSize = $pageSize;
        $this->filters = $filters;
    }

} 