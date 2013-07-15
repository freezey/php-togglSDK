<?php 

class TogglClient extends Toggl{
    public static $fields = array(
        "name", // The name of the client (string, required, unique in workspace)
        "wid", // workspace ID, where the client will be used (integer, required)
        "notes", // Notes for the client (string, not required)
        "hrate", // The hourly rate for this client (float, not required, available only for pro workspaces)
        "cur", // The name of the client's currency (string, not required, available only for pro workspaces)
        "at", // timestamp that is sent in the response, indicates the time client was last updated
    );

    public static function createAClient(array $params = array()){
        foreach ($params as $name => $param){
            if (array_search($name, self::$fields) === false){
                return "Invalid Parameter: $name";
            }
            $params["client"][$name] = $param;
            unset($params[$name]);
        }
        $params['method'] = "POST";
        $params['url'] = "https://www.toggl.com/api/v8/clients";
        return self::send($params);
    }

    public static function getClientDetails($client_id, array $params = array()){
        foreach ($params as $name => $param){
            if (array_search($name, self::$fields) === false){
                return "Invalid Parameter: $name";
            }
        }
        $params['method'] = "GET";
        $params['url'] = "https://www.toggl.com/api/v8/clients/$client_id";
        return self::send($params);
    }

    public static function getClientsVisibleToUser(array $params = array()){
        foreach ($params as $name => $param){
            if (array_search($name, self::$fields) === false){
                return "Invalid Parameter: $name";
            }
        }
        $params['method'] = "GET";
        $params['url'] = "https://www.toggl.com/api/v8/clients";
        return self::send($params);
    }

    public static function getClientProjects($client_id, array $params = array()){
        foreach ($params as $name => $param){
            if (array_search($name, self::$fields) === false){
                return "Invalid Parameter: $name";
            }
        }
        $params['method'] = "GET";
        $params['url'] = "https://www.toggl.com/api/v8/clients/$client_id/projects";
        return self::send($params);
    }
}