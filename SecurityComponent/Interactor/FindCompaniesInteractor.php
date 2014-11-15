<?php

namespace SecurityComponent\Interactor;


use SecurityComponent\Gateway\CompanyGateway;
use SecurityComponent\Gateway\UserGateway;
use SecurityComponent\Interactor\Presenter\FindCompaniesPresenter;
use SecurityComponent\Interactor\Request\FindCompaniesRequest;

class FindCompaniesInteractor implements Interactor
{
    /** @var CompanyGateway */
    private $companyGateway;

    /** @var UserGateway */
    private $userGateway;

    public function __construct(CompanyGateway $companyGateway, UserGateway $userGateway)
    {
        $this->companyGateway = $companyGateway;
        $this->userGateway = $userGateway;
    }

    public function process(Request $request, Presenter $presenter)
    {
        /** @var FindCompaniesRequest $request */
        /** @var FindCompaniesPresenter $presenter */

        $user = $this->userGateway->findOneById($request->userId);

        $filters = array();

        $companys = $this->companyGateway->findAll($filters, $request->pageIndex, $request->pageSize);

        $total = $this->companyGateway->countAll();

        $presenter->setCompanies($companys);
        $presenter->setTotalCompanies($total);
    }
}