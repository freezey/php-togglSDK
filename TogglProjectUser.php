<?php 

class TogglProjectUser extends Toggl{
    public static $fields = array(
        "pid", // project ID (integer, required)
        "uid", // user ID, who is added to the project (integer, required)
        "wid", // workspace ID, where the project belongs to (integer, not-required, project's workspace id is used)
        "manager", // admin rights for this project (boolean, default false)
        "rate", // hourly rate for the project user (float, not-required, only for pro workspaces) in the currency of the project's client or in workspace default currency.
        "at", // timestamp that is sent in the response, indicates when the project user was last updated
    );

    public static function createAProjectUser(array $params = array()){
        foreach ($params as $name => $param){
            if (array_search($name, self::$fields) === false){
                return "Invalid Parameter: $name";
            }
            $params["project_user"][$name] = $param;
            unset($params[$name]);
        }
        $params['method'] = "POST";
        $params['url'] = "https://www.toggl.com/api/v8/project_users";
        return self::send($params);
    }

    public static function createMultipleProjectUsersForSingleProject(array $params = array()){
        foreach ($params as $name => $param){
            if (array_search($name, self::$fields) === false){
                return "Invalid Parameter: $name";
            }
            $params["project_user"][$name] = $param;
            unset($params[$name]);
        }
        $params['method'] = "POST";
        $params['url'] = "https://www.toggl.com/api/v8/project_users";
        return self::send($params);
    }
}