<?php

namespace SecurityComponent\Interactor;

use SecurityComponent\Gateway\RunningChecklistGateway;
use SecurityComponent\Gateway\UserGateway;
use SecurityComponent\Interactor\Presenter;
use SecurityComponent\Interactor\Presenter\RunningChecklistPresenter;
use SecurityComponent\Interactor\Request;
use SecurityComponent\Interactor\Request\ProcessRunningChecklistRequest;
use SecurityComponent\Interactor\Request\SaveRunningChecklistRequest;
use SecurityComponent\Logger\Logger;

class SaveRunningChecklistInteractor implements Interactor
{
    /** @var UserGateway */
    private $userGateway;

    /** @var RunningChecklistGateway */
    private $runningChecklistGateway;

    public function __construct(RunningChecklistGateway $runningChecklistGateway, UserGateway $userGateway, Logger $logger)
    {
        $this->runningChecklistGateway = $runningChecklistGateway;
        $this->userGateway = $userGateway;
        $this->logger = $logger;
    }

    public function process(Request $request, Presenter $presenter)
    {
        /** @var SaveRunningChecklistRequest $request */
        /** @var RunningChecklistPresenter $presenter */

        if (!$this->validate($request, $presenter)) {
            return;
        }

        $user = $this->userGateway->findOneById($request->userId);

        $checklist = $this->runningChecklistGateway->findOneById($request->id);

        if ($checklist == null) {
            $presenter->setErrors(array(Presenter::NOT_FOUND));
            return;
        }

        if ($request instanceof SaveRunningChecklistRequest) {
            if (!($user->isSuperadmin()
                || ($user->isAdminFor($checklist->getCompanyId()))
                || $user->ownsChecklist($checklist))
            ) {
                $presenter->setErrors(array(Presenter::FORBIDDEN));
                return;
            }

            $checklist->setSubtitle($request->subtitle);
            $assignees = $this->userGateway->findByIds($request->assignees);
            $checklist->setAssignees($assignees);
        }
        else if ($request instanceof ProcessRunningChecklistRequest) {
            if (!($user->isSuperadmin() || $user->isAssignedTo($checklist))) {
                $presenter->setErrors(array(Presenter::FORBIDDEN));
                return;
            }

            foreach ($checklist->getTasks() as $task) {
                if (in_array($task->getId(), $request->tasks))
                    $checklist->checkTask($user, $task->getId());
                else
                    $checklist->uncheckTask($task->getId());
            }
        }

        $this->logger->log(Logger::RUNNING_CHECKLIST_SAVED, json_encode($request), $user);

        $checklist = $this->runningChecklistGateway->save($checklist);

        $presenter->setRunningChecklist($checklist);
    }

    private function validate(Request $request, RunningChecklistPresenter $presenter)
    {
        if ($request->id === null) {
            $presenter->setErrors(array(Presenter::NOT_FOUND));
            return false;
        }

        return true;
    }
}