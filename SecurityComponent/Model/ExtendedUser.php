<?php

namespace SecurityComponent\Model;

class ExtendedUser extends User
{
    const ROLE_FREE_USER = 'ROLE_FREE_USER';
    const DEFAULT_MAXIMUM_CHECKLIST_TEMPLATES = 5;

    const MAX_CHECKLIST_TEMPLATES = 'maxChecklistTemplates';

    /** @var array */
    private $countChecklistTemplates = 0;

    /** @var array */
    private $countRunningChecklists = 0;

    /** @var array */
    private $countShareRequests = 0;

    /** @var int */
    private $maximumChecklistTemplates = self::DEFAULT_MAXIMUM_CHECKLIST_TEMPLATES;

    public static function getUserRoles()
    {
        return array(
            self::ROLE_USER,
            self::ROLE_MANAGER,
            self::ROLE_ADMIN,
        );
    }

    public static function getSuperadminRoles()
    {
        return array_merge(parent::getSuperadminRoles(), array(
            self::ROLE_FREE_USER,
        ));
    }

    public function setCountChecklistTemplates($total)
    {
        $this->countChecklistTemplates = $total;
    }

    public function countChecklistTemplates()
    {
        return $this->countChecklistTemplates;
    }

    public function setCountRunningChecklists($total)
    {
        $this->countRunningChecklists = $total;
    }

    public function countRunningChecklists()
    {
        return $this->countRunningChecklists;
    }

    public function setMaxChecklistTemplates($max)
    {
        $this->maximumChecklistTemplates = $max;
    }

    public function getMaxChecklistTemplates()
    {
        return $this->maximumChecklistTemplates;
    }

    public function isSharing(ChecklistTemplate $template)
    {
        return $template->hasSharingUserId($this->getId());
    }

    public function setCountShareRequests($count)
    {
        $this->countShareRequests = $count;
    }

    public function countShareRequests()
    {
        return $this->countShareRequests;
    }

    public function hasShareRequests()
    {
        return $this->countShareRequests > 0;
    }

    public function updateExtraFields($extraFields)
    {
        if(array_key_exists(self::MAX_CHECKLIST_TEMPLATES, $extraFields))
            $this->maximumChecklistTemplates = $extraFields[self::MAX_CHECKLIST_TEMPLATES];
     }
}