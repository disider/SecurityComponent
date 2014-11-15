<?php

namespace SecurityComponent\Interactor\Presenter;

use SecurityComponent\Interactor\Presenter;

interface FindRunningChecklistsPresenter extends Presenter
{
    public function getRunningChecklists();

    public function setRunningChecklists($checklists);

    public function getTotalRunningChecklists();

    public function setTotalRunningChecklists($total);

}