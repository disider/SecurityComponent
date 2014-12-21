<?php

namespace Diside\SecurityComponent\Model;

use Diside\SecurityComponent\Exception\UndefinedTranslationException;

class Page extends PageTranslation
{
    /** @var array */
    private $translations = array();

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

    public function hasTranslation($locale)
    {
        if($locale == $this->getLocale())
            return true;

        /** @var PageTranslation $translation */
        foreach ($this->translations as $translation) {
            if ($translation->getLocale() == $locale) {
                return true;
            }
        }

        return false;
    }

    public function getTranslation($locale)
    {
        if($locale == $this->getLocale())
            return $this;

        /** @var PageTranslation $translation */
        foreach ($this->translations as $translation)
            if ($translation->getLocale() == $locale)
                return $translation;

        throw new UndefinedTranslationException('Page has no translation for ' . $locale);
    }

}