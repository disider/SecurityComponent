<?php

namespace SecurityComponent\Interactor\Presenter;

use SecurityComponent\Interactor\Presenter;

interface FindUsersPresenter extends Presenter
{
    /** @return array */
    public function getUsers();

    public function setUsers(array $users);

    /** @return int */
    public function getTotalUsers();

    public function setTotalUsers($total);
}