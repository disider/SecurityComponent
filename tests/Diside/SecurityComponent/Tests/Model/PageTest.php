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
        $page = $this->givenPage();

        $this->assertThat($page->getLanguage(), $this->equalTo('en'));
        $this->assertThat($page->getUrl(), $this->equalTo('url'));
        $this->assertThat($page->getTitle(), $this->equalTo('title'));
        $this->assertThat($page->getContent(), $this->equalTo('content'));
        $this->assertThat($page->countTranslations(), $this->equalTo(0));
        $this->assertThat($page->getTranslations(), $this->equalTo(array()));
    }

    /**
     * @test
     */
    public function testAddTranslation()
    {
        $page = $this->givenPage();
        $page->addTranslation(new PageTranslation(null, 'it', '/it/titolo', 'Titolo', 'Descrizione'));

        $this->assertTrue($page->hasTranslation('it'));
        $this->assertFalse($page->hasTranslation('es'));
        $this->assertThat($page->countTranslations(), $this->equalTo(1));

        $translations = $page->getTranslations();

        $this->assertThat(count($translations), $this->equalTo(1));
        $this->assertNotNull($page->getTranslation('it'));
    }

    /**
     * @test
     * @expectedException \Diside\SecurityComponent\Exception\UndefinedTranslationException
     */
    public function testGetTranslation()
    {
        $page = $this->givenPage();
        $page->getTranslation('unknown');
    }

    /**
     * @return Page
     */
    protected function givenPage()
    {
        $page = new Page(null, 'en', 'url', 'title', 'content');

        return $page;
    }

}