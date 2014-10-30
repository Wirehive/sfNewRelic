<?php


class sfNewRelicAPI
{
  const PARTNER_API = 1;

  const STAGING = 1;
  const LIVE    = 2;

  private $logging;
  private $api;


  public function __construct($type, $mode = self::LIVE, $logging = true)
  {
    switch ($mode)
    {
      case self::STAGING:
        $partner_id = sfConfig::get('app_newrelic_staging_partner_id');
        $api_key    = sfConfig::get('app_newrelic_staging_api_key');
        break;

      default:
      case self::LIVE:
        $partner_id = sfConfig::get('app_newrelic_live_partner_id');
        $api_key    = sfConfig::get('app_newrelic_live_api_key');
        break;
    }

    switch ($type)
    {
      default:
      case self::PARTNER_API:
        $api = new NewRelicPartnerAPI($partner_id, $api_key, $mode);
        $this->setApi($api);
        break;
    }

    $this->setLogging($logging);

    if ($logging)
    {
      $api->setCurlOpt(CURLOPT_VERBOSE, 1);
    }
  }


  /**
   * @return mixed
   */
  public function getApi()
  {
    return $this->api;
  }


  /**
   * @param mixed $api
   */
  public function setApi($api)
  {
    $this->api = $api;
  }


  /**
   * Get the whether logging is turned on or off
   *
   * @return mixed
   */
  public function getLogging()
  {
    return $this->logging;
  }


  /**
   * Set logging on or off
   *
   * @param $state
   */
  public function setLogging($state)
  {
    $this->logging = $state;

    return $this;
  }


  /**
   * Convenience method for enabling logging
   *
   * @return sfNewRelic
   */
  public function enableLogging()
  {
    $this->setLogging(true);

    return $this;
  }


  /**
   * Convenience method for disabling logging
   *
   * @return sfNewRelic
   */
  public function disableLogging()
  {
    $this->setLogging(false);

    return $this;
  }
}