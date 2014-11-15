<?php

namespace SecurityComponent\Interactor;

use SecurityComponent\Gateway\RunningChecklistGateway;
use SecurityComponent\Gateway\RunningTaskGateway;
use SecurityComponent\Gateway\UserGateway;
use SecurityComponent\Interactor\Presenter\RunningTaskPresenter;
use SecurityComponent\Logger\Logger;
use SecurityComponent\Model\RunningTask;

class ChangeRunningTaskStatusInteractor implements Interactor
{
    /** @var UserGateway */
    private $userGateway;

    /** @var RunningChecklistGateway */
    private $checklistGateway;

    /** @var Logger */
    private $logger;

    public function __construct(RunningChecklistGateway $checklistGateway, UserGateway $userGateway, Logger $logger)
    {
        $this->userGateway = $userGateway;
        $this->checklistGateway = $checklistGateway;
        $this->logger = $logger;
    }

    public function process(Request $request, Presenter $presenter)
    {
        if($request->userId === null || $request->id === null) {
            $presenter->setErrors(array(RunningTaskPresenter::BAD_REQUEST));
            return;
        }

        $user = $this->userGateway->findOneById($request->userId);

        $checklist = $this->checklistGateway->findOneByTaskId($request->id);

        if($checklist == null) {
            $presenter->setErrors(array(Presenter::NOT_FOUND));
            return;
        }

        if(!$checklist->hasAssigneeId($user->getId())) {
            $presenter->setErrors(array(RunningTaskPresenter::FORBIDDEN));
            return;
        }

        $task = $checklist->getTaskById($request->id);

        if($task->isChecked() && !$request->checked)
            $task->uncheck();
        else if(!$task->isChecked() && $request->checked)
            $task->check($user, new \DateTime);

        $this->logger->log(Logger::RUNNING_TASK_STATUS_CHANGED, json_encode($request), $user);

        $this->checklistGateway->save($checklist);

        $presenter->setRunningTask($task);
    }
}