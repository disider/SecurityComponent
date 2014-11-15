<?php

namespace SecurityComponent\Interactor\Presenter;

use SecurityComponent\Interactor\Presenter;
use SecurityComponent\Model\ChecklistTemplate;

interface ChecklistTemplatePresenter extends Presenter
{
    const EMPTY_CHECKLIST_TEMPLATE_TITLE = 'empty_checklist_template_title';
    const EMPTY_TASK_TEMPLATE_TITLE = 'empty_task_template_title';
    const EMPTY_TASK_TEMPLATE_POSITION = 'empty_task_template_position';
    const UNDEFINED_CHECKLIST_TEMPLATE_ID = 'undefined_checklist_template_id';
    const MAX_CHECKLIST_TEMPLATES_REACHED = 'max_checklist_templates_reached';

    public function getChecklistTemplate();

    public function setChecklistTemplate(ChecklistTemplate $template);
}