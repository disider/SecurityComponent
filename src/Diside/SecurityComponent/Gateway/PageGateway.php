<?php

namespace Diside\SecurityComponent\Gateway;

use Diside\SecurityComponent\Model\Page;

interface PageGateway extends Gateway
{
    const NAME = 'page_gateway';

    /**
     * @param Page $page
     * @return Page
     */
    public function save(Page $page);

    /**
     * @param int $id
     * @return Page
     */
    public function delete($id);

    /**
     * @param string $language
     * @param string $url
     * @return Page
     */
    public function findOneByLanguageAndUrl($language, $url);

    /**
     * @param int $id
     * @return Page
     */
    public function findOneById($id);
}