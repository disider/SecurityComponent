<?php

namespace Diside\SecurityComponent\Interactor\Request;

use Diside\SecurityComponent\Interactor\Request;

class FindChecklistTemplatesRequest implements Request
{
    /** @var int */
    public $userId;

    /** @var int */
    public $pageIndex;

    /** @var int */
    public $pageSize;

    /** @var array */
    public $filters;

    public function __construct($ownerId, $pageIndex = 0, $pageSize = PHP_INT_MAX, array $filters = array())
    {
        $this->userId = $ownerId;
        $this->pageIndex = $pageIndex;
        $this->pageSize = $pageSize;
        $this->filters = $filters;
    }
} 