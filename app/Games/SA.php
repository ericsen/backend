<?php

namespace App\Games;

use App\Games\IGameInterface;
use Carbon\Carbon;

class DES
{
    private $key;
    private $iv;
    function __construct($key, $iv = 0)
    {
        $this->key = $key;
        if ($iv == 0) {
            $this->iv = $key;
        } else {
            $this->iv = $iv;
        }
    }

    function encrypt($str)
    {
        return base64_encode(openssl_encrypt($str, 'DES-CBC', $this->key, OPENSSL_RAW_DATA, $this->iv));
    }
}

class SA
{

    /**
     * 測試線路資訊
     */
    // private static $_apiUrl = 'http://sai-api.sa-apisvr.com/api/api.aspx';
    // private static $_launchUrl = 'https://www.sai.slgaming.net/app.aspx';
    // private static $_secretKey = '044946CB1AF0431D82A88F981B73C706';
    // private static $_encryptKey = 'g9G16nTs';
    // private static $_md5Key = 'GgaIMaiNNtg';
    // private static $_lobby_name = 'A3760';

    /**
     * 正式線路資訊
     */
    private static $_apiUrl = 'http://api.sa-apisvr.com/api/api.aspx';
    private static $_launchUrl = 'https://web.sa-globalxns.com/app.aspx';
    private static $_secretKey = '22CB703338B848028414E92A2BE99E54';
    private static $_encryptKey = 'g9G16nTs';
    private static $_md5Key = 'GgaIMaiNNtg';
    private static $_lobby_name = 'A3760';


    private static function _xml2Array($contents, $get_attributes = 1, $priority = 'tag')
    {
        if (!$contents) return array();

        if (!function_exists('xml_parser_create')) {
            //print "'xml_parser_create()' function not found!";
            return array();
        }

        //Get the XML parser of PHP - PHP must have this module for the parser to work
        $parser = xml_parser_create('');
        xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8"); # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, trim($contents), $xml_values);
        xml_parser_free($parser);

        if (!$xml_values) return; //Hmm...

        //Initializations
        $xml_array = array();
        $parents = array();
        $opened_tags = array();
        $arr = array();

        $current = &$xml_array; //Refference

        //Go through the tags.
        $repeated_tag_index = array(); //Multiple tags with same name will be turned into an array
        foreach ($xml_values as $data) {
            unset($attributes, $value); //Remove existing values, or there will be trouble

            //This command will extract these variables into the foreach scope
            // tag(string), type(string), level(int), attributes(array).
            extract($data); //We could use the array by itself, but this cooler.

            $result = array();
            $attributes_data = array();

            if (isset($value)) {
                if ($priority == 'tag') $result = $value;
                else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
            }

            //Set the attributes too.
            if (isset($attributes) and $get_attributes) {
                foreach ($attributes as $attr => $val) {
                    if ($priority == 'tag') $attributes_data[$attr] = $val;
                    else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
                }
            }

            //See tag status and do the needed.
            if ($type == "open") { //The starting of the tag '<tag>'
                $parent[$level - 1] = &$current;
                if (!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
                    $current[$tag] = $result;
                    if ($attributes_data) $current[$tag . '_attr'] = $attributes_data;
                    $repeated_tag_index[$tag . '_' . $level] = 1;

                    $current = &$current[$tag];
                } else { //There was another element with the same tag name

                    if (isset($current[$tag][0])) { //If there is a 0th element it is already an array
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;
                        $repeated_tag_index[$tag . '_' . $level]++;
                    } else { //This section will make the value an array if multiple tags with the same name appear together
                        $current[$tag] = array($current[$tag], $result); //This will combine the existing item and the new item together to make an array
                        $repeated_tag_index[$tag . '_' . $level] = 2;

                        if (isset($current[$tag . '_attr'])) { //The attribute of the last(0th) tag must be moved as well
                            $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                            unset($current[$tag . '_attr']);
                        }
                    }
                    $last_item_index = $repeated_tag_index[$tag . '_' . $level] - 1;
                    $current = &$current[$tag][$last_item_index];
                }
            } elseif ($type == "complete") { //Tags that ends in 1 line '<tag />'
                //See if the key is already taken.
                if (!isset($current[$tag])) { //New Key
                    $current[$tag] = $result;
                    $repeated_tag_index[$tag . '_' . $level] = 1;
                    if ($priority == 'tag' and $attributes_data) $current[$tag . '_attr'] = $attributes_data;
                } else { //If taken, put all things inside a list(array)
                    if (isset($current[$tag][0]) and is_array($current[$tag])) { //If it is already an array...

                        // ...push the new element into that array.
                        $current[$tag][$repeated_tag_index[$tag . '_' . $level]] = $result;

                        if ($priority == 'tag' and $get_attributes and $attributes_data) {
                            $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                        }
                        $repeated_tag_index[$tag . '_' . $level]++;
                    } else { //If it is not an array...
                        $current[$tag] = array($current[$tag], $result); //...Make it an array using using the existing value and the new value
                        $repeated_tag_index[$tag . '_' . $level] = 1;
                        if ($priority == 'tag' and $get_attributes) {
                            if (isset($current[$tag . '_attr'])) { //The attribute of the last(0th) tag must be moved as well

                                $current[$tag]['0_attr'] = $current[$tag . '_attr'];
                                unset($current[$tag . '_attr']);
                            }

                            if ($attributes_data) {
                                $current[$tag][$repeated_tag_index[$tag . '_' . $level] . '_attr'] = $attributes_data;
                            }
                        }
                        $repeated_tag_index[$tag . '_' . $level]++; //0 and 1 index is already taken
                    }
                }
            } elseif ($type == 'close') { //End of tag '</tag>'
                $current = &$parent[$level - 1];
            }
        }

        return ($xml_array);
    }

