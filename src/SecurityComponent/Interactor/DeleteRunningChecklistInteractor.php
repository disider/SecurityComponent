<?php

namespace SecurityComponent\Interactor;

use SecurityComponent\Gateway\RunningChecklistGateway;
use SecurityComponent\Gateway\UserGateway;
use SecurityComponent\Interactor\Presenter;
use SecurityComponent\Interactor\Presenter\RunningChecklistPresenter;
use SecurityComponent\Interactor\Request;
use SecurityComponent\Interactor\Request\DeleteRunningChecklistRequest;
use SecurityComponent\Model\User;

class DeleteRunningChecklistInteractor implements Interactor
{
    /** @var UserGateway */
    private $userGateway;

    /** @var RunningChecklistGateway */
    private $runningChecklistGateway;

    public function __construct(RunningChecklistGateway $runningChecklistGateway, UserGateway $userGateway)
    {
        $this->runningChecklistGateway = $runningChecklistGateway;
        $this->userGateway = $userGateway;
    }

    public function process(Request $request, Presenter $presenter)
    {
        /** @var DeleteRunningChecklistRequest $request */
        /** @var RunningChecklistPresenter $presenter */

        if ($request->userId === null) {
            $presenter->setErrors(array(RunningChecklistPresenter::UNDEFINED_USER_ID));
            return;
        }

        /** @var User $user */
        $user = $this->userGateway->findOneById($request->userId);

        if ($user == null) {
            $presenter->setErrors(array(RunningChecklistPresenter::UNDEFINED_USER_ID));
            return;
        }

        $checklist = $this->runningChecklistGateway->findOneById($request->id);

        if ($checklist == null) {
            $presenter->setErrors(array(RunningChecklistPresenter::NOT_FOUND));
            return;
        }

        if (!($user->isSuperadmin() || $user->ownsChecklist($checklist)
            || ($user->isAdmin() && ($checklist->hasSameCompanyAs($user)))
            || $user->isSharing($checklist->getChecklistTemplate()))
        ) {
            $presenter->setErrors(array(RunningChecklistPresenter::FORBIDDEN));
            return;
        }

        $this->runningChecklistGateway->delete($checklist->getId());

        $presenter->setRunningChecklist($checklist);
    }
}