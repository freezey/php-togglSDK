<?php

class SurveyGizmo {

    /*
     * API URL parts
     */
    const SURVEY_URL = "http://restapi.surveygizmo.com/v3/survey";
    const RESPONSES = "surveyresponse";
    const QUESTIONS = "surveyquestion";
    const SURVEYCAMPAIGN = "surveycampaign";

    private $userName;
    private $password;


    public function __construct($surveyGizmoUserName, $surveyGizmoPassword) {
        $this->userName = $surveyGizmoUserName;
        $this->password = $surveyGizmoPassword;
    }

    private function sendWithoutAddingAuth($url) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($curl);
        curl_close($curl);
        $resultJson = json_decode($result, true);
        if ($resultJson['result_ok'] == true){
            return $resultJson['data'];
        } else {
            CakeLog::write('notice', 'SurveyGizmo API returned Error Code ' . $resultJson['code'] . ': ' . $resultJson['message'] . ' -- Request URL: ' . $url . ' Raw result dump: ' . $result);
            throw new Exception('SurveyGizmo API returned Error Code ' . $resultJson['code'] . ': ' . $resultJson['message'] . ' -- Request URL: ' . $url);
        }
    }

    private function send($url, $options = array()) {
        if (isset($options['survey_id'])){
            $url = '/' . $options['survey_id'];
            unset($options['survey_id']);
        }
        if (substr($url, -1) !== '&'){
            if (strpos($url, '?') === false){
                $url .= '?';
            } else {
                $url .= '&';
            }
        }
        $url = $url . "user:md5=" . $this->userName . ":" . md5($this->password);
        if (!empty($options)) {
            foreach ($options as $key => $option){
                if (is_array($option)){
                    $filter["filter[field][$key]"] = $option['field'];
                    $filter["filter[operator][$key]"] = $option['operator'];
                    $filter["filter[value][$key]"] = $option['value'];
//                    $filter['altfilter'] = urlencode(http_build_query($query));
                } else {
                    $filter[$key] = $option;
                }

            }
            $url = $url . '&' . urldecode(http_build_query($filter, '', '&'));
        }

        return $this->sendWithoutAddingAuth($url);
    }

    public function checkConnection(){
        $url = self::SURVEY_URL;
        $options = array('resultsperpage' => '1', 'page' => 1);
        try{
            $results = $this->send($url, $options);
        } catch(Exception $e){
            return false;
        }
        return $results !== false;
    }

    public function getSurveyList(array $options = array()){
        $url = self::SURVEY_URL;
        $surveys = array();
        $page = 1;
        $allResultsReturned = false;
        while ($allResultsReturned == false){
            $optionsWithPage = array_merge($options, array('page' => $page, 'resultsperpage' => '50'));
            $results = $this->send($url, $optionsWithPage);
            $surveys = array_merge($surveys, $results);
            if (count($results) != 50){
                $allResultsReturned = true;;
            }
            $page++;
        }
        return $results;
    }

    public function getSurveyQuestions($surveyId, $options = array()){
        $url = self::SURVEY_URL . "/" . $surveyId . "/" . self::QUESTIONS;

        $questions = array();
        $page = 1;
        $allResultsReturned = false;
        while ($allResultsReturned == false){
            $optionsWithPage = array_merge($options, array('page' => $page, 'resultsperpage' => '50'));
            $results = $this->send($url, $optionsWithPage);
            $questions = array_merge($questions, $results);
            if (count($results) != 50){
                $allResultsReturned = true;
            }
            $page++;
        }
        return $results;
    }

    public function getSurveyResponses($surveyId, $options = array()){
        $url = self::SURVEY_URL . '/' . $surveyId . '/' . self::RESPONSES;

        $responses = array();
        $page = 1;
        $allResultsReturned = false;
        while ($allResultsReturned == false){
            $optionsWithPage = array_merge($options, array('page' => $page, 'resultsperpage' => '200'));
            $results = $this->send($url, $optionsWithPage);
            $responses = array_merge($responses, $results);
            if (count($results) != 200){
                $allResultsReturned = true;
            }
            if (memory_get_usage(true) > 104857600){
                $allResultsReturned = true;
            }
            $page++;
        }
        return $responses;
    }

    public function getSurveyCampaign($surveyId, $options = array()){
        $url = self::SURVEY_URL . '/' . $surveyId . '/' . self::SURVEYCAMPAIGN;
        $results = $this->send($url, $options);
        return $results;
    }
    /*
     * Returns all the responses to the survey in the last $time_period period.
     * Default of last 12 hours
     */

    public function getSurveyResponsesWithFilter($surveyId, $filter){
        $url = self::SURVEY_URL . "/" . $surveyId . "/" . self::RESPONSES . "?";
        

        $responseList = $this->send($url);

        //Return false if the result was not ok
        if (!$responseList["result_ok"]) {
            throw new Exception("The Survey Gizmo import service returned an error of {$responseList['code']} - {$responseList['message']}");
        }

        //Separate the data from the response from SurveyGizmo
        $responses = $responseList["data"];

        return $responses;
    }

    /*
     * Returns an non-zero-based array
     * $questionId=>$questionTitle
     */
    public function getSurveyQuestionTitles($surveyId, $language = "English") {
        //Check the question title for "first name", "last name", or "email"
        $surveyQuestions = $this->getSurveyQuestions($surveyId);
        foreach ($surveyQuestions as $question) {
            $id = $question["id"];
            $title = $question["title"][$language];
            $questionTitles[$id] = $title;
        }
        return $questionTitles;
    }

    /*
     * Returns all the metadata about a particular response to a survey
     */
    public function getSurveyResponse($surveyId, $responseId) {
        $url = self::SURVEY_URL . "/" . $surveyId . "/" . self::RESPONSES . "/" .$responseId . "?";
        $this->addAuthAndFilterToUrl($url);

        $response = $this->send($url);

        if (!$response["result_ok"]) {
            throw new Exception("The Survey Gizmo import service returned an error of {$response['code']} - {$response['message']}");
        }

        return $response["data"];
    }

    /*
     * Returns all the metadata about a particular question in a survey
     */
    public function getSurveyQuestion($surveyId, $questionId) {
        $url = self::SURVEY_URL . "/" . $surveyId . "/" . self::QUESTIONS . "/" . $questionId . "?";
        $this->addAuthAndFilterToUrl($url);

        $question = $this->send($url);

        if (!$question["result_ok"]) {
            throw new Exception("The Survey Gizmo import service returned an error of {$question['code']} - {$question['message']}");
        }

        return $question["data"];
    }



    public function getLastNResponses($surveyId, $survey_count){
        $data = array(
            "filter[field][0]"=>"status",
            "filter[operator][0]" => "=",
            "filter[value][0]" => "Complete",
            "resultsperpage" => $survey_count
        );

        return $this->getSurveyResponsesWithFilter($surveyId, $data);
    }

    public function getResponsesWithIdGreaterThen($surveyId, $id){ //Edited to include $id in the array - untested
        $data = array(
            "filter[field][0]" => "status",
            "filter[operator][0]" => "=",
            "filter[value][0]" => "Complete",
            "filter[field][1]" => 'response_id',
            "filter[operator][1]" => ">",
            "filter[value][1]" => $id
        );

        return $this->getSurveyResponsesWithFilter($surveyId, $data);
    }

    public function getResponsesWithDateGreaterThen($surveyId, $date, $page = 1){
        $data = array(
            "filter[field][0]" => "status",
            "filter[operator][0]" => "=",
            "filter[value][0]" => "Complete",
            "filter[field][1]" => 'datesubmitted',
            "filter[operator][1]" => ">=",
            "filter[value][1]" => $date,
            'resultsperpage' => '500',
            "page" => $page,
        );

        return $this->getSurveyResponsesWithFilter($surveyId, $data);
    }
}