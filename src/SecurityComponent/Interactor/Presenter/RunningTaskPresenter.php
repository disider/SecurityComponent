<?php

namespace SecurityComponent\Interactor\Presenter;

use SecurityComponent\Interactor\Presenter;
use SecurityComponent\Model\RunningTask;

interface RunningTaskPresenter extends Presenter
{
    const UNDEFINED_RUNNING_TASK_ID = 'undefined_running_task_id';

    public function getRunningTask();

    public function setRunningTask(RunningTask $task);
}