<?php


namespace SecurityComponent\Model;

use SecurityComponent\Exception\InvalidTaskTemplateException;

class TaskTemplate {

    /** @var int */
    private $id;

    /** @var int */
    private $position;

    /** @var string */
    private $title;

    /** @var string */
    private $videoRef;

    /** @var string */
    private $imageUrl;

    /** @var string */
    private $description;

    public function __construct($id, $position, $title)
    {
        $this->id = $id;
        $this->position = $position;
        $this->title = $title;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }


    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function getVideoRef()
    {
        return $this->videoRef;
    }

    public function setVideoRef($videoRef)
    {
        $this->videoRef = $videoRef;
    }

    public function hasVideoRef()
    {
        return $this->videoRef != null;
    }

    public function getImageUrl()
    {
        return $this->imageUrl;
    }
    public function setImageUrl($imageUrl)
    {
        $this->imageUrl = $imageUrl;
    }

    public function hasImageUrl()
    {
        return $this->imageUrl != null;
    }
}