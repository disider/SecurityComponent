<?php

namespace SecurityComponent\Gateway;

use SecurityComponent\Model\User;

interface UserGateway
{
    const FILTER_SUPERADMIN = 'filter_superadmin';
    const FILTER_BY_COMPANY_ID = 'filter_by_company_id';
    const FILTER_ACTIVE = 'filter_active';

    /**
     * @param User $user
     * @return User
     */
    public function save(User $user);

    /**
     * @param $id
     */
    public function delete($id);

    /**
     * @param string $id
     * @return User
     */
    public function findOneById($id);

    /**
     * @param string $email
     * @return User
     */
    public function findOneByEmail($email);

    /**
     * @param string $token
     * @return User
     */
    public function findOneByRegistrationToken($token);

    /**
     * @param string $token
     * @return User
     */
    public function findOneByResetPasswordToken($token);

    /**
     * @param array $filters
     * @param int $pageIndex
     * @param int $pageSize
     * @return array
     */
    public function findAll($filters = array(), $pageIndex = 0, $pageSize = PHP_INT_MAX);

    /**
     * @param $userIds
     * @return array
     */
    public function findByIds(array $userIds);


    /**
     * @return int
     */
    public function countAll($filters = array());
}