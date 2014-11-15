<?php

namespace SecurityComponent\Interactor;

use SecurityComponent\Gateway\CategoryGateway;
use SecurityComponent\Gateway\UserGateway;
use SecurityComponent\Interactor\Presenter;
use SecurityComponent\Interactor\Presenter\DeleteCategoryPresenter;
use SecurityComponent\Interactor\Presenter\CategoryPresenter;
use SecurityComponent\Interactor\Request;
use SecurityComponent\Interactor\Request\DeleteCategoryRequest;

class DeleteCategoryInteractor implements Interactor
{
    /** @var UserGateway */
    private $userGateway;

    /** @var CategoryGateway  */
    private $categoryGateway;

    public function __construct(CategoryGateway $categoryGateway, UserGateway $userGateway)
    {
        $this->userGateway = $userGateway;
        $this->categoryGateway = $categoryGateway;
    }

    public function process(Request $request, Presenter $presenter)
    {
        /** @var DeleteCategoryRequest $request */
        /** @var CategoryPresenter $presenter */

        if ($request->superadminId === null) {
            $presenter->setErrors(array(CategoryPresenter::UNDEFINED_SUPERADMIN_ID));
            return;
        }

        $superadmin = $this->userGateway->findOneById($request->superadminId);
        if (!$superadmin->isSuperadmin()) {
            $presenter->setErrors(array(CategoryPresenter::FORBIDDEN));
            return;
        }

        $category = $this->categoryGateway->findOneById($request->id);

        if ($category == null) {
            $presenter->setErrors(array(CategoryPresenter::UNDEFINED_CATEGORY_ID));
            return;
        }

        $this->categoryGateway->delete($category->getId());

        $presenter->setCategory($category);
    }
}