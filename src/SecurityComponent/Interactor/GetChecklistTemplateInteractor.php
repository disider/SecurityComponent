<?php

namespace SecurityComponent\Interactor;

use SecurityComponent\Gateway\ChecklistTemplateGateway;
use SecurityComponent\Gateway\UserGateway;
use SecurityComponent\Interactor\Presenter;
use SecurityComponent\Interactor\Presenter\ChecklistTemplatePresenter;
use SecurityComponent\Interactor\Presenter\GetRunningChecklistPresenter;
use SecurityComponent\Interactor\Request;
use SecurityComponent\Interactor\Request\GetChecklistTemplateRequest;

class GetChecklistTemplateInteractor implements Interactor
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
        /** @var GetChecklistTemplateRequest $request */
        /** @var ChecklistTemplatePresenter $presenter */

        $errors = array();

        if ($request->userId === null)
            $errors[] = ChecklistTemplatePresenter::UNDEFINED_USER_ID;

        if ($request->id === null)
            $errors[] = ChecklistTemplatePresenter::UNDEFINED_CHECKLIST_TEMPLATE_ID;

        if(count($errors) > 0) {
            $presenter->setErrors($errors);
            return;
        }

        $user = $this->userGateway->findOneById($request->userId);

        $template = $this->checklistTemplateGateway->findOneById($request->id);

        if ($template == null) {
            $presenter->setErrors(array(ChecklistTemplatePresenter::NOT_FOUND));
            return;
        }

        if (!($user->isSuperadmin() || ($user->isAdmin() && $template->hasSameCompanyAs($user)) || $user->ownsTemplate($template))) {
            $presenter->setErrors(array(Presenter::FORBIDDEN));
            return;
        }

        $presenter->setChecklistTemplate($template);
    }
}