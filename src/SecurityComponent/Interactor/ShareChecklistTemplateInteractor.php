<?php

namespace SecurityComponent\Interactor;

use SecurityComponent\Gateway\ChecklistTemplateGateway;
use SecurityComponent\Gateway\ShareRequestGateway;
use SecurityComponent\Gateway\UserGateway;
use SecurityComponent\Helper\TokenGenerator;
use SecurityComponent\Interactor\Presenter\ChecklistTemplatePresenter;
use SecurityComponent\Interactor\Presenter\ShareRequestsPresenter;
use SecurityComponent\Interactor\Request\SaveShareRequestsRequest;
use SecurityComponent\Interactor\Presenter;
use SecurityComponent\Interactor\Request;
use SecurityComponent\Model\ShareRequest;

class ShareChecklistTemplateInteractor implements Interactor
{
    /** @var ShareRequestGateway */
    private $shareRequestGateway;

    /** @var ChecklistTemplateGateway */
    private $checklistTemplateGateway;

    /** @var UserGateway */
    private $userGateway;

    public function __construct(ShareRequestGateway $shareRequestGateway, ChecklistTemplateGateway $checklistTemplateGateway, UserGateway $userGateway)
    {
        $this->shareRequestGateway = $shareRequestGateway;
        $this->checklistTemplateGateway = $checklistTemplateGateway;
        $this->userGateway = $userGateway;
    }

    public function process(Request $request, Presenter $presenter)
    {
        /** @var ShareChecklistTemplateRequest $request */
        /** @var ChecklistTemplatePresenter $presenter */

        if (!$this->validate($request, $presenter)) {
            return;
        }

        $user = $this->userGateway->findOneById($request->userId);
        if ($user == null) {
            $presenter->setErrors(array(Presenter::NOT_FOUND));
            return;
        }

        $shareRequest = $this->shareRequestGateway->findOneByToken($request->token);

        if ($shareRequest == null) {
            $presenter->setErrors(array(Presenter::NOT_FOUND));
            return;
        }

        if($user->getEmail() != $shareRequest->getEmail()) {
            $presenter->setErrors(array(Presenter::FORBIDDEN));
            return;
        }

        $template = $shareRequest->getChecklistTemplate();
        $template->addSharingUser($user);
        $template = $this->checklistTemplateGateway->save($template);

        $shareRequests[] = $this->shareRequestGateway->delete($shareRequest->getId());

        $presenter->setChecklistTemplate($template);
    }

    private function validate(Request $request, ChecklistTemplatePresenter $presenter)
    {
        $errors = array();

        if ($request->userId === null)
            $errors[] = ShareRequestsPresenter::UNDEFINED_USER_ID;

        if(!empty($errors)) {
            $presenter->setErrors($errors);
            return false;
        }

        return true;
    }
}