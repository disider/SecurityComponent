<?php

namespace SecurityComponent\Interactor\Presenter;

use SecurityComponent\Interactor\Presenter;

interface FindCategoriesPresenter extends Presenter
{
    /** @return array */
    public function getCategories();

    public function setCategories(array $categories);

    /** @return int */
    public function getTotalCategories();

    public function setTotalCategories($total);
}