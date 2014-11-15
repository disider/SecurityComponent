<?php

namespace SecurityComponent\Model;

class RunningChecklist
{
    const IDLE = 'idle';
    const STARTED = 'started';
    const COMPLETED = 'completed';

    /** @var int */
    private $id;

    /** @var ChecklistTemplate */
    private $checklistTemplate;

    /** @var string */
    private $type;

    /** @var string */
    private $title;

    /** @var string */
    private $subtitle;

    /** @var array */
    private $assignees = array();

    /** @var array */
    private $tasks = array();

    public function __construct($id, ChecklistTemplate $checklistTemplate, $subtitle = '')
    {
        $this->id = $id;
        $this->checklistTemplate = $checklistTemplate;
        $this->type = $checklistTemplate->getType();
        $this->title = $checklistTemplate->getTitle();
        $this->subtitle = $subtitle;
    }

    public static function getStatuses()
    {
        return array(
            self::IDLE,
            self::STARTED,
            self::COMPLETED
        );
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

    public function getChecklistTemplate()
    {
        return $this->checklistTemplate;
    }

    public function getChecklistTemplateId()
    {
        return $this->checklistTemplate->getId();
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getSubtitle()
    {
        return $this->subtitle;
    }

    public function setSubtitle($subtitle)
    {
        $this->subtitle = $subtitle;
    }

    public function getTasks()
    {
        return $this->tasks;
    }

    public function setAssignees(array $assignees)
    {
        $this->assignees = $assignees;
    }

    public function getAssignees()
    {
        return $this->assignees;
    }

    public function assignTo(User $user)
    {
        $this->assignees[] = $user;
    }

    public function hasAssigneeId($assigneeId)
    {
        /** @var User $assignee */
        foreach ($this->assignees as $assignee)
            if ($assignee->getId() == $assigneeId)
                return true;

        return false;
    }

    public function hasTaskId($taskId)
    {
        /** @var RunningTask $task */
        foreach ($this->tasks as $task)
            if ($task->getId() == $taskId)
                return true;

        return false;
    }

    public function getTaskById($taskId)
    {
        /** @var RunningTask $task */
        foreach ($this->tasks as $task)
            if ($task->getId() == $taskId)
                return $task;

        throw new UndefinedRunningTask;
    }

    public function addTask(RunningTask $task)
    {
        $this->tasks[] = $task;
    }

    protected function totalTasks()
    {
        return count($this->tasks);
    }

    public function totalCompletedTasks()
    {
        $count = 0;
        foreach ($this->tasks as $task)
            if ($task->isChecked())
                $count++;

        return $count;
    }

    public function completionPercent()
    {
        if($this->totalTasks() == 0)
            return 100.00;

        return sprintf('%.2f', ($this->totalCompletedTasks() / $this->totalTasks()) * 100);
    }

    public function getStatus()
    {
        $started = false;
        $completed = true;

        /** @var RunningTask $task */
        foreach ($this->tasks as $task) {
            if (!$task->isChecked())
                $completed = false;

            if ($task->isChecked())
                $started = true;
        }

        if ($completed)
            return self::COMPLETED;

        if ($started)
            return self::STARTED;

        return self::IDLE;
    }

    public function setTasks(array $tasks)
    {
        $this->tasks = $tasks;
    }

    public function checkTask(User $user, $taskId)
    {
        /** @var RunningTask $task */
        foreach ($this->tasks as $task) {
            if ($task->getId() == $taskId)
                $task->check($user, new \DateTime());
        }
    }

    public function uncheckTask($taskId)
    {
        /** @var RunningTask $task */
        foreach ($this->tasks as $task) {
            if ($task->getId() == $taskId)
                $task->uncheck();
        }
    }

    public function hasSameCompanyAs(User $user)
    {
        return $this->checklistTemplate->hasSameCompanyAs($user);
    }

    public function countVideos()
    {
        $count = 0;

        /** @var RunningTask $task */
        foreach ($this->tasks as $task)
            if ($task->hasVideoRef())
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

        /** @var RunningTask $task */
        foreach ($this->tasks as $task)
            if ($task->hasImageUrl())
                ++$count;

        return $count;
    }

    public function hasImages()
    {
        return $this->countImages() > 0;
    }

    public function isSequential()
    {
        return $this->type == ChecklistType::SEQUENTIAL;
    }

    public function getOwnerId()
    {
        return $this->checklistTemplate->getOwnerId();
    }

    public function getCompanyId()
    {
        return $this->checklistTemplate->getCompanyId();
    }
}

class UndefinedRunningTask extends \Exception
{

}