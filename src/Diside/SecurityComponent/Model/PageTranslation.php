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
    private $description;

    public function __construct($id, $language, $title, $url, $description)
    {
        $this->id = $id;
        $this->language = $language;
        $this->title = $title;
        $this->url = $url;
        $this->description = $description;
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

}