    private static function _curl($method, $params)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, self::$_apiUrl);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($params));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        $data = self::_xml2Array(curl_exec($curl), 0);
        $responseLog = [
            'brand' => 'SA',
            'method' => $method,
            'result' => $data,
        ];
        GameAPI::log('SA', $method, 'response', $responseLog, 'GAME_API');
        curl_close($curl);
        return $data;
    }

    private static function _sign($data)
    {
        return md5($data);
    }

    private static function _encodeRequest($fields)
    {
        $QS = http_build_query($fields);
        // 實例化DES加密物件
        $encrypter = new DES(self::$_encryptKey);
        $payload = [
            'q' => $encrypter->encrypt($QS), //
            's' => self::_sign($QS . self::$_md5Key . $fields['Time'] . self::$_secretKey)
        ];
        return $payload;
    }

    public static function getGameUrl($feilds, $opts)
    {
        $launch_game_info = [
            'token' => $feilds['Token'],
            'username' => $feilds['DisplayName'],
            'lobby' => self::$_lobby_name,
            'lang' => $opts['lang'],
            'mobile' => true,
            'returnurl' => $opts['returnurl']
        ];
        $game_url = self::$_launchUrl;
        $keys = array_keys($launch_game_info);
        for ($i = 0; $i < count($keys); $i++) {
            $param = ($i == 0 ? '?' : '&') . $keys[$i] . '=' . $launch_game_info[$keys[$i]];
            $game_url .= $param;
        }
        return $game_url;
    }

    public static function RegUserInfo($req)
    {
        $merged_param = array_merge([
            'method' => __FUNCTION__,
            'Key' => self::$_secretKey,
            'Time' => Carbon::now()->format('yymdhis')
        ], $req);

        return self::_curl(__FUNCTION__, self::_encodeRequest($merged_param))['RegUserInfoResponse'];
    }

    public static function LoginRequest($req_to_sa)
    {
        $merged_param = array_merge([
            'method' => __FUNCTION__,
            'Key' => self::$_secretKey,
            'Time' => Carbon::now()->format('yymdhis')
        ], $req_to_sa);

        return self::_curl(__FUNCTION__, self::_encodeRequest($merged_param))['LoginRequestResponse'];
    }

    public static function LoginRequestForFun($req_to_sa)
    {
        $merged_param = array_merge([
            'method' => __FUNCTION__,
            'Key' => self::$_secretKey,
            'Time' => Carbon::now()->format('yymdhis')
        ], $req_to_sa);

        return self::_curl(__FUNCTION__, self::_encodeRequest($merged_param))['LoginRequestTryToPlayResponse'];
    }

    public static function GetUserBalance($fields){
        $merged_param = array_merge([
            'method' => __FUNCTION__,
            'Key' => self::$_secretKey,
            'Time' => Carbon::now()->format('yymdhis')
        ], $fields);

        return self::_curl(__FUNCTION__, self::_encodeRequest($merged_param));
    }

    //入金
    public static function CreditBalanceDV($fields)
    {
        $merged_param = array_merge([
            'method' => __FUNCTION__,
            'Key' => self::$_secretKey,
            'Time' => Carbon::now()->format('yymdhis'),
            'OrderId' => 'IN' . Carbon::now()->format('yymdhis') . $fields['Username']
        ], $fields);

        return self::_curl(__FUNCTION__, self::_encodeRequest($merged_param))['CreditBalanceResponse'];
    }

    //出全部的金
    public static function DebitAllBalanceDV($fields)
    {
        $merged_param = array_merge([
            'method' => __FUNCTION__,
            'Key' => self::$_secretKey,
            'Time' => Carbon::now()->format('yymdhis'),
            'OrderId' => 'OUT' . Carbon::now()->format('yymdhis') . $fields['Username']
        ], $fields);

        return self::_curl(__FUNCTION__, self::_encodeRequest($merged_param))['DebitAllBalanceResponse'];
    }
}
