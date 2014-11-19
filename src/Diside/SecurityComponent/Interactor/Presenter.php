<?php

namespace Diside\SecurityComponent\Interactor;

interface Presenter
{
    const BAD_REQUEST = 'bad_request';
    const UNAUTHORIZED = 'unauthorized';
    const FORBIDDEN = 'forbidden';
    const NOT_FOUND = 'not_found';
    const UNDEFINED_USER_ID = 'undefined_user_id';

    public function hasErrors();

    public function getErrors();

    public function setErrors(array $errors);
}