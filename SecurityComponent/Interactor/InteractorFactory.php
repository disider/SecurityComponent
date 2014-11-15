<?php

namespace SecurityComponent\Interactor;

use Exception;
use SecurityComponent\Gateway\CategoryGateway;
use SecurityComponent\Gateway\ChecklistTemplateGateway;
use SecurityComponent\Gateway\CompanyGateway;
use SecurityComponent\Gateway\LogGateway;
use SecurityComponent\Gateway\RunningChecklistGateway;
use SecurityComponent\Gateway\ShareRequestGateway;
use SecurityComponent\Gateway\UserGateway;
use SecurityComponent\Logger\Logger;

class InteractorFactory
{
    const FIND_COMPANIES = 'find_companies';
    const GET_COMPANY = 'get_company';
    const SAVE_COMPANY = 'save_company';
    const DELETE_COMPANY = 'delete_company';

    const REGISTER_USER = 'register_user';
    const CONFIRM_USER_REGISTRATION = 'confirm_user_registration';
    const REQUEST_RESET_PASSWORD = 'request_reset_password';
    const RESET_PASSWORD = 'reset_password';
    
    const FIND_USERS = 'find_users';
    const GET_USER = 'get_user';
    const SAVE_USER = 'save_user';
    const DELETE_USER = 'delete_user';

    /** @var CompanyGateway */
    private $companyGateway;

    /** @var UserGateway */
    private $userGateway;

    public function __construct(CompanyGateway $companyGateway, UserGateway $userGateway)
    {
        $this->companyGateway = $companyGateway;
        $this->userGateway = $userGateway;
    }

    /**
     *
     */
    public function get($type)
    {
        switch($type) {
            case self::FIND_COMPANIES:
                return new FindCompaniesInteractor($this->companyGateway, $this->userGateway);
            case self::GET_COMPANY:
                return new GetCompanyInteractor($this->companyGateway, $this->userGateway);
            case self::SAVE_COMPANY:
                return new SaveCompanyInteractor($this->companyGateway, $this->userGateway);
            case self::DELETE_COMPANY:
                return new DeleteCompanyInteractor($this->companyGateway, $this->userGateway);

            case self::REGISTER_USER:
                return new RegisterUserInteractor($this->userGateway);
            case self::CONFIRM_USER_REGISTRATION:
                return new ConfirmUserRegistrationInteractor($this->userGateway);

            case self::REQUEST_RESET_PASSWORD:
                return new RequestResetPasswordInteractor($this->userGateway);
            case self::RESET_PASSWORD:
                return new ResetPasswordInteractor($this->userGateway);

            case self::FIND_USERS:
                return new FindUsersInteractor($this->userGateway);
            case self::GET_USER:
                return new GetUserInteractor($this->userGateway);
            case self::SAVE_USER:
                return new SaveUserInteractor($this->userGateway, $this->companyGateway);
            case self::DELETE_USER:
                return new DeleteUserInteractor($this->userGateway);

            default:
                throw new UndefinedInteractorException($type);
        }
    }
}

class UndefinedInteractorException extends \Exception
{
    public function __construct($type = "")
    {
        parent::__construct('Undefined interactor type: ' . $type);
    }
}