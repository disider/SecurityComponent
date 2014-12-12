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

        $this->assertThat($page->countTranslations(), $this->equalTo(0));
        $this->assertThat($page->getTranslations(), $this->equalTo(array()));
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
        $this->assertThat($page->countTranslations(), $this->equalTo(1));

        $translations = $page->getTranslations();

        $this->assertThat(count($translations), $this->equalTo(1));
    }

}