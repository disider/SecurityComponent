<?php

namespace SecurityComponent\Interactor;

use SecurityComponent\Gateway\ChecklistTemplateGateway;
use SecurityComponent\Gateway\UserGateway;
use SecurityComponent\Interactor\Presenter\FindChecklistTemplatesPresenter;
use SecurityComponent\Interactor\Presenter;
use SecurityComponent\Interactor\Request;
use SecurityComponent\Interactor\Request\FindChecklistTemplatesRequest;
use Whalist\CoreBundle\Entity\ChecklistTemplate;

class FindChecklistTemplatesInteractor implements Interactor
{
    /** @var ChecklistTemplateGateway */
    private $checklistTemplateGateway;

    /** @var UserGateway */
    private $userGateway;

    public function __construct(ChecklistTemplateGateway $checklistTemplateGateway, UserGateway $userGateway)
    {
        $this->checklistTemplateGateway = $checklistTemplateGateway;
        $this->userGateway = $userGateway;
    }

    public function process(Request $request, Presenter $presenter)
    {
        /** @var FindChecklistTemplatesRequest $request */
        /** @var FindChecklistTemplatesPresenter $presenter */

        if ($request->userId === null) {
            $presenter->setErrors(array(FindChecklistTemplatesPresenter::UNDEFINED_OWNER_ID));
            return;
        }

        $user = $this->userGateway->findOneById($request->userId);

        if($user == null) {
            $presenter->setErrors(array(FindChecklistTemplatesPresenter::UNDEFINED_OWNER_ID));
            return;
        }

        $filters = $request->filters;

        if($user->isSuperadmin()) {
            if(array_key_exists(ChecklistTemplateGateway::FILTER_ONLY_MINE, $filters) && $filters[ChecklistTemplateGateway::FILTER_ONLY_MINE])
                $filters[ChecklistTemplateGateway::FILTER_BY_OWNER_OR_SHARING_USER_ID] = $request->userId;
        }
        else if($user->isAdmin()) {
            $filters[ChecklistTemplateGateway::FILTER_BY_COMPANY_ID] = $user->getCompanyId();
        }
        else {
            $filters[ChecklistTemplateGateway::FILTER_BY_OWNER_OR_SHARING_USER_ID] = $request->userId;
        }

        $templates = $this->checklistTemplateGateway->findAll($filters, $request->pageIndex, $request->pageSize);
        $total = $this->checklistTemplateGateway->countAll($filters);

        $presenter->setChecklistTemplates($templates);
        $presenter->setTotalChecklistTemplates($total);
    }
}