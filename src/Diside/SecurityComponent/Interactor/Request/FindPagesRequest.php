<?php

namespace Diside\SecurityComponent\Interactor\Request;

use Diside\SecurityComponent\Interactor\Request;

class FindPagesRequest implements Request
{

    /** @var int The user making the request*/
    public $executorId;

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