<?php

namespace SecurityComponent\Interactor\Presenter;

use SecurityComponent\Interactor\Presenter;

interface FindLogsPresenter extends Presenter
{
    /** @return array */
    public function getLogs();

    public function setLogs(array $logs);

    /** @return int */
    public function getTotalLogs();

    public function setTotalLogs($total);
}