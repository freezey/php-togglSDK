<?php
class TogglWorkspaceCache extends TogglSmartCache{

    public function getDataFromSource(){
        $workspaces = TogglWorkspace::getWorkspaces();
        return $workspaces;
    }
}