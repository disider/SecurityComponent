<?php

namespace SecurityComponent\Interactor\Presenter;

use SecurityComponent\Interactor\Presenter;
use SecurityComponent\Model\Company;

interface CompanyPresenter extends Presenter
{
    const EMPTY_NAME = 'empty_name';
    const UNDEFINED_SUPERADMIN_ID = 'undefined_superadmin_id';
    const UNDEFINED_COMPANY_ID = 'undefined_company_id';
    const UNDEFINED_COMPANY = 'undefined_company';

    public function getCompany();

    public function setCompany(Company $company);
}