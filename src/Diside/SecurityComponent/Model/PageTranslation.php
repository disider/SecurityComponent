<?php

namespace Diside\SecurityComponent\Model;

class PageTranslation
{
    /** @var int */
    private $id;

    /** @var string */
    private $locale;

    /** @var string */
    private $title;

    /** @var string */
    private $url;

    /** @var string */
    private $content;

    public function __construct($id, $locale, $url, $title, $content)
    {
        $this->id = $id;
        $this->locale = $locale;
        $this->url = $url;
        $this->title = $title;
        $this->content = $content;
    }

    public function __toString()
    {
        return $this->getTitle();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getLocale()
    {
        return $this->locale;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getContent()
    {
        return $this->content;
    }

}