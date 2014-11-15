<?php

namespace SecurityComponent\Interactor;

use SecurityComponent\Gateway\ChecklistTemplateGateway;
use SecurityComponent\Gateway\RunningChecklistGateway;
use SecurityComponent\Gateway\UserGateway;
use SecurityComponent\Interactor\Presenter;
use SecurityComponent\Interactor\Request;
use SecurityComponent\Interactor\Request\RunChecklistTemplateRequest;
use SecurityComponent\Model\RunningChecklist;
use SecurityComponent\Model\RunningTask;
use SecurityComponent\Model\TaskTemplate;

class RunChecklistTemplateInteractor implements Interactor
{
    /** @var UserGateway */
    private $userGateway;

    /** @var ChecklistTemplateGateway */
    private $checklistTemplateGateway;

    /** @var RunningChecklistGateway */
    private $runningChecklistGateway;

    public function __construct(RunningChecklistGateway $runningChecklistGateway, ChecklistTemplateGateway $checklistTemplateGateway, UserGateway $userGateway)
    {
        $this->runningChecklistGateway = $runningChecklistGateway;
        $this->checklistTemplateGateway = $checklistTemplateGateway;
        $this->userGateway = $userGateway;
    }

    public function process(Request $request, Presenter $presenter)
    {
        /** @var RunChecklistTemplateRequest $request */
        /** @var RunningChecklistTemplatePresenter $presenter */

        $template = $this->checklistTemplateGateway->findOneById($request->id);

        if ($template == null) {
            $presenter->setErrors(array(Presenter::NOT_FOUND));
            return;
        }

        /** @var User $user */
        $user = $this->userGateway->findOneById($request->userId);

        if (!($user->isSuperadmin()
            || ($user->isAdmin() && $template->hasSameCompanyAs($user))
            || $user->ownsTemplate($template)
            || $user->isSharing($template))) {
            $presenter->setErrors(array(Presenter::FORBIDDEN));
            return;
        }

        $user = $this->userGateway->findOneById($request->userId);

        $checklist = new RunningChecklist(null, $template);

        $checklist->assignTo($user);

        /** @var TaskTemplate $taskTemplate */
        foreach ($template->getTaskTemplates() as $taskTemplate) {
            $task = new RunningTask(null, null, $taskTemplate->getTitle());
            $task->setDescription($taskTemplate->getDescription());
            $task->setVideoRef($taskTemplate->getVideoRef());
            $task->setImageUrl($taskTemplate->getImageUrl());

            $checklist->addTask($task);
        }

        $checklist = $this->runningChecklistGateway->save($checklist);
        $presenter->setRunningChecklist($checklist);
    }
}