<?php 

class TogglWorkspace extends Toggl{
    public static $fields = array(
        "name", // (string, required)
        "premium", // If it's a pro workspace or not. Shows if someone is paying for the workspace or not (boolean, not required)
        "at", // timestamp that is sent in the response, indicates the time item was last updated
    );

    public static function getWorkspaces(array $params = array()){
        foreach ($params as $name => $param){
            if (array_search($name, self::$fields) === false){
                return "Invalid Parameter: $name";
            }
        }
        $params['method'] = "GET";
        $params['url'] = "https://www.toggl.com/api/v8/workspaces";
        return self::send($params);
    }

    public static function getWorkspaceUsers($workspace_id, array $params = array()){
        foreach ($params as $name => $param){
            if (array_search($name, self::$fields) === false){
                return "Invalid Parameter: $name";
            }
        }
        $params['method'] = "GET";
        $params['url'] = "https://www.toggl.com/api/v8/workspaces/$workspace_id/users";
        return self::send($params);
    }

    public static function getWorkspaceClients($workspace_id, array $params = array()){
        foreach ($params as $name => $param){
            if (array_search($name, self::$fields) === false){
                return "Invalid Parameter: $name";
            }
        }
        $params['method'] = "GET";
        $params['url'] = "https://www.toggl.com/api/v8/workspaces/$workspace_id/clients";
        return self::send($params);
    }

    public static function getWorkspaceProjects($workspace_id, array $params = array()){
        foreach ($params as $name => $param){
            if (array_search($name, self::$fields) === false){
                return "Invalid Parameter: $name";
            }
        }
        $params['method'] = "GET";
        $params['url'] = "https://www.toggl.com/api/v8/workspaces/$workspace_id/projects";
        return self::send($params);
    }

    public static function getWorkspaceTasks($workspace_id, array $params = array()){
        foreach ($params as $name => $param){
            if (array_search($name, self::$fields) === false){
                return "Invalid Parameter: $name";
            }
        }
        $params['method'] = "GET";
        $params['url'] = "https://www.toggl.com/api/v8/workspaces/$workspace_id/tasks";
        return self::send($params);
    }
}