<?php

namespace SecurityComponent\Model;

class RunningTask
{
    /** @var int */
    private $id;

    /** @var int */
    private $checklistId;

    /** @var string */
    private $title;

    /** @var string */
    private $description;

    /** @var string */
    private $videoRef;

    /** @var string */
    private $imageUrl;

    /** @var \DateTime */
    private $checkedAt;

    /** @var User */
    private $checkedBy;

    public function __construct($id, $checklistId, $title, \DateTime $checkedAt = null, User $checkedBy = null)
    {
        $this->id = $id;
        $this->checklistId = $checklistId;
        $this->title = $title;
        $this->checkedAt = $checkedAt;
        $this->checkedBy = $checkedBy;
    }

    public function __toString()
    {
        return $this->title;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getRunningChecklistId()
    {
        return $this->checklistId;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function isChecked()
    {
        return $this->checkedAt != null;
    }

    public function check(User $checkedBy, \DateTime $checkedAt = null)
    {
        if(!$this->isChecked()) {
            $this->checkedBy = $checkedBy;
            $this->checkedAt = $checkedAt;
        }
    }

    public function uncheck()
    {
        $this->checkedBy = null;
        $this->checkedAt = null;
    }

    public function getCheckedAt()
    {
        return $this->checkedAt;
    }

    public function getCheckedBy()
    {
        return $this->checkedBy;
    }

    public function getCheckedById()
    {
        return $this->checkedBy ? $this->checkedBy->getId() : null;
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