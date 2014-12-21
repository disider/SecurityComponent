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

    public function findOneByLanguageAndUrl($locale, $url)
    {
        /** @var Page $page*/
        foreach ($this->getItems() as $page)
            if ($page->hasTranslation($locale)) {
                $translation = $page->getTranslation('en');

                if ($translation->getUrl() == $url) {
                    return $page;
                }
            }

        return null;
    }

}