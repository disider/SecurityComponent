<?php

namespace Diside\SecurityComponent\Gateway\InMemory;

use Diside\SecurityComponent\Gateway\PageGateway;
use Diside\SecurityComponent\Model\Page;

class InMemoryPageGateway extends InMemoryBaseGateway implements PageGateway
{
    public function getName()
    {
        return self::NAME;
    }

    public function save(Page $page)
    {
        return $this->persist($page);
    }

    protected function applyFilters($items, array $filters = array())
    {
        return $items;
    }
}