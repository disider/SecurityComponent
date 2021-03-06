<?php

namespace Diside\SecurityComponent\Tests\Model;

use Diside\SecurityComponent\Model\Company;

class CompanyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testConstructor()
    {
        $company = new Company(null, 'Acme');

        $this->assertNull($company->getId());
        $this->assertThat($company->getName(), $this->equalTo('Acme'));
    }
}