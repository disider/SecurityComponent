<?php

namespace Diside\SecurityComponent\Interactor\Request;

use Diside\SecurityComponent\Interactor\Request;

class SavePageRequest implements Request
{
    /** @var */
    public $executorId;

    /** @var int */
    public $id;

    /** @var array */
    public $translations = array();

    public function __construct($executorId, $id)
    {
        $this->executorId = $executorId;
        $this->id = $id;
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