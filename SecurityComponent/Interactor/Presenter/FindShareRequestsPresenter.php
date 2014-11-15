<?php

namespace SecurityComponent\Interactor\Presenter;

use SecurityComponent\Interactor\Presenter;

interface FindShareRequestsPresenter extends Presenter
{
    /** @return array */
    public function getShareRequests();

    public function setShareRequests(array $categories);

    /** @return int */
    public function getTotalShareRequests();

    public function setTotalShareRequests($total);
}