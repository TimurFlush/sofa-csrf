<?php

namespace TimurFlush\SofaCsrf;

/**
 * Class Protection
 * @package TimurFlush\SofaCsrf
 * @version 2.0.0
 * @author Timur Flush
 */
class Protection
{
    /**
     * @var \Phalcon\Session\AdapterInterface
     */
    private $_session;

    /**
     * @var string
     */
    private $_prefix;

    /**
     * Protection constructor.
     * @param \Phalcon\Session\AdapterInterface $adapter
     * @param string $prefix
     */
    public function __construct(\Phalcon\Session\AdapterInterface $adapter, string $prefix = '')
    {
        $this->_session = $adapter;
        $this->_prefix = $prefix;

        if (!$this->tokenExists())
            $this->generateToken();
    }

    /**
     * @param string $prefix
     */
    public function generateToken(string $prefix = null) : void
    {
        $prefix = $prefix ?? $this->_prefix;

        $random = new \Phalcon\Security\Random();

        $this->_session->set(__CLASS__ . $prefix, [
            'key' => 'TF' . $random->hex(30),
        ]);
    }

    /**
     * @param string $prefix
     * @param string $tokenKey
     * @param string $tokenValue
     * @return bool
     */
    public function checkToken(string $tokenKey, string $prefix = null) : bool
    {
        $prefix = $prefix ?? $this->_prefix;

        if ($this->_session->has(__CLASS__ . $prefix)) {
            $token = $this->_session->get(__CLASS__ . $prefix);
            if ($token['key'] == $tokenKey)
                return true;
        }

        return false;
    }

    /**
     * @param string $prefix
     * @return bool
     */
    public function tokenExists(string $prefix = null) : bool
    {
        $prefix = $prefix ?? $this->_prefix;

        if ($this->_session->has(__CLASS__ . $prefix))
            return true;

        return false;
    }

    /**
     * @param string $prefix
     * @return array|bool
     */
    public function getToken(string $prefix = null)
    {
        $prefix = $prefix ?? $this->_prefix;

        if ($this->_session->has(__CLASS__ . $prefix)) {
            $token = $this->_session->get(__CLASS__ . $prefix);
            return $token['key'];
        }

        return false;
    }
    
    /**
     * @return string
     */
    public function html() : string
    {
        return "<input type=\"hidden\" name=\"csrf_token\" value=\"{$this->getToken()}\" />";
    }
}