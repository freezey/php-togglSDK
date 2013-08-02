<?php
class TogglProjectCache extends TogglSmartCache{

    public function __construct($workspace_id){
        $this->workspace_id = $workspace_id;
        parent::__construct('projects_' . $workspace_id, 60);
    }

    public function getDataFromSource(){

        $projects = TogglWorkspace::getWorkspaceProjects($this->workspace_id);
        return $projects;
    }
}