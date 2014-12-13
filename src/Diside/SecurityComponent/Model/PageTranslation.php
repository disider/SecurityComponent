<?php

namespace Diside\SecurityComponent\Model;

class PageTranslation
{
    /** @var int */
    private $id;

    /** @var string */
    private $language;

    /** @var string */
    private $title;

    /** @var string */
    private $url;

    /** @var string */
    private $content;

    public function __construct($id, $language, $url, $title, $content)
    {
        $this->id = $id;
        $this->language = $language;
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

    public function getLanguage()
    {
        return $this->language;
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