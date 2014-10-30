<?php


/**
 * Class sfNewRelicAPI
 *
 * A Symfony 1.4 wrapper to the New Relic API libraries.
 * Currently only supports the Partner v2 API library as provided.
 *
 * @author Robin Corps <robin@wirehive.net>
 * @version 0.1
 */
class sfNewRelicAPI
{
  const PARTNER_API = 1;

  const STAGING = 1;
  const LIVE    = 2;

  private $logging;
  private $api;


  public function __construct($type, $mode = null, $logging = true)
  {
    switch ($mode)
    {
      case self::STAGING:
        $config = sfConfig::get('app_newrelic_staging');
        break;

      default:
      case self::LIVE:
        $config = sfConfig::get('app_newrelic_live');
        break;
    }

    switch ($type)
    {
      default:
      case self::PARTNER_API:
        $api = new NewRelicPartnerAPI($config['partner_id'], $config['api_key'], $mode);
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
   * Magic method to call functions on the wrapper API library
   *
   * @param $method
   * @param $args
   *
   * @return mixed
   * @throws sfNewRelicAPIMethodNotFoundException
   */
  public function __call($method, $args)
  {
    $api = $this->getApi();

    if (method_exists($api, $method))
    {
      $result = call_user_func_array(array($api, $method), $args);

      if ($this->getLogging())
      {
        $this->log(get_class($api), $method, $args, $result, $api->get);
      }

      return $result;
    }

    throw new sfNewRelicAPIMethodNotFoundException(
      'The method "' . $method . '" does not exist for the API ' . get_class($api),
      $method,
      $api
    );
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
   *
   * @return sfNewRelicAPI
   */
  public function setLogging($state)
  {
    $this->logging = $state;

    return $this;
  }


  /**
   * Convenience method for enabling logging
   *
   * @return sfNewRelicAPI
   */
  public function enableLogging()
  {
    $this->setLogging(true);

    return $this;
  }


  /**
   * Convenience method for disabling logging
   *
   * @return sfNewRelicAPI
   */
  public function disableLogging()
  {
    $this->setLogging(false);

    return $this;
  }


  /**
   * Log API call to the database
   *
   * @param $api_class
   * @param $method
   * @param $args
   * @param $result
   * @param $url
   */
  protected function log($api_class, $method, $args, $result, $url)
  {
    $log = new NewRelicLog();
    $log->setApi($api_class);
    $log->setMethod($method);
    $log->setContentSent(json_encode($args));
    $log->setContentReceived(json_encode($result));
    $log->setUrl($url);
    $log->save();
  }
}