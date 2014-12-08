<?php

namespace Diside\SecurityComponent\Gateway\InMemory;

use Diside\SecurityComponent\Gateway\UserGateway;
use Diside\SecurityComponent\Model\User;

class InMemoryUserGateway extends InMemoryBaseGateway implements UserGateway
{
    public function getName()
    {
        return self::NAME;
    }

    public function save(User $user)
    {
        return $this->persist($user);
    }

    public function findOneByEmail($email)
    {
        /** @var User $user */
        foreach($this->getItems() as $user) {
            if ($user->getEmail() == $email)
                return $user;
        }

        return null;
    }

    public function findOneByRegistrationToken($token)
    {
        /** @var User $user */
        foreach($this->getItems() as $user) {
            if ($user->getRegistrationToken() == $token)
                return $user;
        }

        return null;
    }

    public function findOneByResetPasswordToken($token)
    {
        /** @var User $user */
        foreach($this->getItems() as $user) {
            if ($user->getResetPasswordToken() == $token)
                return $user;
        }

        return null;
    }

    public function findByIds(array $userIds)
    {
        $users = array();

        foreach($this->getItems() as $user) {
            if (in_array($user->getId(), $userIds))
                $users[] = $user;
        }

        return $users;
    }

    protected function applyFilters($users, array $filters = array())
    {
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