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
 * Client Class
 *
 * @package  GoIP
 * @author   April Sacil <aprilvsacil@gmail.com>
 * @standard PSR-2
 */
class GoipClient
{
    /**
     * Default host.
     *
     * @var string
     */
    public $host = null;

    /**
     * Default port / channel.
     *
     * @var int
     */
    public $port = null;

    /**
     * Default password.
     *
     * @var string
     */
    public $password = null;

    /**
     * Default username.
     *
     * @var string
     */
    public $username = null;

    /**
     * Current send id.
     *
     * @var int | null
     */
    public $id = null;

    /**
     * Debug flag.
     *
     * @var bool
     */
    public $debug = false;

    /**
     * Initialize the client connection
     * given the host, port, username and password.
     * $host can be array of arguments.
     *
     * @param string|array $host
     * @param string       $username
     * @param string       $password
     * @param int          $port
     */
    public function __construct($host, $username = '', $password = '', $port = 80)
    {
        if (is_array($host)) {
            // set the host
            $this->host = $host['host'];
            // set the username
            $this->username = $host['username'];
            // set the password
            $this->password = $host['password'];
            // set the port
            if (isset($host['port'])) {
                $this->port = $host['port'];
            } else {
                $this->port = $port;
            }
        } else {
            // set the host
            $this->host = $host;
            // set the port
            $this->port = $port;
            // set the username
            $this->username = $username;
            // set the password
            $this->password = $password;
        }
    }

    /**
     * Set host.
     *
     * @param   string
     * @return  $this
     */
    public function setHost($host)
    {
        // set the host
        $this->host = $host;

        return $this;
    }

    /**
     * Set port.
     *
     * @param   int
     * @return  $this
     */
    public function setPort($port = 80)
    {
        // set the port / channel
        $this->port = $port;

        return $this;
    }

    /**
     * Set password.
     *
     * @param   string
     * @return  $this
     */
    public function setPassword($password)
    {
        // set the password
        $this->password = $password;

        return $this;
    }

    /**
     * Set username.
     *
     * @param   string
     * @return  $this
     */
    public function setUsername($username)
    {
        // set the username
        $this->username = $username;

        return $this;
    }

    /**
     * Set send id.
     *
     * @param   int
     * @return  $this
     */
    public function setId($id)
    {
        // set the id
        $this->id = $id;

        return $this;
    }

    /**
     * Set debugging.
     *
     * @param   bool
     * @return  $this
     */
    public function setDebug($debug = false)
    {
        // set debug flag
        $this->debug = $debug;

        return $this;
    }
}
