<?php

namespace SecurityComponent\Gateway;

use SecurityComponent\Model\Company;

interface CompanyGateway
{
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
     * @return array
     */
    public function findAll($filters = array(), $pageIndex = 0, $pageSize = PHP_INT_MAX);

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

    /**
     * @return int
     */
    public function countAll($filters = array());
}