<?php

/**
 * Description of REST
 *
 * @author Ramón Serrano <ramon.calle.88@gmail.com>
 */

namespace MVC\REST;

class REST
{
    
    /**
     * @var REST
     */
    static $instance;

    /**
     * @var string $baseUrl
     * @access public 
     */
    public $baseUrl = "http://localhost/";
    
    /**
     * @return REST
     */
    public function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param string $uri
     * @param string $method
     * @param string $content_type
     * 
     * @return resource
     */
    private function getConnect($uri, $method, $content_type)
    {
        $connect = curl_init($this->baseUrl . $uri);

        curl_setopt($connect, CURLOPT_USERAGENT, "MVC REST by Ramón Serrano");
        curl_setopt($connect, CURLOPT_SSLVERSION, 3);
        curl_setopt($connect, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($connect, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($connect, CURLOPT_HTTPHEADER, array("Accept: application/json", "Content-Type: " . $content_type));

        return $connect;
    }

    /**
     * @param resource $connect
     * @param type $data
     * @param string $content_type
     * 
     * @throws \Exception
     */
    private function setData(&$connect, $data, $content_type)
    {
        if ($content_type == "application/json") {
            if (gettype($data) == "string") {
                json_decode($data, true);
            } else {
                $data = json_encode($data);
            }

            if (function_exists('json_last_error')) {
                $json_error = json_last_error();
                if ($json_error != JSON_ERROR_NONE) {
                    throw new Exception("JSON Error [{$json_error}] - Data: {$data}");
                }
            }
        }

        curl_setopt($connect, CURLOPT_POSTFIELDS, $data);
    }

    /**
     * @param string $method
     * @param string $uri
     * @param type $data
     * @param string $content_type
     * 
     * @return array
     * 
     * @throws \Exception
     */
    private function exec($method, $uri, $data, $content_type)
    {
        $connect = $this->getConnect($uri, $method, $content_type);
        if ($data) {
            $this->setData($connect, $data, $content_type);
        }

        $api_result = curl_exec($connect);
        $api_http_code = curl_getinfo($connect, CURLINFO_HTTP_CODE);
        $api_http_time = curl_getinfo($connect, CURLINFO_TOTAL_TIME);

        $response = array(
            "status" => $api_http_code,
            "total_time" => $api_http_time,
        );

        if ($content_type == "application/json") {
            $response["response"] = json_decode($api_result, true);
        } elseif ($content_type == "text/html") {
            $response["response"] = $api_result;
        }

        if ($response['status'] >= 400) {
            throw new Exception($response['response'], $response['status']);
        }

        curl_close($connect);

        return $response;
    }

    /**
     * @param string $uri
     * @param mixed $data
     * @param string $content_type
     * 
     * @return array
     */
    public function get($uri, $data, $content_type = "application/json")
    {
        return $this->exec("GET", $uri, $data, $content_type);
    }

    /**
     * @param string $uri
     * @param mixed $data
     * @param string $content_type
     * 
     * @return array
     */
    public function post($uri, $data, $content_type = "application/json")
    {
        return $this->exec("POST", $uri, $data, $content_type);
    }

    /**
     * @param string $uri
     * @param mixed $data
     * @param string $content_type
     * 
     * @return array
     */
    public function put($uri, $data, $content_type = "application/json")
    {
        return $this->exec("PUT", $uri, $data, $content_type);
    }
    
    /**
     * @param string $uri
     * @param mixed $data
     * @param string $content_type
     * 
     * @return array
     */
    public function delete($uri, $data, $content_type = "application/json")
    {
        return $this->exec("DELETE", $uri, $data, $content_type);
    }

}
