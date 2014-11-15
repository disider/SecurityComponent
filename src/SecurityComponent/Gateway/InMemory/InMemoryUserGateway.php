<?php

namespace SecurityComponent\Gateway\InMemory;

use SecurityComponent\Gateway\UserGateway;
use SecurityComponent\Model\User;

class InMemoryUserGateway implements UserGateway
{
    private $users = array();

    public function save(User $user)
    {
        if($user->getId() == null) {
            $user->setId(count($this->users) + 1);
        }

        $this->users[$user->getId()] = $user;

        return $user;
    }

    public function delete($id)
    {
        /** @var User $user */
        foreach($this->users as $user) {
            if($user->getId() == $id) {
                unset($this->users[$id]);
                return $user;
            }
        }
    }

    public function findOneById($id)
    {
        /** @var User $user */
        foreach($this->users as $user) {
            if ($user->getId() == $id)
                return $user;
        }

        return null;
    }

    public function findOneByEmail($email)
    {
        /** @var User $user */
        foreach($this->users as $user) {
            if ($user->getEmail() == $email)
                return $user;
        }

        return null;
    }

    public function findOneByRegistrationToken($token)
    {
        /** @var User $user */
        foreach($this->users as $user) {
            if ($user->getRegistrationToken() == $token)
                return $user;
        }

        return null;
    }

    public function findOneByResetPasswordToken($token)
    {
        /** @var User $user */
        foreach($this->users as $user) {
            if ($user->getResetPasswordToken() == $token)
                return $user;
        }

        return null;
    }

    public function findAll($filters = array(), $pageIndex = 0, $pageSize = PHP_INT_MAX)
    {
        $users = $this->filterUsers($filters);

        return array_slice($users, $pageIndex * $pageSize, $pageSize);
    }

    public function findByIds(array $userIds)
    {
        $users = array();

        foreach($this->users as $user) {
            if (in_array($user->getId(), $userIds))
                $users[] = $user;
        }

        return $users;
    }

    public function countAll($filters = array())
    {
        return count($this->findAll($filters));
    }

    private function filterUsers($filters)
    {
        $users = $this->users;

        if(!array_key_exists(self::FILTER_SUPERADMIN, $filters))
            $users = $this->filterOutSuperadmin($users);

        if(array_key_exists(self::FILTER_BY_COMPANY_ID, $filters))
            $users = $this->filterByCompanyId($filters[self::FILTER_BY_COMPANY_ID], $users);

        if(array_key_exists(self::FILTER_ACTIVE, $filters))
            $users = $this->filterActive($users);

        return $users;
    }

    private function filterByCompanyId($companyId, $users)
    {
        $results = array();

        /** @var User $user */
        foreach ($users as $user) {
            if ($user->getCompanyId() === $companyId)
                $results[] = $user;
        }

        return $results;
    }

    private function filterActive($users)
    {
        $results = array();

        /** @var User $user */
        foreach ($users as $user) {
            if ($user->isActive())
                $results[] = $user;
        }

        return $results;
    }

    private function filterOutSuperadmin($users)
    {
        $results = array();

        /** @var User $user */
        foreach ($users as $user) {
            if (!$user->isSuperadmin())
                $results[] = $user;
        }

        return $results;
    }
}