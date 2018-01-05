<?php // -->
/**
 * GoIP Client/Server Package based on
 * GoIP SMS Gateway Interface.
 *
 * (c) 2017 April Sacil
 *
 * Copyright and license information can be found at LICENSE.txt
 * distributed with this package.
 */

namespace GoIP;

/**
 * Base Class
 *
 * @package  GoIP
 * @author   April Sacil <aprilvsacil@gmail.com>
 * @standard PSR-2
 */
class Base
{
    public $goip;

    /**
     * Initialize the client connection
     * given the host, port, username and password
     *
     * @param GoIp|null $goip
     */
    public function __construct(GoipClient $goip)
    {
        $this->goip = $goip;
    }

    /**
     * Does a curl in the GoIP GSM Modem
     *
     * @param string $route
     * @param array $params
     * @return resource
     */
    public function connect(string $route, array $params = [], array $data = [])
    {
        $url  = "http://" . $this->goip->host . '/default/en_US';
        $url .= $route . '?' . http_build_query($params);
        $user = $this->goip->username . ":" . $this->goip->password;
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_USERPWD, $user);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_PORT, $this->goip->port);

        if ($data) {
            curl_setopt($curl, CURLOPT_POST, count($data));
            curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        $results = curl_exec($curl);
        curl_close($curl);
        
        return $results;
    }
}
