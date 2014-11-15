<?php

namespace SecurityComponent\Interactor;

use SecurityComponent\Gateway\CategoryGateway;
use SecurityComponent\Interactor\Presenter;
use SecurityComponent\Interactor\Presenter\CategoryPresenter;
use SecurityComponent\Interactor\Request;
use SecurityComponent\Interactor\Request\GetCategoryByEmailRequest;
use SecurityComponent\Interactor\Request\GetCategoryByIdRequest;
use SecurityComponent\Interactor\Request\GetCategoryRequest;

class GetCategoryInteractor implements Interactor
{

    /** @var CategoryGateway */
    private $categoryGateway;

    public function __construct(CategoryGateway $categoryGateway)
    {
        $this->categoryGateway = $categoryGateway;
    }

    public function process(Request $request, Presenter $presenter)
    {
        /** @var GetCategoryRequest $request */
        /** @var CategoryPresenter $presenter */

        if ($request->id === null) {
            $presenter->setErrors(array(CategoryPresenter::UNDEFINED_CATEGORY_ID));
            return;
        }

        $category = $this->categoryGateway->findOneById($request->id);

        if ($category == null) {
            $presenter->setErrors(array(CategoryPresenter::UNDEFINED_CATEGORY));
            return;
        }

        $presenter->setCategory($category);
    }
}