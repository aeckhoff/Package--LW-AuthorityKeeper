<?php

namespace LwAuthorityKeeper;

class LwAuthorityKeeper
{
    public function __construct($name = "default")
    {
        $this->sessionIdentifier = $name;
        $ok = $this->checkSession();
        if ($ok) {
            $this->loadData();
        }
    }
    
    private function checkSession()
    {
        if (isset($_SESSION[$this->sessionIdentifier]) && is_array($_SESSION[$this->sessionIdentifier]['SessionData']) && count($_SESSION[$this->sessionIdentifier]['SessionData'])>0) {
            if (\lw_security::checkSession()) {
                $this->loggedIn = true;
				return true;
            } 
            else {
                $this->SessionData = false;
                $this->loggedIn = false;
                $this->destroySession();
                return false;
            }
        }
        else {
            $this->SessionData = false;
            return false;
        }
    }    
    
    private function destroySession()
    {
        if ($this->sessionIdentifier == "default") {
            session_unset();
            session_destroy();
        } 
        else {
            unset($_SESSION[$this->sessionIdentifier]);
        }
    }    
    
    public function loadData()
    {
        if ($this->loggedIn == true) {
            if (!is_array($this->SessionData) || count($this->SessionData)<1) {
                $this->SessionData = $this->getSession();
            }
            return true;
        }
        else {
            return false;
        }
    }    

    public function login($SessionData)
    {
        session_regenerate_id(true);
        session_unset();
        $this->loggedIn = true;
        $this->SessionData = $SessionData;
        $this->setSession($SessionData);
    }
    
    public function logout()
    {
        $this->loggedIn = false;
        $this->destroySession();
    }
    
    private function setSession($SessionData)
    {
    	$_SESSION[$this->sessionIdentifier]['SessionData'] = $SessionData;
    }
    
    private function getSession()
    {
        return $_SESSION[$this->sessionIdentifier]['SessionData'];
    }
    
    public function isLoggedIn()
    {
        return $this->loggedIn;
    }
    
    public function getValueByKey($key)
    {
        return $this->SessionData[$key];
    }
    
    public function getValues()
    {
        return $this->SessionData;
    }
    
    public function setAdditionalVars($key, $value)
    {
        $_SESSION[$this->sessionIdentifier]['additional'][$key] = $value;
    }
    
    public function getAdditionalVars($key)
    {
        return $_SESSION[$this->sessionIdentifier]['additional'][$key];
    }
    
    public function unsetAdditionalVars($key)
    {
        $_SESSION[$this->sessionIdentifier]['additional'][$key] = false;
    }    
}
