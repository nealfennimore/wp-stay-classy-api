<?php

/*
  Plugin Name: Classy API
  Description: Classy API wrapper
  Version: 0.1.1
  Author: Neal Fennimore
  Author URI: http://neal.codes
  License: GPL V3
 */

if ( ! defined( 'WPINC' ) ) {
  die;
}

class Classy_API {
  private static $api_endpoint = 'https://www.stayclassy.org/api1/',
                 $cid = CLASSY_CHARITY_ID,
                 $token = CLASSY_TOKEN;

// ================================================
//                   Campaigns
// ================================================

  /**
   * [get_campaigns Curl to classy api to get all campaigns]
   * @param  [obj] $opts [Optional parameters for /campaigns]
   * @return [arr of objs] [Returns all campaigns as objects]
   */
  public static function get_campaigns($opts=NULL){
    $opts = func_get_args();
    $url = self::create_url('campaigns', $opts);
    $data = self::fetch_data($url);
    return $data['campaigns'];
  }

  /**
   * [get_campaign Curl to classy api to get info on a campaign]
   * @param  [int] $eid [Need an event ID]
   * @return [arr of objs] [Returns campaign info]
   */
  public static function get_campaign($eid=NULL){
    if (!is_int($eid)) { return self::throw_error('Need a valid event ID', __LINE__); }
    $url = self::create_url('campaign-info', array('eid' => $eid));
    $data = self::fetch_data($url);
    return $data;
  }

// ================================================
//                  Fundraisers
// ================================================

  /**
   * [get_fundraisers Curl to classy api to get all fundraisers]
   * @param  [obj] $opts [Optional parameters for /fundraisers]
   * @return [arr of objs] [Returns all fundraisers as objects]
   */
  public static function get_fundraisers($opts=NULL){
    $opts = func_get_args();
    $url = self::create_url('fundraisers', $opts);
    $data = self::fetch_data($url);
    return $data['fundraisers'];
  }

  /**
   * [get_fundraiser Curl to classy api to get info on a fundraiser]
   * @param  [int] $fcid [Need a fundraiser campaign ID]
   * @return [arr of objs] [Returns fundraiser info]
   */
  public static function get_fundraiser($fcid=NULL){
    if (!is_int($fcid)) { return self::throw_error('Need a valid fundraising campaign ID', __LINE__); }
    $url = self::create_url('fundraiser-info', array('fcid' => $fcid));
    $data = self::fetch_data($url);
    return $data;
  }


// ================================================
//                  Donations
// ================================================

  /**
   * [get_donations Curl to classy api to get donations]
   * @param  [obj] $opts [Optional parameters for /donations]
   * @return [arr of objs]       [Returns donations]
   */
  public static function get_donations($opts=NULL){
    $opts = func_get_args();
    $url = self::create_url('donations', $opts);
    $data = self::fetch_data($url);
    return $data['donations'];
  }

  /**
   * [get_recurring Curl to classy api to get recurring donations]
   * @param  [obj] $opts [Optional parameters for /recurring]
   * @return [arr of objs]       [Returns recurring donations]
   */
  public static function get_recurring($opts=NULL){
    $opts = func_get_args();
    $url = self::create_url('recurring', $opts);
    $data = self::fetch_data($url);
    return $data['profiles'];
  }

// ================================================
//                      Teams
// ================================================

  /**
   * [get_teams Curl to classy api to get teams]
   * @param  [obj] $opts [Optional parameters for /teams]
   * @return [arr of objs]       [Returns teams]
   */
  public static function get_teams($opts=NULL){
    $opts = func_get_args();
    $url = self::create_url('teams', $opts);
    $data = self::fetch_data($url);
    return $data['teams'];
  }

  /**
   * [get_team Curl to classy api to get info on a team]
   * @param  [int] $ftid [Need fundraising team ID]
   * @return [arr of objs] [Returns team info]
   */
  public static function get_team($ftid=NULL){
    if (!is_int($ftid)) { return self::throw_error('Need a valid fundraising team ID', __LINE__); }
    $url = self::create_url('team-info', array('ftid' => $ftid));
    $data = self::fetch_data($url);
    return $data;
  }

// ================================================
//                    Projects
// ================================================

  /**
   * [get_project Curl to classy api to get info on a project]
   * @param  [int] $pid [Need project ID]
   * @return [arr of objs] [Returns project info]
   */
  public static function get_project($pid=NULL){
    if (!is_int($pid)) { return self::throw_error('Need a valid PID', __LINE__); }
    $url = self::create_url('project-info', array('pid' => $pid));
    $data = self::fetch_data($url);
    return $data;
  }

// ================================================
//                    Facebook
// ================================================

  /**
   * [get_facebook Curl to classy api to get info on a facebook activity]
   * @param  [int || array] $opts [Need facebook user ID(fb_uid) or list of friends IDs (friends_ids)]
   * @return [arr of objs] [Returns fundraisers and/or donations for given ID]
   */
  public static function get_fb_activity($opts=NULL){
    $opts = func_get_args();
    $url = self::create_url('fb-friend-activity', $opts);
    $data = self::fetch_data($url);
    return $data;
  }

// ================================================
//                     Classy
// ================================================

  /**
   * [create_params Creates params for Classy]
   * @param  [obj] $args [params of str and val]
   * @return [str] [Returns concatenated params as http ready string]
   */
  private static function create_params($args=NULL){
    $main_params = array('cid' => self::$cid,  'token' => self::$token);

    if ( is_array($args) && !empty($args) ) {
      $params = array_merge($main_params, $args);
    } else {
      $params = $main_params;
    }
    return '?' . http_build_query($params);
  }

  /**
   * [create_url Creates the Classy URL]
   * @param  string $queryType [Top level collection]
   * @param  [arr of objs || obj] $opts [Params being passed]
   * @return [str]             [URL]
   */
  private static function create_url($queryType='', $opts=NULL){
    if( empty($queryType) || !is_string($queryType) ){
      return self::throw_error('No query string', __LINE__);
    } elseif ( $opts !== NULL && !is_array($opts) ) {
      return self::throw_error('Options is not an array', __LINE__);
    }

    if ( isset($opts[0]) && is_array($opts[0]) && !empty($opts[0])) {
      $url = self::$api_endpoint . $queryType . self::create_params($opts[0]);
    } elseif (is_array($opts) && !empty($opts)) {
      $url = self::$api_endpoint . $queryType . self::create_params($opts);
    } else {
      $url = self::$api_endpoint . $queryType . self::create_params();
    }
    return $url;
  }

  /**
   * [fetch_data Get data via a curl request]
   * @param  [str] $url [The url to request]
   * @return [arr || obj] [Depends on api request]
   */
  private static function fetch_data($url=NULL) {
    if(!is_string($url)){ return self::throw_error('No URL', __LINE__); }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    $result = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($result, true);
    return $data;
  }

  /**
   * [throw_error Sends errors to wp-content/debug.log when WP_DEBUG_LOG & WP_DEBUG are true]
   * @param  [str] $message [Error message]
   * @param  [int || str] $line [Line number]
   */
  private static function throw_error($message, $line='unknown') {
    if (WP_DEBUG === true) {
      if (is_array($message) || is_object($message)) {
        error_log(print_r($message, true));
      } else {
        error_log('Classy API: ' . $message . ' on line ' . $line);
      }
    }
  }

}