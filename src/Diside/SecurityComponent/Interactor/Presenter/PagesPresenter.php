<?php

namespace Diside\SecurityComponent\Interactor\Presenter;

use Diside\SecurityComponent\Interactor\Presenter;

interface PagesPresenter extends Presenter
{
    /** @return array */
    public function getPages();

    public function setPages(array $pages);

    /** @return int */
    public function getTotalPages();

    public function setTotalPages($total);
}