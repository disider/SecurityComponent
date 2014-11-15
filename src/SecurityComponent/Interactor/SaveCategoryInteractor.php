<?php

namespace SecurityComponent\Interactor;

use SecurityComponent\Gateway\CategoryGateway;
use SecurityComponent\Interactor\Presenter\CategoryPresenter;
use SecurityComponent\Interactor\Request\SaveCategoryRequest;
use SecurityComponent\Interactor\Presenter;
use SecurityComponent\Interactor\Request;
use SecurityComponent\Model\Category;

class SaveCategoryInteractor implements Interactor
{
    /** @var CategoryGateway */
    private $categoryGateway;

    public function __construct(CategoryGateway $categoryGateway)
    {
        $this->categoryGateway = $categoryGateway;
    }

    public function process(Request $request, Presenter $presenter)
    {
        /** @var SaveCategoryRequest $request */
        /** @var CategoryPresenter $presenter */

        if (!$this->validate($request, $presenter)) {
            return;
        }

        $category = new Category($request->id, $request->name);

        $category = $this->categoryGateway->save($category);

        $presenter->setCategory($category);
    }

    private function validate(Request $request, CategoryPresenter $presenter)
    {
        if ($request->name === null) {
            $error = CategoryPresenter::EMPTY_NAME;
            $presenter->setErrors(array($error));
            return false;
        }

        return true;
    }
}