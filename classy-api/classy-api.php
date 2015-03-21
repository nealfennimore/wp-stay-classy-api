<?php

/*
  Plugin Name: Classy API
  Description: Classy API wrapper
  Version: 0.1.2
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
//                   Account
// ================================================

  /**
   * /account-info
   * [get_account_info Curl to classy api to account information]
   * @return [array] [Returns account info]
   */
  public static function get_account_info(){
    $data = self::api_handler('account-info');
    return $data;
  }

  /**
   * /account-activity
   * [get_account_activity Curl to classy api to get all account activity]
   * @param  [array/hash] $opts [Optional parameters]
   * @return [array] [Returns account activity]
   */
  public static function get_account_activity($opts=NULL){
    $data = self::api_handler('account-activity', $opts);
    return $data['activity'];
  }

  /**
   * /account-sponsor-matching
   * [get_campaign_sponsors Curl to classy api to get information about matching sponsors for a campaign/event]
   * @param  [int] $eid [Need an event ID]
   * @return [array] [Returns matching sponsors for a campaign/event]
   */
  public static function get_campaign_sponsors($eid=NULL){
    if (!is_int($eid)) { return self::throw_error('Need a valid event ID', __LINE__); }
    $data = self::api_handler('account-sponsor-matching', array('eid' => $eid));
    return $data['sponsors'];
  }


// ================================================
//                   Campaigns
// ================================================

  /**
   * /campaigns
   * [get_campaigns Curl to classy api to get all campaigns]
   * @param  [array/hash] $opts [Optional parameters]
   * @return [array] [Returns all campaigns]
   */
  public static function get_campaigns($opts=NULL){
    $data = self::api_handler('campaigns', $opts);
    return $data['campaigns'];
  }

  /**
   * /campaign-info
   * [get_campaign Curl to classy api to get info on a campaign]
   * @param  [int] $eid [Need an event ID]
   * @return [array] [Returns campaign info]
   */
  public static function get_campaign($eid=NULL){
    if (!is_int($eid)) { return self::throw_error('Need a valid event ID', __LINE__); }
    $data = self::api_handler('campaign-info', array('eid' => $eid));
    return $data;
  }

  /**
   * /campaign-tickets
   * [get_campaign_tickets Curl to classy api to get tickets on a campaign]
   * @param  [int] $eid [Need an event ID]
   * @return [array] [Returns campaign tickets]
   */
  public static function get_campaign_tickets($eid=NULL){
    if (!is_int($eid)) { return self::throw_error('Need a valid event ID', __LINE__); }
    $data = self::api_handler('campaign-tickets', array('eid' => $eid));
    return $data['tickets'];
  }

// ================================================
//                  Fundraisers
// ================================================

  /**
   * /fundraisers
   * [get_fundraisers Curl to classy api to get all fundraisers]
   * @param  [array/hash] $opts [Optional parameters]
   * @return [array] [Returns all fundraisers as an array]
   */
  public static function get_fundraisers($opts=NULL){
    $data = self::api_handler('fundraisers', $opts);
    return $data['fundraisers'];
  }

  /**
   * /fundraiser-info
   * [get_fundraiser Curl to classy api to get info on a fundraiser]
   * @param  [int] $fcid [Need a fundraiser campaign ID]
   * @return [array] [Returns fundraiser info]
   */
  public static function get_fundraiser($fcid=NULL){
    if (!is_int($fcid)) { return self::throw_error('Need a valid fundraising campaign ID', __LINE__); }
    $data = self::api_handler('fundraiser-info', array('fcid' => $fcid));
    return $data;
  }

