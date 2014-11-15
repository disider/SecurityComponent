<?php

namespace SecurityComponent\Interactor;

use SecurityComponent\Gateway\CategoryGateway;
use SecurityComponent\Gateway\ChecklistTemplateGateway;
use SecurityComponent\Gateway\UserGateway;
use SecurityComponent\Interactor\Presenter\ChecklistTemplatePresenter;
use SecurityComponent\Interactor\Presenter\SaveChecklistTemplatePresenter;
use SecurityComponent\Interactor\Request\SaveChecklistTemplateRequest;
use SecurityComponent\Interactor\Presenter;
use SecurityComponent\Interactor\Request;
use SecurityComponent\Model\ChecklistTemplate;
use SecurityComponent\Model\ChecklistType;
use SecurityComponent\Model\TaskTemplate;

class SaveChecklistTemplateInteractor implements Interactor
{
    /** @var UserGateway */
    private $userGateway;

    /** @var CategoryGateway */
    private $categoryGateway;

    /** @var ChecklistTemplateGateway */
    private $checklistTemplateGateway;

    public function __construct(ChecklistTemplateGateway $checklistTemplateGateway, CategoryGateway $categoryGateway, UserGateway $userGateway)
    {
        $this->checklistTemplateGateway = $checklistTemplateGateway;
        $this->categoryGateway = $categoryGateway;
        $this->userGateway = $userGateway;
    }

    public function process(Request $request, Presenter $presenter)
    {
        /** @var SaveChecklistTemplateRequest $request */
        /** @var ChecklistTemplatePresenter $presenter */

        if (!$this->validate($request, $presenter)) {
            return;
        }

        $tasks = $this->buildTasks($request);

        $user = $this->userGateway->findOneById($request->userId);
        $category = $this->categoryGateway->findOneById($request->categoryId);

        if($request->id != null) {
            if($user->isSuperadmin() || $user->isAdmin())
                $template = $this->checklistTemplateGateway->findOneById($request->id);
            else
                $template = $this->checklistTemplateGateway->findOneByOwnerId($request->userId, $request->id);

            if(!$template) {
                $presenter->setErrors(array(ChecklistTemplatePresenter::NOT_FOUND));
                return;
            }

            if(!($user->isSuperadmin() || ($user->isAdmin() && $template->hasSameCompanyAs($user)) || $user->ownsTemplate($template))) {
                $presenter->setErrors(array(Presenter::FORBIDDEN));
                return;
            }

            $template->setTitle($request->title);
        }
        else {
            if($user->getMaxChecklistTemplates() <= $user->countChecklistTemplates()) {
                $presenter->setErrors(array(ChecklistTemplatePresenter::MAX_CHECKLIST_TEMPLATES_REACHED));
                return;
            }

            $template = new ChecklistTemplate($request->id, $user, $request->title);
        }

        $template->setCategory($category);
        $template->setTaskTemplates($tasks);
        $template->setType($request->isSequential ? ChecklistType::SEQUENTIAL : ChecklistType::NON_SEQUENTIAL);

        $template = $this->checklistTemplateGateway->save($template);

        $presenter->setChecklistTemplate($template);
    }

    private function validate(SaveChecklistTemplateRequest $request, ChecklistTemplatePresenter $presenter)
    {
        $errors = array();
        if ($request->title == null) {
            $error = ChecklistTemplatePresenter::EMPTY_CHECKLIST_TEMPLATE_TITLE;
            $errors[] = $error;
        }

        foreach ($request->tasks as $task) {
            if (empty($task->title)) {
                $error = ChecklistTemplatePresenter::EMPTY_TASK_TEMPLATE_TITLE;
                $errors[] = $error;
            }

            if ($task->position === null) {
                $error = ChecklistTemplatePresenter::EMPTY_TASK_TEMPLATE_POSITION;
                $errors[] = $error;
            }

        }

        if(count($errors) > 0) {
            $presenter->setErrors($errors);
            return false;
        }

        return true;
    }

    private function buildTasks(Request $request)
    {
        $tasks = array();

        foreach ($request->tasks as $task) {
            $template = new TaskTemplate($task->id, $task->position, $task->title);
            $template->setDescription($task->description);
            $template->setVideoRef($task->videoRef);
            $template->setImageUrl($task->imageUrl);

            $tasks[$task->position] = $template;
        }

        ksort($tasks);
        return $tasks;
    }
}