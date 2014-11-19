<?php

namespace Diside\SecurityComponent\Interactor\Presenter;

use Diside\SecurityComponent\Interactor\Presenter;

interface UsersPresenter extends Presenter
{
    /** @return array */
    public function getUsers();

    public function setUsers(array $users);

    /** @return int */
    public function getTotalUsers();

    public function setTotalUsers($total);
}