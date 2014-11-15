<?php

namespace SecurityComponent\Interactor\Presenter;

use SecurityComponent\Interactor\Presenter;
use SecurityComponent\Model\User;

interface UserPresenter extends Presenter
{
    const EMPTY_EMAIL = 'empty_email';
    const EMPTY_PASSWORD = 'empty_password';
    const UNDEFINED_USER = 'undefined_user';
    const EMAIL_ALREADY_DEFINED = 'email_already_defined';
    const EMPTY_REGISTRATION_TOKEN = 'empty_registration_token';
    const EMPTY_RESET_PASSWORD_TOKEN = 'empty_reset_password_token';
    const WRONG_PASSWORD = 'wrong_password';
    const EMPTY_CURRENT_PASSWORD = 'empty_current_password';
    const EMPTY_NEW_PASSWORD = 'empty_new_password';
    const UNDEFINED_COMPANY = 'undefined_company';

    public function getUser();

    public function setUser(User $user);
}