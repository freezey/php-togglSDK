<?php 

class TogglProject extends Toggl{
    public static $fields = array(
        "name", // The name of the project (string, required, unique for client and workspace)
        "wid", // workspace ID, where the project will be saved (integer, required)
        "cid", // client ID(integer, not required)
        "active", // whether the project is archived or not (boolean, by default true)
        "is_private", // whether project is accessible for only project users or for all workspace users (boolean, default true)
        "template", // whether the project can be used as a template (boolean, not required)
        "template_id", // id of the template project used on current project's creation
        "billable", // whether the project is billable or not (boolean, default true, available only for pro workspaces)
        "at", // timestamp that is sent in the response for PUT, indicates the time task was last updated
    );

    public static function createProject(array $params = array()){
        foreach ($params as $name => $param){
            if (array_search($name, self::$fields) === false){
                return "Invalid Parameter: $name";
            }
            $params["project"][$name] = $param;
            unset($params[$name]);
        }
        $params['method'] = "POST";
        $params['url'] = "https://www.toggl.com/api/v8/projects";
        return self::send($params);
    }

    public static function getProjectData($project_id, array $params = array()){
        foreach ($params as $name => $param){
            if (array_search($name, self::$fields) === false){
                return "Invalid Parameter: $name";
            }
        }
        $params['method'] = "GET";
        $params['url'] = "https://www.toggl.com/api/v8/projects/$project_id";
        return self::send($params);
    }

    public static function getProjectUsers($project_id, array $params = array()){
        foreach ($params as $name => $param){
            if (array_search($name, self::$fields) === false){
                return "Invalid Parameter: $name";
            }
        }
        $params['method'] = "GET";
        $params['url'] = "https://www.toggl.com/api/v8/projects/$project_id/project_users";
        return self::send($params);
    }
}