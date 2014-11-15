<?php

namespace SecurityComponent\Model;

class ChecklistTemplate
{
    /** @var int */
    private $id;

    /** @var string */
    private $title;

    /** @var Category */
    private $category;

    /** @var User */
    private $owner;

    /** @var array */
    private $taskTemplates = array();

    /** @var array */
    private $runningChecklists = array();

    /** @var array */
    private $sharingUsers = array();

    /** @var string */
    private $type = ChecklistType::SEQUENTIAL;

    public function __construct($id, User $owner, $title)
    {
        $this->id = $id;
        $this->owner = $owner;
        $this->title = $title;
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

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function setCategory($category)
    {
        $this->category = $category;
    }

    public function hasCategory()
    {
        return $this->category != null;
    }

    public function getOwner()
    {
        return $this->owner;
    }

    public function getCompany()
    {
        return $this->owner->getCompany();
    }

    public function getCategoryId()
    {
        return $this->category ? $this->category->getId() : null;
    }

    public function getOwnerId()
    {
        return $this->owner ? $this->owner->getId() : null;
    }

    public function getTaskTemplates()
    {
        return $this->taskTemplates;
    }

    public function setTaskTemplates(array $taskTemplates)
    {
        $this->taskTemplates = $taskTemplates;
    }

    public function hasTaskTemplateId($id)
    {
        foreach ($this->taskTemplates as $task) {
            if ($task->getId() == $id) {
                return true;
            }
        }
        return false;
    }

    public function addTaskTemplate(TaskTemplate $template)
    {
        $this->taskTemplates[$template->getPosition()] = $template;
    }

    public function countTaskTemplates()
    {
        return count($this->taskTemplates);
    }

    public function countRunningChecklists()
    {
        return count($this->runningChecklists);
    }

    public function addRunningChecklist(RunningChecklist $checklist)
    {
        $this->runningChecklists[] = $checklist;
    }

    public function hasSameCompanyAs(User $user)
    {
        return $this->owner->hasSameCompanyAs($user);
    }

    public function countVideos()
    {
        $count = 0;

        /** @var TaskTemplate $taskTemplate */
        foreach($this->taskTemplates as $taskTemplate)
            if($taskTemplate->hasVideoRef())
                ++$count;

        return $count;
    }

    public function hasVideos()
    {
        return $this->countVideos() > 0;
    }

    public function countImages()
    {
        $count = 0;

        /** @var TaskTemplate $taskTemplate */
        foreach($this->taskTemplates as $taskTemplate)
            if($taskTemplate->hasImageUrl())
                ++$count;

        return $count;
    }

    public function hasImages()
    {
        return $this->countImages() > 0;
    }

    public function getRunningChecklists()
    {
        return $this->runningChecklists;
    }

    public function isSequential()
    {
        return $this->type == ChecklistType::SEQUENTIAL;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function addSharingUser(User $user)
    {
        $this->sharingUsers[] = $user;
    }

    public function countSharingUsers()
    {
        return count($this->sharingUsers);
    }

    public function getSharingUsers()
    {
        return $this->sharingUsers;
    }

    public function hasSharingUserId($userId)
    {
        /** @var User $sharingUser */
        foreach($this->sharingUsers as $sharingUser)
            if($sharingUser->getId() == $userId)
                return true;

        return false;
    }

    public function getCompanyId()
    {
        return $this->getCompany() ? $this->getCompany()->getId() : null;
    }
}