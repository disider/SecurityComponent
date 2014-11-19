<?php

namespace Diside\SecurityComponent\Interactor\Presenter;

use Diside\SecurityComponent\Interactor\Presenter;

interface CompaniesPresenter extends Presenter
{
    /** @return array */
    public function getCompanies();

    public function setCompanies(array $companies);

    /** @return int */
    public function getTotalCompanies();

    public function setTotalCompanies($total);
}