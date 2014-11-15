<?php

namespace SecurityComponent\Interactor\Presenter;


use SecurityComponent\Interactor\Presenter;

interface FindCompaniesPresenter extends Presenter
{
    /** @return array */
    public function getCompanies();

    public function setCompanies(array $companies);

    /** @return int */
    public function getTotalCompanies();

    public function setTotalCompanies($total);
}