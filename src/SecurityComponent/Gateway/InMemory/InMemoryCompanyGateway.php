<?php

namespace SecurityComponent\Gateway\InMemory;

use SecurityComponent\Gateway\CompanyGateway;
use SecurityComponent\Model\Company;

class InMemoryCompanyGateway implements CompanyGateway
{
    private $companies = array();

    public function save(Company $company)
    {
        if($company->getId() == null) {
            $company->setId(count($this->companies) + 1);
        }

        $this->companies[$company->getId()] = $company;

        return $company;
    }

    public function delete($id)
    {
        /** @var Company $company */
        foreach($this->companies as $company) {
            if($company->getId() == $id) {
                unset($this->companies[$id]);
                return $company;
            }
        }
    }

    /**
     * @return array
     */
    public function findAll($filters = array(), $pageIndex = 0, $pageSize = PHP_INT_MAX)
    {
        return array_slice($this->companies, $pageIndex * $pageSize, $pageSize);
    }

    public function findOneByName($name)
    {
        /** @var Company $company */
        foreach($this->companies as $company)
            if($company->getName() == $name)
                return $company;

        return null;
    }

    public function findOneById($id)
    {
        /** @var Company $company */
        foreach($this->companies as $company)
            if($company->getId() == $id)
                return $company;

        return null;
    }

    public function countAll($filters = array())
    {
        return count($this->companies);
    }
}