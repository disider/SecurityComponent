<?php

namespace SecurityComponent\Interactor;

use SecurityComponent\Gateway\CompanyGateway;
use SecurityComponent\Gateway\UserGateway;
use SecurityComponent\Interactor\Presenter;
use SecurityComponent\Interactor\Presenter\DeleteCompanyPresenter;
use SecurityComponent\Interactor\Presenter\CompanyPresenter;
use SecurityComponent\Interactor\Request;
use SecurityComponent\Interactor\Request\DeleteCompanyRequest;

class DeleteCompanyInteractor implements Interactor
{
    /** @var UserGateway */
    private $userGateway;

    /** @var CompanyGateway  */
    private $companyGateway;

    public function __construct(CompanyGateway $companyGateway, UserGateway $userGateway)
    {
        $this->userGateway = $userGateway;
        $this->companyGateway = $companyGateway;
    }

    public function process(Request $request, Presenter $presenter)
    {
        /** @var DeleteCompanyRequest $request */
        /** @var CompanyPresenter $presenter */

        if ($request->superadminId === null) {
            $presenter->setErrors(array(CompanyPresenter::UNDEFINED_SUPERADMIN_ID));
            return;
        }

        $superadmin = $this->userGateway->findOneById($request->superadminId);
        if (!$superadmin->isSuperadmin()) {
            $presenter->setErrors(array(CompanyPresenter::FORBIDDEN));
            return;
        }

        $company = $this->companyGateway->findOneById($request->id);

        if ($company == null) {
            $presenter->setErrors(array(CompanyPresenter::UNDEFINED_COMPANY_ID));
            return;
        }

        $this->companyGateway->delete($company->getId());

        $presenter->setCompany($company);
    }
}