// ================================================
//                      Teams
// ================================================

  /**
   * /teams
   * [get_teams Curl to classy api to get teams]
   * @param  [array/hash] $opts [Optional parameters]
   * @return [array] [Returns teams]
   */
  public static function get_teams($opts=NULL){
    $data = self::api_handler('teams', $opts);
    return $data['teams'];
  }

  /**
   * /team-info
   * [get_team Curl to classy api to get info on a team]
   * @param  [int] $ftid [Need fundraising team ID]
   * @return [array] [Returns team info]
   */
  public static function get_team($ftid=NULL){
    if (!is_int($ftid)) { return self::throw_error('Need a valid fundraising team ID', __LINE__); }
    $data = self::api_handler('team-info', array('ftid' => $ftid));
    return $data;
  }

// ================================================
//                  Donations
// ================================================

  /**
   * /donations
   * [get_donations Curl to classy api to get donations]
   * @param  [array/hash] $opts [Optional parameters]
   * @return [array] [Returns donations]
   */
  public static function get_donations($opts=NULL){
    $data = self::api_handler('donations', $opts);
    return $data['donations'];
  }

  /**
   * /recurring
   * [get_recurring Curl to classy api to get recurring donations]
   * @param  [array/hash] $opts [Optional parameters]
   * @return [array] [Returns recurring donations]
   */
  public static function get_recurring($opts=NULL){
    $data = self::api_handler('recurring', $opts);
    return $data['profiles'];
  }

// ================================================
//                    Projects
// ================================================

  /**
   * /project-info
   * [get_project Curl to classy api to get info on a project]
   * @param  [int] $pid [Need project ID]
   * @return [array] [Returns project info]
   */
  public static function get_project($pid=NULL){
    if (!is_int($pid)) { return self::throw_error('Need a valid PID', __LINE__); }
    $data = self::api_handler('project-info', array('pid' => $pid));
    return $data;
  }

// ================================================
//                    Facebook
// ================================================

  /**
   * /fb-friend-activity
   * [get_facebook Curl to classy api to get info on a facebook activity]
   * @param  [int || array/hash] $opts [Need facebook user ID(fb_uid) or list of friends IDs (friends_ids)]
   * @return [arrays] [Returns fundraisers and/or donations for given ID]
   */
  public static function get_fb_activity($opts=NULL){
    $data = self::api_handler('fb-friend-activity', $opts);
    return $data;
  }

// ================================================
//                   Classy API
// ================================================

  /**
   * [api_handler description]
   * @param  [str] $queryType [Top level collection i.e. /recurring /fundraisers /teams]
   * @param  [array/hash] $opts [Options for the query: array('limit' => 2, 'eid' => 000000)]
   * @return [array] [Data from API call]
   */
  private static function api_handler($queryType=NULL, $opts=NULL){
    $url = self::create_url($queryType, $opts);
    $data = self::fetch_data($url);
    return $data;
  }

  /**
   * [create_url Creates the Classy URL]
   * @param  string $queryType [Top level collection]
   * @param  [array/hash || obj] $opts [Options for the query: array('limit' => 2, 'eid' => 000000)]
   * @return [str]             [URL]
   */
  private static function create_url($queryType='', $opts=NULL){
    if( empty($queryType) || !is_string($queryType) ){
      return self::throw_error('No query string', __LINE__);
    } elseif ( $opts !== NULL && !is_array($opts) ) {
      return self::throw_error('Options is not an array', __LINE__);
    }

    if (is_array($opts) && !empty($opts)) {
      $url = self::$api_endpoint . $queryType . self::create_params($opts);
    } else {
      $url = self::$api_endpoint . $queryType . self::create_params();
    }
    return $url;
  }

  /**
   * [create_params Creates params for Classy]
   * @param  [array/hash] $args [params of str and val]
   * @return [str] [Returns concatenated params as http ready string]
   */
  private static function create_params($opts=NULL){
    $main_params = array('cid' => self::$cid,  'token' => self::$token);

    if ( is_array($opts) && !empty($opts) ) {
      $params = array_merge($main_params, $opts);
    } else {
      $params = $main_params;
    }
    return '?' . http_build_query($params);
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