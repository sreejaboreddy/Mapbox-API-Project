<?php


/**
 * MapboxRequestHandler  class is used to modify the given input url to LocationIQ standards 
 * just need to create a new method and pass the input url to process function which returns the modified url
 * We are force setting LocationIQ's parameters like format:json because the only response format in mapbox is json
 */
class MapboxRequestHandler {

    //this class variable is used to store the url
    private $url;

    //constructor initialization 
    function __construct($url) {
        $this->url = $url;
    }

    // used to store same parameters present in mapbox and LocationIQ
    // key represents parameters in mapbox , value represents parameters in LocationIQ
    // ex ['mapbox_parameter' => 'loctionIQ_parameter']

    private  $mapboxToLIQParameterMapping = [
        'language'     =>  'accept-language',
        'bbox'         =>  'viewbox',
        'country'      =>  'countrycodes',
        'limit'        =>  'limit',
        'access_token' =>  'key'
    ];

    // used to find represent if geocoding is forward or reverse
    private $reverse = false;

    private $prefixUrl = 'https://us1.locationiq.com/v1';

    private $query;

    // getter is set to access the value in mapbox.php
    public function getQuery() {
        return $this->query;
    }

    //this class variable is set when resulted in any error
    private $error = false;

    //this class variable is used to store error code like empty search_string (ex-400)
    private $errorCode;

    // getter is set to access the value in mapbox.php
    public function getError() {
        return $this->error;
    }

    public function getErrorCode() {
        return $this->errorCode;
    }

    /**
     * function is used to extraction search-string ie q in LocationIQ parameters
     * example url: https://api.mapbox.com/geocoding/v5/{endpoint}/{search_text}.json
     * 
     * @param   string $url          input url 
     * @return  mixed (array||bool)  returns the search_key ie q if present if this is forward geocoding else it returns lat,lon in array if this is reverse geocoding else returns false
     */
    private function pathParameterExtraction($url) {
        // varible $values is used to save the parameter extracted from the input url
        // stored in an associative array
        // if the search_string is not present then we return false 
        $values = parse_url($url);
        if ($values === false) {
            return false;
        }
        //manipulating the string to get the key words which we use to search LocationIQ
        //$values['path'] will contain value similar to this  "/geocoding/v5/{endpoint}/{search_text}.json"
        if (isset($values['path'])) {
            $searchString = explode("/", $values['path']);
            $searchString = rtrim($searchString[4], ".json");

            $result = explode(",", $searchString);

            //checking if the given url has lat,lon values 
            if (!is_null($result) && count($result) == 2) {
                $lon = trim($result[0]);
                $lat = trim($result[1]);
                //if the lat,lon values are present then $reverse is set as true
                if ((is_numeric($lat)) and (is_numeric($lon))) {
                    $this->reverse = true;
                    // return values as an associative array with the lat,lon according to LocationIQ standards
                    return ['lat' => $lat, 'lon' => $lon];
                } else {
                    return ['q' => urldecode($searchString)];
                }
            }
            //in case of forward geocoding we return the $searchString 
            else {

                return ['q' => urldecode($searchString)];
            }
        }
        return false;
    }


    /**
     * this fuction is used to extract url parameters
     *
     * @param   string  $url input url 
     * @return  mixed   (array||bool) returns url parameters if present else returns false
     */
    private function urlParametersExtraction($url) {

        // varible $values is used to save the parameter extracted from the input url
        // stored in an associative array
        $values = parse_url($url);

        if ($values === false) {
            return false;
        }

        if (isset($values['query'])) {
            parse_str($values['query'], $parameters);
            return $parameters;
        }
        return false;
    }


    /**
     * this function is used to change the url parameters according to LocationIQ
     *
     * @param   array  $params     URL Parameters extracted from mapbox URL format
     * @return  array  $parmeters  URL Parameters as per LocationIQ's standard
     */
    private function mapParametersToLIQFormat(array $params) {
        //$parameters is used to store the values according to LocationIQ standards
        $parameters = [];

        //we are force setting format as json because the return format in mapbox is geojson
        $parameters['format'] = 'json';

        // looping through the $params to check if there are any parameters which are same in both Mapbox and LocationIQ
        foreach ($params as $key => $value) {
            if (isset($this->mapboxToLIQParameterMapping[$key])) {
                $parameters[$this->mapboxToLIQParameterMapping[$key]] = $value;
            }
        }

        //returning parameters
        return $parameters;
    }


    /**
     * this is function returns the final url according to LocationIQ standards is returned
     * using http_build_query
     * 
     * @param   string  $pathParamerters  search_string i.e q in Location_IQ
     * @param   array   $urlParameters    optional parameters which are same in both mapbox and LocationIQ
     * @return  string  $ans              returing the resultant http request in form of string
     */
    private function buildUrl(array $pathParameters, array $urlParameters) {
        //$parameters is an associative array which is used to store all the values that are needed 
        $parameters = [];

        // we add the path parameters which are q ie search-key in forward geocoding
        //else we add lat,lon values for reverse geocodin
        if (!is_null($pathParameters)) {
            $parameters = $parameters + $pathParameters;
        }

        //if there are same optional parameters available then, they are appended to the 
        //associative array which is later converted into http request
        if (!is_null($urlParameters)) {
            $parameters = $parameters + $urlParameters;
        }

        // building a http query from the associative array which is stored in $parameters
        $ans = http_build_query($parameters);
        //if $reverse is set to false it means forward geocoding address is used 
        if (!$this->reverse) {
            $ans = $this->prefixUrl . "/search.php?" . $ans;
        }
        //else reverse geocoding address is used
        else {
            $ans = $this->prefixUrl . "/reverse.php?" . $ans;
        }

        //returning the resultant http request 
        return $ans;
    }

    /**
     * this is function is used to return the resultant url
     * if search_string is not present then it prints an error else checks for the url parameters if the array is not null then resultant url is returned
     *
     * @param   string  $url input url
     * @return  string  which is modified accoring to LocationIQ standards
     */
    public function process() {

        $pathParameters = $this->pathParameterExtraction($this->url);
        $this->query = $pathParameters;
        if ($pathParameters === false) {
            $this->error = true;
            $this->errorCode = 400;
            return '';
        }
        $urlParameters = $this->urlParametersExtraction($this->url);
        if ($urlParameters == false) {
            $this->error = true;
            $this->errorCode = 401;
            return '';
        }
        $liqParameters = $this->mapParametersToLIQFormat($urlParameters);
        $resultantUrl = $this->buildUrl($pathParameters, $liqParameters);
        return $resultantUrl;
    }
}
