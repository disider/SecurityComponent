<?php

namespace Diside\SecurityComponent\Tests\Interactor\Interactor;

use Diside\SecurityComponent\Interactor\Interactor\FindPagesInteractor;
use Diside\SecurityComponent\Interactor\Presenter\PagesPresenter;
use Diside\SecurityComponent\Interactor\Request\FindPagesRequest;
use Diside\SecurityComponent\Model\User;

class FindPagesInteractorTest extends BasePageInteractorTest
{
    /** @var FindPagesInteractor */
    private $interactor;

    /**
     * @before
     */
    public function setUp()
    {
        parent::setUp();

        $this->interactor = new FindPagesInteractor($this->gatewayRegistry, $this->logger);
    }

    /**
     * @test
     */
    public function testWhenThereAreNoPages_thenReturnEmptyList()
    {
        $user = $this->givenUser();
        $request = new FindPagesRequest($user->getId());

        $this->interactor->process($request, $this->presenter);

        $pages = $this->presenter->getPages();

        $this->assertThat(count($pages), $this->equalTo(0));
    }

    /**
     * @test
     */
    public function testPagination()
    {
        $user = $this->givenUser();

        $total = 10;

        $this->givenPages($total);

        $this->assertPage($user, 0, 5, 5, $total);
        $this->assertPage($user, 1, 5, 5, $total);
        $this->assertPage($user, 2, 5, 0, $total);
    }

    private function givenPages($number)
    {
        for ($i = 0; $i < $number; ++$i) {
            $this->givenPage('en', '/page' . $i);
        }
    }

    private function assertPage(User $user, $start, $end, $value, $total)
    {
        $request = new FindPagesRequest($user->getId(), $start, $end);

        $this->interactor->process($request, $this->presenter);

        $this->assertThat(count($this->presenter->getPages()), $this->equalTo($value));
        $this->assertThat($this->presenter->getTotalPages(), $this->equalTo($total));
    }

    protected function buildPresenter()
    {
        return new FindPagesPresenterMock();
    }
}

class FindPagesPresenterMock implements PagesPresenter
{
    private $total;
    private $errors;
    private $users;

    public function getPages()
    {
        return $this->users;
    }

    public function setPages(array $users)
    {
        $this->users = $users;
    }

    public function hasErrors()
    {
        return $this->errors != null;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function setErrors(array $errors)
    {
        $this->errors = $errors;
    }

    /** @return int */
    public function getTotalPages()
    {
        return $this->total;
    }

    public function setTotalPages($total)
    {
        $this->total = $total;
    }
}
