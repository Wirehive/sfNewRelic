<?php


/**
 * Class sfNewRelicAPIMethodNotFoundException
 *
 * Handle method not found exceptions
 *
 * @author Robin Corps <robin.corps@wirehive.com>
 */
class sfNewRelicAPIMethodNotFoundException extends sfException
{
  protected $api;
  protected $method;


  public function __construct($message, $method, $api)
  {
    parent::__construct($message, 501);

    $this->setApi($api);

    $this->setMethod($method);
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
   * @return mixed
   */
  public function getMethod()
  {
    return $this->method;
  }


  /**
   * @param mixed $method
   */
  public function setMethod($method)
  {
    $this->method = $method;
  }


} 