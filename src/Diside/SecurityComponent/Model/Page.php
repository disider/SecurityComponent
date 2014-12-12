<?php

namespace Diside\SecurityComponent\Model;

use Diside\SecurityComponent\Exception\UndefinedTranslationException;

class Page
{
    /** @var int */
    private $id;

    /** @var array */
    private $translations = array();

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getTranslations()
    {
        return $this->translations;
    }

    public function countTranslations()
    {
        return count($this->translations);
    }

    public function addTranslation(PageTranslation $translation)
    {
        $this->translations[] = $translation;
    }

    public function hasTranslation($language)
    {
        /** @var PageTranslation $translation */
        foreach ($this->translations as $translation) {
            if ($translation->getLanguage() == $language) {
                return true;
            }
        }

        return false;
    }

    public function getTranslation($language)
    {
        /** @var PageTranslation $translation */
        foreach ($this->translations as $translation) {
            if ($translation->getLanguage() == $language) {
                return $translation;
            }
        }

        throw new UndefinedTranslationException('Page has no translation for ' . $language);
    }

}