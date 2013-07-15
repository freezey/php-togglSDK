<?php 

class TogglTask extends Toggl{
    public static $fields = array(
        "name", // The name of the task (string, required, unique in project)
        "pid", // project ID for the task (integer, required)
        "wid", // workspace ID, where the task will be saved (integer, project's workspace id is used when not supplied)
        "uid", // user ID, to whom the task is assigned to (integer, not required)
        "estimated_seconds", // estimated duration of task in seconds (integer, not required)
        "active", // whether the task is done or not (boolean, by default true)
        "at", // timestamp that is sent in the response for PUT, indicates the time task was last updated
    );

    public static function createATask(array $params = array()){
        foreach ($params as $name => $param){
            if (array_search($name, self::$fields) === false){
                return "Invalid Parameter: $name";
            }
            $params["task"][$name] = $param;
            unset($params[$name]);
        }
        $params['method'] = "POST";
        $params['url'] = "https://www.toggl.com/api/v8/tasks";
        return self::send($params);
    }

    public static function getTaskDetails($task_id, array $params = array()){
        foreach ($params as $name => $param){
            if (array_search($name, self::$fields) === false){
                return "Invalid Parameter: $name";
            }
        }
        $params['method'] = "GET";
        $params['url'] = "https://www.toggl.com/api/v8/tasks/$task_id";
        return self::send($params);
    }
}