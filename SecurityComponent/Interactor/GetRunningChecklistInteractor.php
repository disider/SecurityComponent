<?php

namespace SecurityComponent\Interactor;

use SecurityComponent\Gateway\RunningChecklistGateway;
use SecurityComponent\Gateway\UserGateway;
use SecurityComponent\Interactor\Presenter;
use SecurityComponent\Interactor\Presenter\RunningChecklistPresenter;
use SecurityComponent\Interactor\Request;
use SecurityComponent\Interactor\Request\GetRunningChecklistRequest;

class GetRunningChecklistInteractor implements Interactor
{
    /** @var RunningChecklistGateway  */
    private $runningChecklistGateway;

    /** @var UserGateway */
    private $userGateway;

    public function __construct(RunningChecklistGateway $runningChecklistGateway, UserGateway $userGateway)
    {
        $this->runningChecklistGateway = $runningChecklistGateway;
        $this->userGateway = $userGateway;
    }

    public function process(Request $request, Presenter $presenter)
    {
        /** @var GetRunningChecklistRequest $request */
        /** @var RunningChecklistPresenter $presenter */

        $errors = array();

        if ($request->userId === null)
            $errors[] = RunningChecklistPresenter::UNDEFINED_USER_ID;

        if ($request->id === null)
            $errors[] = RunningChecklistPresenter::UNDEFINED_RUNNING_CHECKLIST_ID;

        if(count($errors) > 0) {
            $presenter->setErrors($errors);
            return;
        }

        $user = $this->userGateway->findOneById($request->userId);

        $checklist = $this->runningChecklistGateway->findOneById($request->id);

        if($checklist == null) {
            $presenter->setErrors(array(RunningChecklistPresenter::NOT_FOUND));
            return;
        }

        if(!($user->isSuperadmin() || ($user->isAdmin() && $checklist->hasSameCompanyAs($user)) || $user->ownsChecklist($checklist) || $user->isAssignedTo($checklist))) {
            $presenter->setErrors(array(RunningChecklistPresenter::FORBIDDEN));
            return;
        }

        $presenter->setRunningChecklist($checklist);
    }
}