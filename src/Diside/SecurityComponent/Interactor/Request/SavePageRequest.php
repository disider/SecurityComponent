<?php

namespace Diside\SecurityComponent\Interactor\Request;

use Diside\SecurityComponent\Interactor\Request;

class SavePageRequest implements Request
{
    /** @var */
    public $executorId;

    /** @var int */
    public $id;

    /** @var string */
    public $language;

    /** @var string */
    public $url;

    /** @var string */
    public $title;

    /** @var string */
    public $content;

    /** @var array */
    public $translations = array();

    public function __construct($executorId, $id, $language, $url, $title, $content)
    {
        $this->executorId = $executorId;
        $this->id = $id;
        $this->language = $language;
        $this->url = $url;
        $this->title = $title;
        $this->content = $content;
    }

    public function addTranslation($id, $language, $url, $title, $content)
    {
        $translation = new \stdClass();
        $translation->id = $id;
        $translation->language = $language;
        $translation->url = $url;
        $translation->title = $title;
        $translation->content = $content;

        $this->translations[] = $translation;
    }

}