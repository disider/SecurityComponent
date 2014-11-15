<?php

namespace SecurityComponent\Interactor;

use SecurityComponent\Exception\InvalidEmailException;
use SecurityComponent\Gateway\ChecklistTemplateGateway;
use SecurityComponent\Gateway\ShareRequestGateway;
use SecurityComponent\Helper\TokenGenerator;
use SecurityComponent\Interactor\Presenter\ShareRequestsPresenter;
use SecurityComponent\Interactor\Request\SaveShareRequestsRequest;
use SecurityComponent\Interactor\Presenter;
use SecurityComponent\Interactor\Request;
use SecurityComponent\Model\ShareRequest;

class SaveShareRequestsInteractor implements Interactor
{
    /** @var ShareRequestGateway */
    private $shareRequestGateway;

    /** @var ChecklistTemplateGateway */
    private $checklistTemplateGateway;

    public function __construct(ShareRequestGateway $shareRequestGateway, ChecklistTemplateGateway $checklistTemplateGateway)
    {
        $this->shareRequestGateway = $shareRequestGateway;
        $this->checklistTemplateGateway = $checklistTemplateGateway;
    }

    public function process(Request $request, Presenter $presenter)
    {
        /** @var SaveShareRequestsRequest $request */
        /** @var ShareRequestsPresenter $presenter */

        if (!$this->validate($request, $presenter)) {
            return;
        }

        $template = $this->checklistTemplateGateway->findOneById($request->checklistTemplateId);

        if ($template == null) {
            $presenter->setErrors(array(Presenter::NOT_FOUND));
            return;
        }

        $shareRequests = array();

        foreach($request->emails as $email) {
            try {
                $shareRequest = new ShareRequest(null, TokenGenerator::generateToken(), $template, $email);
                $shareRequests[] = $this->shareRequestGateway->save($shareRequest);
            }
            catch(InvalidEmailException $e) {

            }
        }

        $presenter->setShareRequests($shareRequests);
    }

    private function validate(Request $request, ShareRequestsPresenter $presenter)
    {
        $errors = array();

        if ($request->checklistTemplateId === null)
            $errors[] = ShareRequestsPresenter::UNDEFINED_CHECKLIST_TEMPLATE_ID;

        if (empty($request->emails))
            $errors[] = ShareRequestsPresenter::EMPTY_EMAILS;

        if(!empty($errors)) {
            $presenter->setErrors($errors);
            return false;
        }

        return true;
    }
}