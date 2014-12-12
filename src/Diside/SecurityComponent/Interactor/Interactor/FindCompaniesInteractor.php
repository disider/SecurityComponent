<?php

namespace Diside\SecurityComponent\Interactor\Interactor;

use Diside\SecurityComponent\Gateway\CompanyGateway;
use Diside\SecurityComponent\Gateway\UserGateway;
use Diside\SecurityComponent\Interactor\AbstractInteractor;
use Diside\SecurityComponent\Interactor\Presenter;
use Diside\SecurityComponent\Interactor\Presenter\CompaniesPresenter;
use Diside\SecurityComponent\Interactor\Request;
use Diside\SecurityComponent\Interactor\Request\FindCompaniesRequest;

class FindCompaniesInteractor extends AbstractInteractor
{
    public function process(Request $request, Presenter $presenter)
    {
        $companyGateway = $this->getGateway(CompanyGateway::NAME);
        $userGateway = $this->getGateway(UserGateway::NAME);

        /** @var FindCompaniesRequest $request */
        /** @var CompaniesPresenter $presenter */

        $user = $userGateway->findOneById($request->userId);

        $filters = array();

        $companies = $companyGateway->findAll($filters, $request->pageIndex, $request->pageSize);

        $total = $companyGateway->countAll();

        $presenter->setCompanies($companies);
        $presenter->setTotalCompanies($total);
    }
}