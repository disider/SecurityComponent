<?php

namespace Diside\SecurityComponent\Interactor\Interactor;

use Diside\SecurityComponent\Gateway\CompanyGateway;
use Diside\SecurityComponent\Interactor\AbstractInteractor;
use Diside\SecurityComponent\Interactor\Presenter\CompanyPresenter;
use Diside\SecurityComponent\Interactor\Request\SaveCompanyRequest;
use Diside\SecurityComponent\Interactor\Presenter;
use Diside\SecurityComponent\Interactor\Request;
use Diside\SecurityComponent\Model\Company;

class SaveCompanyInteractor extends AbstractInteractor
{
    public function process(Request $request, Presenter $presenter)
    {
        $companyGateway = $this->getGateway(CompanyGateway::NAME);

        /** @var SaveCompanyRequest $request */
        /** @var CompanyPresenter $presenter */

        if (!$this->validate($request, $presenter)) {
            return;
        }

        $company = $this->buildCompany($request);

        $company = $companyGateway->save($company);

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

    /**
     * @param SaveCompanyRequest $request
     * @return Company
     */
    protected function buildCompany(Request $request)
    {
        return new Company($request->id, $request->name);
    }
}