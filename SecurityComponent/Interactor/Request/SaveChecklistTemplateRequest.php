<?php

namespace SecurityComponent\Interactor\Request;

use SecurityComponent\Interactor\Request;

class SaveChecklistTemplateRequest implements Request
{
    /** @var string */
    public $id;

    /** @var string */
    public $title;

    /** @var int */
    public $userId;

    /** @var array */
    public $tasks = array();

    /** @var int */
    public $categoryId;

    /** @var bool */
    public $isSequential;

    public function __construct($userId, $id, $title, $categoryId = null, $isSequential = true)
    {
        $this->userId = $userId;
        $this->id = $id;
        $this->title = $title;
        $this->categoryId = $categoryId;
        $this->isSequential = $isSequential;
    }

    public function addTask($id, $position, $title, $description = null, $videoRef = null, $imageUrl = null)
    {
        $task = new \stdClass;
        $task->id = $id;
        $task->position = $position;
        $task->title = $title;
        $task->description = $description;
        $task->videoRef = $videoRef;
        $task->imageUrl = $imageUrl;

        $this->tasks[] = $task;
    }

}