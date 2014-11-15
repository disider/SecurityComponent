<?php

namespace SecurityComponent\Interactor;

use SecurityComponent\Gateway\CompanyGateway;
use SecurityComponent\Interactor\Presenter\CompanyPresenter;
use SecurityComponent\Interactor\Request\SaveCompanyRequest;
use SecurityComponent\Interactor\Presenter;
use SecurityComponent\Interactor\Request;
use SecurityComponent\Model\Company;

class SaveCompanyInteractor implements Interactor
{
    /** @var CompanyGateway */
    private $companyGateway;

    public function __construct(CompanyGateway $companyGateway)
    {
        $this->companyGateway = $companyGateway;
    }

    public function process(Request $request, Presenter $presenter)
    {
        /** @var SaveCompanyRequest $request */
        /** @var CompanyPresenter $presenter */

        if (!$this->validate($request, $presenter)) {
            return;
        }

        $company = new Company($request->id, $request->name);

        $company = $this->companyGateway->save($company);

        $presenter->setCompany($company);
    }

    private function validate(Request $request, CompanyPresenter $presenter)
    {
        if ($request->name === null) {
            $error = CompanyPresenter::EMPTY_NAME;
            $presenter->setErrors(array($error));
            return false;
        }

        return true;
    }
}