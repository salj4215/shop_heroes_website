<?php

/**
 * Defines the Credit Card Authorization API Client Interface.
 */
interface CCAuthApiInterface {

  /**
   * Create a new request.
   *
   * @param array $values
   *   An associative array of values to create the request.
   */
  public static function create($values = []);

  /**
   * Returns a response from the server.
   *
   * @return stdClass
   *   The response object.
   */
  public function response();

}

/**
 * Credit Card Authorization API Client.
 */
class CCAuthApi implements CCAuthApiInterface {

  /**
   * The request object.
   *
   * @var stdClass
   */
  private $request;

  /**
   * Construct the object.
   */
  public function __construct($values = []) {
    $this->config = self::getConfig();
    $this->request = new stdClass();
    foreach ($values as $key => $value) {
      $this->request->$key = $value;
    }
  }

  /**
   * Create a new request.
   *
   * @param array $values
   *   An associative array of values to create the request.
   */
  public static function create($values = []) {
    return new CCAuthApi($values);
  }

  /**
   * Read the configuration file.
   */
  private static function getConfig() {
    $file = dirname(__FILE__) . '/config.php';
    if (file_exists($file)) {
      require_once($file);
      return $config;
    }
    else {
      die("<div><strong>Configuration Error! Please create config.php and place it in the same folder with " . __FILE__ . "</strong></div>");
    }
  }

  /**
   * Returns a response from the server.
   *
   * @return stdClass
   *   The response object.
   */
  public function response() {
    return $this->requestTransaction();
  }

  /**
   * Connect to server and request a transaction.
   */
  private function requestTransaction() {
    $this->request->merchant_id = $this->config['merchant_id'];
    $this->request->transaction_key = $this->config['transaction_key'];

    $payload = json_encode($this->request);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $this->config['api_url']);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
    curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    $result = curl_exec ($curl);
    curl_close ($curl);
    return json_decode($result);
  }
}
