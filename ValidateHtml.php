<?php

/**
 * W3C HTML validator data retriever
 * 2017
 * Created by Renee Säks
 * http://www.escaper.ee/renee/portfolio/
 */
class ValidateHtml
{
    public $url;
    public $htmlErrors;
    public $htmlWarnings;
    public $htmlErrorsAndWarnings;
    public $htmlAllData;

    // clean up the array in case any html tags are present
    private static function htmlSpecialCharsArray($arr = array())
    {
        $rs = array();
        while (list($key, $val) = each($arr)) {
            if (is_array($val)) {
                $rs[$key] = self::htmlSpecialCharsArray($val);
            } else {
                $rs[$key] = htmlentities($val, ENT_QUOTES);
            }
        }
        return $rs;
    }


    // check if the host is in localhost
    private static function notLocalhost()
    {
        $whitelist = array('127.0.0.1', '::1');
        if (!in_array($_SERVER['REMOTE_ADDR'], $whitelist)) {
            return true;
        } else {
            return false;
        }
    }


    // function to sort out errors or warnings
    private static function getSpecificType($data, $type1, $type2 = false)
    {

        $htmlData = array();

        foreach ($data['messages'] as $key => $value) {
            foreach ($data['messages'][$key] as $key1 => $value1) {
                if ($value1 == $type1 || $value1 == $type2) {
                    if(isset($data['messages'][$key]['lastLine'])) {
                        array_push($htmlData, $data['messages'][$key]['message'] . ' Line ' . $data['messages'][$key]['lastLine'] . '.');
                    } else {
                        array_push($htmlData, $data['messages'][$key]['message']);
                    }
                }
            }
        }

        if (!empty($htmlData)) {
            return $htmlData;
        } else {
            return 'No errors or warnings to show.';
        }


    }


    // get everything from validator to array
    private static function getAllInArray($url, $repeat = false)
    {

        // if repeated request then return the already saved data
        if ($repeat != false) {
            return $repeat;
        } else {
            $html = 'https://validator.w3.org/nu/?&doc=' . $url . '&out=json';
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $html);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)');
            $data = curl_exec($curl);
            curl_close($curl);

            if (strpos($data, 'Forbidden due to abuse')) {
                exit($data . 'Try again later.');
            }

            if (empty($data)) {
                exit("Something went wrong with call to W3C API. Make sure you have the valid URL and in the 
            correct format.");
            }

            return self::htmlSpecialCharsArray(json_decode($data, true));

        }

    }


    // get only errors
    private function getValidatorErrors($validatorData, $serialized)
    {

        if ($serialized) {
            $this->htmlErrors = serialize(self::getSpecificType($validatorData, 'error'));
        } else {
            $this->htmlErrors = self::getSpecificType($validatorData, 'error');
        }

    }


    // get only warnings
    private function getValidatorWarnings($validatorData, $serialized)
    {

        if ($serialized) {
            $this->htmlWarnings = serialize(self::getSpecificType($validatorData, 'warning'));
        } else {
            $this->htmlWarnings = self::getSpecificType($validatorData, 'warning');
        }

    }


    // get both errors and warnings
    private function getValidatorErrorsAndWarnings($validatorData, $serialized)
    {

        if ($serialized) {
            $this->htmlErrorsAndWarnings = serialize(self::getSpecificType($validatorData, 'warning', 'error'));
        } else {
            $this->htmlErrorsAndWarnings = self::getSpecificType($validatorData, 'warning', 'error');
        }

    }

    // get all the data from validator
    private function getValidatorAll($validatorData, $serialized)
    {

        if (empty($validatorData['messages'])) {
            $this->htmlAllData = 'No errors or warnings to show.';
        } else {
            if ($serialized) {
                $this->htmlAllData = serialize(self::getAllInArray(false, $validatorData));
            } else {
                $this->htmlAllData = self::getAllInArray(false, $validatorData);
            }
        }

    }


    // main function
    public function validateHtml($url, $serialized = false)
    {

        if (self::notLocalhost()) {
            $validatorData = self::getAllInArray($url);
            $this->url = $url;
            $this->getValidatorErrors($validatorData, $serialized);
            $this->getValidatorWarnings($validatorData, $serialized);
            $this->getValidatorErrorsAndWarnings($validatorData, $serialized);
            $this->getValidatorAll($validatorData, $serialized);

        } else {
            exit('Localhost is not supported.');
        }

    }

}