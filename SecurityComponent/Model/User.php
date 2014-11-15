<?php

namespace SecurityComponent\Model;

class User
{
    const ROLE_USER = 'ROLE_USER';
    const ROLE_FREE_USER = 'ROLE_FREE_USER';
    const ROLE_MANAGER = 'ROLE_MANAGER';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    const ROLE_SUPERADMIN = 'ROLE_SUPERADMIN';
    const ROLE_ALLOWED_TO_SWITCH = 'ROLE_ALLOWED_TO_SWITCH';

    /** @var int */
    private $id;

    /** @var string */
    private $email;

    /** @var string */
    private $password;

    /** @var string */
    private $salt;

    /** @var bool */
    private $isActive = false;

    /** @var array */
    private $roles = array();

    /** @var Company */
    private $company;

    /** @var string */
    private $registrationToken;

    /** @var string */
    private $resetPasswordToken;

    public function __construct($id, $email, $password, $salt)
    {
        $this->id = $id;
        $this->email = $email;
        $this->password = $password;
        $this->salt = $salt;
    }

    public function __toString()
    {
        return $this->email;
    }

    public static function getUserRoles()
    {
        return array(
            self::ROLE_USER,
            self::ROLE_MANAGER,
            self::ROLE_ADMIN,
        );
    }

    public static function getSuperadminRoles()
    {
        return array(
            self::ROLE_USER,
            self::ROLE_MANAGER,
            self::ROLE_ADMIN,
            self::ROLE_SUPERADMIN,
            self::ROLE_ALLOWED_TO_SWITCH
        );
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    public function getRoles()
    {
        return array_unique(array_merge($this->roles, array(self::ROLE_USER)));
    }

    public function setActive($isActive)
    {
        $this->isActive = $isActive;
    }

    public function isActive()
    {
        return $this->isActive;
    }

    public function addRole($role)
    {
        if (!in_array($role, $this->roles) && $role != self::ROLE_USER)
            $this->roles[] = $role;
    }

    public function isSuperadmin()
    {
        return $this->hasRole(self::ROLE_SUPERADMIN);
    }

    public function isAdmin()
    {
        return $this->hasRole(self::ROLE_ADMIN);
    }

    public function isManager()
    {
        return $this->hasRole(self::ROLE_MANAGER);
    }

    public function isFreeUser()
    {
        return $this->hasRole(self::ROLE_FREE_USER);
    }

    public function hasRole($role)
    {
        return in_array($role, $this->roles);
    }

    public function setRoles(array $roles)
    {
        $this->roles = array_unique($roles);
    }

    public function setCompany(Company $company = null)
    {
        $this->company = $company;
    }

    public function getCompany()
    {
        return $this->company;
    }

    public function getCompanyId()
    {
        if ($this->company)
            return $this->company->getId();

        return null;
    }

    public function setRegistrationToken($token)
    {
        $this->registrationToken = $token;
    }

    public function getRegistrationToken()
    {
        return $this->registrationToken;
    }

    public function setResetPasswordToken($token)
    {
        $this->resetPasswordToken = $token;
    }

    public function getResetPasswordToken()
    {
        return $this->resetPasswordToken;
    }

    public function hasSameCompanyAs(User $user)
    {
        return $this->getCompanyId() === $user->getCompanyId();
    }

    public function isSameAs(User $user)
    {
        return $this->getId() === $user->getId();
    }

    public function isAdminFor($companyId)
    {
        return $this->isAdmin() && ($this->getCompanyId() == $companyId);
    }

    public function updateExtraFields($extraFields)
    {
    }

}