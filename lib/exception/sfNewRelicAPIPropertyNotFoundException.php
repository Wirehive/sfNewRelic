<?php


/**
 * Class sfNewRelicAPIPropertyNotFoundException
 *
 * Handle method not found exceptions
 *
 * @author Robin Corps <robin@wirehive.net>
 */
class sfNewRelicAPIPropertyNotFoundException extends sfException
{
  protected $api;
  protected $property;


  public function __construct($message, $property, $api)
  {
    parent::__construct($message, 501);

    $this->setApi($api);

    $this->setProperty($property);
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
  public function getProperty()
  {
    return $this->property;
  }


  /**
   * @param mixed $method
   */
  public function setProperty($property)
  {
    $this->property = $property;
  }


} 