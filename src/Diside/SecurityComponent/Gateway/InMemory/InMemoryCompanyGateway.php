<?php

namespace Diside\SecurityComponent\Gateway\InMemory;

use Diside\SecurityComponent\Gateway\CompanyGateway;
use Diside\SecurityComponent\Model\Company;

class InMemoryCompanyGateway extends InMemoryBaseGateway implements CompanyGateway
{
    public function getName()
    {
        return self::NAME;
    }

    public function save(Company $company)
    {
        return $this->persist($company);
    }

    public function findOneByName($name)
    {
        /** @var Company $company */
        foreach ($this->getItems() as $company)
            if ($company->getName() == $name)
                return $company;

        return null;
    }

    protected function applyFilters($items, array $filters = array())
    {
        return $items;
    }
}