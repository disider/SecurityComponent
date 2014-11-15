<?php

namespace SecurityComponent\Interactor\Presenter;

use SecurityComponent\Interactor\Presenter;

interface FindChecklistTemplatesPresenter extends Presenter
{
    const UNDEFINED_OWNER_ID = 'undefined_owner_id';

    public function getChecklistTemplates();

    public function setChecklistTemplates(array $templates);

    public function getTotalChecklistTemplates();

    public function setTotalChecklistTemplates($total);
}