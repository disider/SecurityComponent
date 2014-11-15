<?php

namespace SecurityComponent\Interactor;


use SecurityComponent\Gateway\CategoryGateway;
use SecurityComponent\Gateway\UserGateway;
use SecurityComponent\Interactor\Presenter\FindCategoriesPresenter;
use SecurityComponent\Interactor\Request\FindCategoriesRequest;

class FindCategoriesInteractor implements Interactor
{
    /** @var CategoryGateway */
    private $categoryGateway;

    /** @var UserGateway */
    private $userGateway;

    public function __construct(CategoryGateway $categoryGateway, UserGateway $userGateway)
    {
        $this->categoryGateway = $categoryGateway;
        $this->userGateway = $userGateway;
    }

    public function process(Request $request, Presenter $presenter)
    {
        /** @var FindCategoriesRequest $request */
        /** @var FindCategoriesPresenter $presenter */

        $user = $this->userGateway->findOneById($request->userId);

        $filters = array();

        $categorys = $this->categoryGateway->findAll($filters, $request->pageIndex, $request->pageSize);

        $total = $this->categoryGateway->countAll();

        $presenter->setCategories($categorys);
        $presenter->setTotalCategories($total);
    }
}