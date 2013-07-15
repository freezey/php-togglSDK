<?php 

class TogglTag extends Toggl{
    public static $fields = array(
        "name", // The name of the tag (string, required, unique in workspace)
        "wid", // workspace ID, where the tag will be used (integer, required)
    );

    public static function createTag(array $params = array()){
        foreach ($params as $name => $param){
            if (array_search($name, self::$fields) === false){
                return "Invalid Parameter: $name";
            }
            $params["tag"][$name] = $param;
            unset($params[$name]);
        }
        $params['method'] = "POST";
        $params['url'] = "https://www.toggl.com/api/v8/tags";
        return self::send($params);
    }
}