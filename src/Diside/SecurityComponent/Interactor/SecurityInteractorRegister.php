<?php

namespace Diside\SecurityComponent\Interactor;

class SecurityInteractorRegister extends InteractorRegister
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

    const FIND_LOGS = 'find_logs';

    const FIND_PAGES = 'find_pages';
    const GET_PAGE = 'get_page';
    const SAVE_PAGE = 'save_page';
    const DELETE_PAGE = 'delete_page';

    public function __construct()
    {
        $this->register(self::FIND_COMPANIES, '\Diside\SecurityComponent\Interactor\Interactor\FindCompaniesInteractor');
        $this->register(self::GET_COMPANY, '\Diside\SecurityComponent\Interactor\Interactor\GetCompanyInteractor');
        $this->register(self::SAVE_COMPANY, '\Diside\SecurityComponent\Interactor\Interactor\SaveCompanyInteractor');
        $this->register(self::DELETE_COMPANY, '\Diside\SecurityComponent\Interactor\Interactor\DeleteCompanyInteractor');

        $this->register(self::REGISTER_USER, '\Diside\SecurityComponent\Interactor\Interactor\RegisterUserInteractor');
        $this->register(self::CONFIRM_USER_REGISTRATION, '\Diside\SecurityComponent\Interactor\Interactor\ConfirmUserRegistrationInteractor');
        $this->register(self::REQUEST_RESET_PASSWORD, '\Diside\SecurityComponent\Interactor\Interactor\RequestResetPasswordInteractor');
        $this->register(self::RESET_PASSWORD, '\Diside\SecurityComponent\Interactor\Interactor\ResetPasswordInteractor');

        $this->register(self::FIND_USERS, '\Diside\SecurityComponent\Interactor\Interactor\FindUsersInteractor');
        $this->register(self::GET_USER, '\Diside\SecurityComponent\Interactor\Interactor\GetUserInteractor');
        $this->register(self::SAVE_USER, '\Diside\SecurityComponent\Interactor\Interactor\SaveUserInteractor');
        $this->register(self::DELETE_USER, '\Diside\SecurityComponent\Interactor\Interactor\DeleteUserInteractor');

        $this->register(self::FIND_LOGS, '\Diside\SecurityComponent\Interactor\Interactor\FindLogsInteractor');

        $this->register(self::FIND_PAGES, '\Diside\SecurityComponent\Interactor\Interactor\FindPagesInteractor');
        $this->register(self::GET_PAGE, '\Diside\SecurityComponent\Interactor\Interactor\GetPageInteractor');
        $this->register(self::SAVE_PAGE, '\Diside\SecurityComponent\Interactor\Interactor\SavePageInteractor');
        $this->register(self::DELETE_PAGE, '\Diside\SecurityComponent\Interactor\Interactor\DeletePageInteractor');
    }
}
