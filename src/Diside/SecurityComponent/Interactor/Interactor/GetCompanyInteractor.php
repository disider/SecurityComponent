<?php

namespace Diside\SecurityComponent\Interactor\Interactor;

use Diside\SecurityComponent\Gateway\CompanyGateway;
use Diside\SecurityComponent\Interactor\AbstractInteractor;
use Diside\SecurityComponent\Interactor\Presenter\CompanyPresenter;
use Diside\SecurityComponent\Interactor\Presenter;
use Diside\SecurityComponent\Interactor\Request;
use Diside\SecurityComponent\Interactor\Request\GetCompanyByEmailRequest;
use Diside\SecurityComponent\Interactor\Request\GetCompanyByIdRequest;

class GetCompanyInteractor extends AbstractInteractor
{

    public function process(Request $request, Presenter $presenter)
    {
        $companyGateway = $this->getGateway('company_gateway');

        /** @var GetCompanyByEmailRequest $request */
        /** @var CompanyPresenter $presenter */

        if ($request->id === null) {
            $presenter->setErrors(array(CompanyPresenter::UNDEFINED_COMPANY_ID));
            return;
        }

        $company = $companyGateway->findOneById($request->id);

        if($company == null) {
            $presenter->setErrors(array(CompanyPresenter::UNDEFINED_COMPANY));
            return;
        }

        $presenter->setCompany($company);
    }
}