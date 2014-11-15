<?php

namespace SecurityComponent\Interactor\Presenter;

use SecurityComponent\Interactor\Presenter;
use SecurityComponent\Model\ShareRequest;

interface ShareRequestPresenter extends Presenter
{
    const UNDEFINED_SHARE_REQUEST_ID = 'undefined_share_request_id';

    public function getShareRequest();
    public function setShareRequest(ShareRequest $shareRequest);

}