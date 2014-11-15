<?php

namespace SecurityComponent\Interactor;

use SecurityComponent\Gateway\ChecklistTemplateGateway;
use SecurityComponent\Gateway\UserGateway;
use SecurityComponent\Interactor\Presenter;
use SecurityComponent\Interactor\Request;
use SecurityComponent\Interactor\Presenter\ChecklistTemplatePresenter;
use SecurityComponent\Interactor\Request\DeleteChecklistTemplateRequest;
use SecurityComponent\Model\User;

class DeleteChecklistTemplateInteractor implements Interactor
{

    /** @var UserGateway */
    private $userGateway;

    /** @var ChecklistTemplateGateway */
    private $checklistTemplateGateway;

    public function __construct(ChecklistTemplateGateway $checklistTemplateGateway, UserGateway $userGateway)
    {
        $this->checklistTemplateGateway = $checklistTemplateGateway;
        $this->userGateway = $userGateway;
    }

    public function process(Request $request, Presenter $presenter)
    {
        /** @var DeleteChecklistTemplateRequest $request */
        /** @var ChecklistTemplatePresenter $presenter */

        if ($request->userId === null) {
            $presenter->setErrors(array(ChecklistTemplatePresenter::UNDEFINED_USER_ID));
            return;
        }

        /** @var User $user */
        $user = $this->userGateway->findOneById($request->userId);

        if($user == null) {
            $presenter->setErrors(array(ChecklistTemplatePresenter::UNDEFINED_USER_ID));
            return;
        }

        $template = $this->checklistTemplateGateway->findOneById($request->id);

        if($template == null) {
            $presenter->setErrors(array(ChecklistTemplatePresenter::NOT_FOUND));
            return;
        }

        if(!($user->isSuperadmin() || $user->ownsTemplate($template)
            || ($user->isAdmin() && ($user->getCompanyId() == $template->getCompanyId()))
            )) {
            $presenter->setErrors(array(ChecklistTemplatePresenter::FORBIDDEN));
            return;
        }

        $this->checklistTemplateGateway->delete($template->getId());

        $presenter->setChecklistTemplate($template);
   }
}