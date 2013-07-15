<?php 

class TogglUser extends Toggl{
    public static $fields = array(
        "api_token", // (string)
        "default_wid", // default workspace id (integer)
        "email", // (string)
        "jquery_timeofday_format", // (string)
        "jquery_date_format", //(string)
        "timeofday_format", // (string)
        "date_format", // (string)
        "store_start_and_stop_time", // whether start and stop time are saved on time entry (boolean)
        "beginning_of_week", // (integer, Sunday=0)
        "language", // user's language (string)
        "image_url", // url with the user's profile picture(string)
        "sidebar_piechart", // should a piechart be shown on the sidebar (boolean)
        "at", // timestamp of last changes
        "new_blog_post", // an object with toggl blog post title and link
    );

    public static function getCurrentUserData(array $params = array()){
        foreach ($params as $name => $param){
            if (array_search($name, self::$fields) === false){
                return "Invalid Parameter: $name";
            }
        }
        $params['method'] = "GET";
        $params['url'] = "https://www.toggl.com/api/v8/me";
        return self::send($params);
    }

    public static function signUpNewUser(array $params = array()){
        foreach ($params as $name => $param){
            if (array_search($name, self::$fields) === false){
                return "Invalid Parameter: $name";
            }
        }
        $params['method'] = "POST";
        $params['url'] = "https://www.toggl.com/api/v8/signups";
        return self::send($params);
    }
}