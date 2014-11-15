<?php

namespace SecurityComponent\Interactor\Presenter;

use SecurityComponent\Interactor\Presenter;

interface ShareRequestsPresenter extends Presenter
{
    const UNDEFINED_CHECKLIST_TEMPLATE_ID = 'undefined_checklist_template_id';
    const EMPTY_EMAILS = 'empty_emails';

    public function getShareRequests();
    public function setShareRequests(array $shareRequests);

}