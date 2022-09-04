<?php

/**
 * this class is used to modify the response according to mapbox standards 
 * ie in GeoJson Format
 */
class MapboxResponseHandler {

    /**
     * @var array $nameOptions is used to map the values according to the mapbox place-type parmeter in response
     * example : ["LocationIQ_values(address)" => "Mapbox_values(place-type)"]
     */
    private  $nameOptions = [
        'name' => 'poi',
        'house_number' => 'address',
        'road' => 'address',
        'neighbourhood' => 'neighborhood',
        'suburb' => 'locality',
        'island' => 'place',
        'city' => 'place',
        'county' => 'district',
        'state' => 'region',
        //TODO: TBD
        'state_code' => '',
        'postcode' => 'postcode',
        'country' => 'country',
        //TODO: TBD
        'country_code' => ''
    ];

    /**
     * @var array $errorMapping is used to map the values according to the mapbox error messages 
     * example : ["ErrorCode" => "Mapbox Error according to the code"]
     */
    private $errorMapping = [

        400 => "Not Found",
        401 => "Not Authorized - Invalid Token",
        429 => "Rate limit exceeded",
        403 => "Forbidden",
        404 => "Not Found",
        //TODO: TBD
        500 => ""

    ];

    // this variable is used to store the error http status code
    private $errorCode;


    /**
     * this function is used to split the search_string 
     * 
     * @param  string  $query  this is the search_string which we accessed from MapboxRequestHandler
     * @return array           is returned which is consists of lat,lon if reverse geocoding else q is returned if forward geocoding else null string is returned
     */
    private function queryExtraction($query) {

        if (isset($query['q'])) {
            return explode(' ', $query['q']);
        } else if (isset($query['lat']) && isset($query['lon'])) {
            return [$query['lon'], $query['lat']];
        } else {
            return [];
        }
    }


    /** Get element from array is exists or return default value
     * @method getArrayElement
     * @param  array    $array      Array to retrieve element from
     * @param  string   $element    Element to retrieve from the array
     * @param  mixed    $return     Default value to return if element doesn't exist
     * @return mixed                Element from array is exists or the default value
     */
    public function getArrayElement($array, $element, $return = null) {
        if (isset($array[$element])) {
            $return = $array[$element];
        }
        return $return;
    }


    /**
     * this function is used to extract the features
     *
     * @param  array  $decode    this is first response from LocationIQ
     * @return array  $features  is returned which consists of all the values present in both mapbox and loctionIQ
     */
    private function featureExtraction($decode) {

        // $features is used to store the same information present in the mapbox and locationIQ response
        // ex  ['mapbox-values'] => ['locationIq-information']

        $features = [
            'id' => '',
            'type' => 'Feature',
            'place-type' => [],
            'relevance' => $this->getArrayElement($decode, 'importance',  null),
            'properties' => [],
            'bbox' => $this->getArrayElement($decode, 'boundingbox',  null),
            'text' => '',
            'place_name' => $this->getArrayElement($decode, 'display_name',  null),
            'center' => [
                $this->getArrayElement($decode, 'lat',  null),
                $this->getArrayElement($decode, 'lon',  null)
            ],
            'geometry' => [
                'coordinates' => [
                    $this->getArrayElement($decode, 'lat',  null),
                    $this->getArrayElement($decode, 'lon',  null)
                ],
            ],
        ];

        // only if the extratags is set then we use the information in it to add in features['properties']
        if (isset($decode['extratags'])) {
            if (isset($decode['extratags']['wikidata'])) {
                $features['properties'] = [
                    'wikidata' => $decode['extratags']['wikidata']
                ];
            }
            if (isset($decode['extratags']['building:use'])) {
                $features['properties'] = [
                    'category' => $decode['extratags']['building:use']
                ];
            }
        }

        //only if the address is set then we use the information in it to add in features['place-type']
        if (isset($decode['address'])) {
            $addressType = key($decode['address']);
            $placeType  = $this->getArrayElement($this->nameOptions, $addressType, null);
            $features['place-type'] = [$placeType];

            // if the placeType is present then only we can use it to add before place_id which is id in mapbox response
            if (!is_null($placeType)) {
                $features['id'] = $placeType . "." . $decode['place_id'];
            } else {
                $features['id'] = $decode['place_id'];
            }
        }


        // if namedetails is set then we use to find the text which is the first value of namedetails array
        if (isset($decode['namedetails'])) {
            //key is used to find the first key in the associative array
            $temp = key($decode['namedetails']);
            $features['text'] = $decode['namedetails'][$temp];
        }

        $features = array_filter($features, function ($v) {
            return !is_null($v);
        });

        return $features;
    }


