<?php

namespace SecurityComponent\Interactor;

use SecurityComponent\Gateway\CompanyGateway;
use SecurityComponent\Interactor\Presenter\CompanyPresenter;
use SecurityComponent\Interactor\Presenter;
use SecurityComponent\Interactor\Request;
use SecurityComponent\Interactor\Request\GetCompanyByEmailRequest;
use SecurityComponent\Interactor\Request\GetCompanyByIdRequest;

class GetCompanyInteractor implements Interactor
{

    /** @var CompanyGateway */
    private $companyGateway;

    public function __construct(CompanyGateway $companyGateway)
    {
        $this->companyGateway = $companyGateway;
    }

    public function process(Request $request, Presenter $presenter)
    {
        /** @var GetCompanyByEmailRequest $request */
        /** @var CompanyPresenter $presenter */

        if ($request->id === null) {
            $presenter->setErrors(array(CompanyPresenter::UNDEFINED_COMPANY_ID));
            return;
        }

        $company = $this->companyGateway->findOneById($request->id);

        if($company == null) {
            $presenter->setErrors(array(CompanyPresenter::UNDEFINED_COMPANY));
            return;
        }

        $presenter->setCompany($company);
    }
}