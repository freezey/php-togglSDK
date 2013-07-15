<?php

class TogglReport extends Toggl{
    public static $fields = array(
        'user_agent', // string, **required**, the name of your application or your email address so we can get in touch in case you're doing something wrong.
        'workspace_id', // integer, **required**. The workspace which data you want to access.
        'since', // string, ISO 8601 date (YYYY-MM-DD), by default until - 6 days.
        'until', // string, ISO 8601 date (YYYY-MM-DD), by default today
        'billable', // possible values: yes/no/both, default both
        'client_ids', // client ids separated by a comma, **0** if you want to filter out time entries without a client
        'project_ids', // project ids separated by a comma, **0** if you want to filter out time entries without a project
        'user_ids', // user ids separated by a comma
        'tag_ids', // tag ids separated by a comma, **0** if you want to filter out time entries without a tag
        'task_ids', // task ids separated by a comma, **0** if you want to filter out time entries without a task
        'time_entry_ids', // time entry ids separated by a comma
        'description', // string, time entry description
        'without_description', // true/false, filters out the time entries which do not have a description ('(no description)')
        'order_field',
            // * date/description/duration/user in detailed reports
            // * title/duration/amount in summary reports
            // * title/day1/day2/day3/day4/day5/day6/day7/week_total in weekly report
        'order_desc', // on/off, `on` for descending and `off` for ascending order
        'distinct_rates', // on/off, default off
        'rounding', // on/off, default off, rounds time according to workspace settings
        'display_hours', // decimal/minutes, display hours with minutes or as a decimal number, default minutes

    );

    public static function detailed(array $params = array()){
        $additionalFields = array('page');
        foreach ($params as $name => $param){
            if (array_search($name, self::$fields) === false && array_search($name, $additionalFields) === false){
                return "Invalid Parameter: $name";
            }
            if ($name == 'since' || $name == 'until'){
                $params[$name] = date('Y-m-d', strtotime($param));
            }
        }
        $query = http_build_query($params);
        unset($params);
        $params['method'] = "GET";
        $params['url'] = "https://toggl.com/reports/api/v2/details?" . $query;
        return self::send($params);
    }

    public static function summary(array $params = array()){
        $additionalFields = array('grouping', 'subgrouping');
        foreach ($params as $name => $param){
            if (array_search($name, self::$fields) === false && array_search($name, $additionalFields) === false){
                return "Invalid Parameter: $name";
            }
            if ($name == 'since' || $name == 'until'){
                $params[$name] = date('Y-m-d', strtotime($param));
            }
        }
        $query = http_build_query($params);
        unset($params);
        $params['method'] = "GET";
        $params['url'] = "https://toggl.com/reports/api/v2/summary?" . $query;
        return self::send($params);
    }

    public static function weekly(array $params = array()){
        $additionalFields = array('grouping');
        foreach ($params as $name => $param){
            if (array_search($name, self::$fields) === false && array_search($name, $additionalFields) === false){
                return "Invalid Parameter: $name";
            }
            if ($name == 'since' || $name == 'until'){
                $params[$name] = date('Y-m-d', strtotime($param));
            }
        }
        $query = http_build_query($params);
        unset($params);
        $params['method'] = "GET";
        $params['url'] = "https://toggl.com/reports/api/v2/weekly?" . $query;
        return self::send($params);
    }
}