<?php

namespace Diside\SecurityComponent\Interactor\Presenter;

use Diside\SecurityComponent\Interactor\Presenter;
use Diside\SecurityComponent\Model\Page;

interface PagePresenter extends Presenter
{
    const UNDEFINED_PAGE_ID = 'undefined_page_id';
    const UNDEFINED_PAGE = 'undefined_page';

    public function getPage();

    public function setPage(Page $page);
}