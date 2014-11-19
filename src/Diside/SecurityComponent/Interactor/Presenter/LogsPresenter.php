<?php

namespace Diside\SecurityComponent\Interactor\Presenter;

use Diside\SecurityComponent\Interactor\Presenter;

interface LogsPresenter extends Presenter
{
    /** @return array */
    public function getLogs();

    public function setLogs(array $logs);

    /** @return int */
    public function getTotalLogs();

    public function setTotalLogs($total);
}