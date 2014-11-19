<?php

namespace Diside\SecurityComponent\Gateway;

use Diside\SecurityComponent\Model\Company;

interface CompanyGateway extends Gateway
{
    const NAME = 'company_gateway';

    /**
     * @param Company $company
     * @return Company
     */
    public function save(Company $company);

    /**
     * @param int $id
     * @return Company
     */
    public function delete($id);

    /**
     * @param string $name
     * @return Company
     */
    public function findOneByName($name);

    /**
     * @param string $id
     * @return Company
     */
    public function findOneById($id);
}