<?php

namespace Diside\SecurityComponent\Tests\Model;

use Diside\SecurityComponent\Model\Page;
use Diside\SecurityComponent\Model\PageTranslation;

class PageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testConstructor()
    {
        $page = new Page(null);

        $this->assertThat($page->countTranslation(), $this->equalTo(0));
    }

    /**
     * @test
     */
    public function testAddTranslation()
    {
        $page = new Page(null);
        $page->addTranslation(new PageTranslation(null, 'en', '/en/title', 'Title', 'Description'));

        $this->assertTrue($page->hasTranslation('en'));
        $this->assertFalse($page->hasTranslation('it'));
        $this->assertThat($page->countTranslation(), $this->equalTo(1));
    }

}