    /**
     * used to make a geojson format after extracting all the features from different functions
     *
     * @param  array   $decode      is the first response from the loctionIQs response
     * @param  string  $query       is search_string 
     * @return string  $final_data  is returned after encoding the result into json format 
     */
    private function mapboxGeoJsonCreation($decode, $query) {

        // $new_data is used to store all the attributes of the geojson format
        $new_data = [
            'type' => 'FeatureCollection',
            'query' => $this->queryExtraction($query),
            'features' => $this->featureExtraction($decode),
            'attribution' => $decode['licence']
        ];

        // the $new_data which is an associative array is converted into json format using json_encode
        $final_data = json_encode($new_data);

        //finally the $final_data is returned to mainProcess
        return $final_data;
    }

    /**
     * this function is used when there are multiple responses, we convert each of them according to the mapbox response format 
     *
     * @param  array $decode this array consists of multiples responses
     * @param  array $query  search_string as ['q' => 'search_string'] 
     * @return array $result 
     */
    private function multiResponse(array $decode,  $query) {
        $result = [];
        for ($i = 0; $i < count($decode); $i++) {
            $returnResponse = $this->mapboxGeoJsonCreation($decode[$i], $query);
            array_push($result, $returnResponse);
        }
        return $result;
    }

    /**
     * returns the response from LocationIQ's API
     *
     * @param string  $url          input url in the form of string
     * @return mixed  (array||int)  if the response exsists then we send the first response of LocationIQ  else we return 0 which represents 'no response' else returns -1 if there is any error
     */
    private function requestResponse($url) {

        $c = curl_init();

        curl_setopt($c, CURLOPT_URL, $url);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);

        // curl execution
        $resp  = curl_exec($c);

        // $httpcode is used to store the http status code
        $httpcode = curl_getinfo($c, CURLINFO_HTTP_CODE);

        // checking if the result is error response 
        if ($httpcode >= 400) {
            $this->errorCode = $httpcode;
            return -1;
        }

        // checking if the result is error then we will just print the error 
        if ($e = curl_error($c)) {
            echo $e;
        } else {

            // the response from the API is stored in an array format in $decode
            $decode = json_decode($resp, true);

            // if $decode is not null ie if there is even one response we return it else we return 0 indicating there was no response
            if (!is_null($decode)) {
                return $decode;
            }
            return 0;
        }

        // colsing the curl connection 
        curl_close($c);
    }


    /**
     * Returns the response according to Mapbox standards
     * 
     * @param  string  $url             url which is changed accoring to LocationIQ standards
     * @param  array   $query           search_string which directly accessed from MapboxRequestHandler
     * @param  int     $error           error from MapboxRequestHandler, false if no error is present
     * @return array   $returnResponse  consists of the final result according to mapbox response format
     * 
     *mapbox errors : https://docs.mapbox.com/api/search/geocoding/#geocoding-api-errors 
     */
    public function mainProcess($url, $query, $error, $errorCode) {
        $returnResponse = null;
        if ($error === true) {
            if (isset($this->errorMapping[$errorCode])) {
                $returnResponse = json_encode(['message' => $this->errorMapping[$errorCode]]);
            }
            // else{ TODO }
            return $returnResponse;
        }
        $Response = $this->requestResponse($url);
        if ($Response == -1) {
            $returnResponse = json_encode(['message' => $this->errorMapping[$this->errorCode]]);
        } else if (!$Response == 0) {
            //str_contains TBD
            if (strpos($url, '/v1/reverse') == false) {
                $returnResponse =   $this->multiResponse($Response, $query);
                return $returnResponse;
            }
            $returnResponse = $this->mapboxGeoJsonCreation($Response, $query);
        }
        return $returnResponse;
    }
}
