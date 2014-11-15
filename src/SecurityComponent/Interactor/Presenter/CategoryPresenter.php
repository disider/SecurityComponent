<?php

namespace SecurityComponent\Interactor\Presenter;

use SecurityComponent\Interactor\Presenter;
use SecurityComponent\Model\Category;

interface CategoryPresenter extends Presenter
{
    const EMPTY_NAME = 'empty_name';
    const UNDEFINED_SUPERADMIN_ID = 'undefined_superadmin_id';
    const UNDEFINED_CATEGORY_ID = 'undefined_category_id';
    const UNDEFINED_CATEGORY = 'undefined_category';

    public function getCategory();

    public function setCategory(Category $category);
}