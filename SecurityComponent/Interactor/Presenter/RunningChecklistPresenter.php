<?php

namespace SecurityComponent\Interactor\Presenter;

use SecurityComponent\Interactor\Presenter;
use SecurityComponent\Model\RunningChecklist;

interface RunningChecklistPresenter extends Presenter
{
    const UNDEFINED_RUNNING_CHECKLIST_ID = 'undefined_running_checklist_id';

    /** @return RunningChecklist */
    public function getRunningChecklist();

    public function setRunningChecklist(RunningChecklist $runningChecklist);
}
