<?php 

class TogglTimeEntry extends Toggl{
    public static $fields = array(
        "description", // (string, required)
        "wid", // workspace ID (integer, required if pid or tid not supplied)
        "pid", // project ID (integer, not required)
        "tid", // task ID (integer, not required)
        "billable", // (boolean, not required, default false, available for pro workspaces)
        "start", // time entry start time (string, required, ISO 8601 date and time)
        "stop", // time entry stop time (string, not required, ISO 8601 date and time)
        "duration", // time entry duration in seconds. If the time entry is currently running, the duration attribute contains a negative value, denoting the start of the time entry in seconds since epoch (Jan 1 1970). The correct duration can be calculated as current_time + duration, where current_time is the current time in seconds since epoch. (integer, required)
        "created_with", // the name of your client app (string, required)
        "tags", // a list of tag names (array of strings, not required)
        "duronly", // should Toggl show the start and stop time of this time entry? (boolean, not required)
        "at", // timestamp that is sent in the response, indicates the time item was last updated
    );

    public static function createATimeEntry(array $params = array()){
        foreach ($params as $name => $param){
            if (array_search($name, self::$fields) === false){
                return "Invalid Parameter: $name";
            }
            $params["time_entry"][$name] = $param;
            unset($params[$name]);
        }
        $params['method'] = "POST";
        $params['url'] = "https://www.toggl.com/api/v8/time_entries";
        return self::send($params);
    }

    public static function startATimeEntry(array $params = array()){
        foreach ($params as $name => $param){
            if (array_search($name, self::$fields) === false){
                return "Invalid Parameter: $name";
            }
            $params["time_entry"][$name] = $param;
            unset($params[$name]);
        }
        $params['method'] = "POST";
        $params['url'] = "https://www.toggl.com/api/v8/time_entries/start";
        return self::send($params);
    }

    public static function getTimeEntryDetails($time_entry_id, array $params = array()){
        foreach ($params as $name => $param){
            if (array_search($name, self::$fields) === false){
                return "Invalid Parameter: $name";
            }
        }
        $params['method'] = "GET";
        $params['url'] = "https://www.toggl.com/api/v8/time_entries/$time_entry_id";
        return self::send($params);
    }

    public static function deleteATimeEntry($time_entry_id, array $params = array()){
        foreach ($params as $name => $param){
            if (array_search($name, self::$fields) === false){
                return "Invalid Parameter: $name";
            }
        }
        $params['method'] = "POST";
        $params['url'] = "https://www.toggl.com/api/v8/time_entries/$time_entry_id";
        return self::send($params);
    }

    public static function getTimeEntriesStartedInASpecificTimeRange(array $params = array()){
        foreach ($params as $name => $param){
            if (array_search($name, self::$fields) === false){
                return "Invalid Parameter: $name";
            }
        }
        $params['method'] = "GET";
        $params['url'] = "https://www.toggl.com/api/v8/time_entries";
        return self::send($params);
    }
}