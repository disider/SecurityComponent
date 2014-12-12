<?php

namespace Diside\SecurityComponent\Interactor\Request;

use Diside\SecurityComponent\Interactor\Request;

class GetPageRequest implements Request
{
    /** @var int */
    public $executorId;

    /** @var string */
    public $language;

    /** @var string */
    public $url;

    public function __construct($executorId, $language, $url)
    {
        $this->executorId = $executorId;
        $this->language = $language;
        $this->url = $url;
    }
} 