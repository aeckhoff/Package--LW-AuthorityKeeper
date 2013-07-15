<?php

/**************************************************************************
*  Copyright notice
*
*  Copyright 2013 Logic Works GmbH
*
*  Licensed under the Apache License, Version 2.0 (the "License");
*  you may not use this file except in compliance with the License.
*  You may obtain a copy of the License at
*
*  http://www.apache.org/licenses/LICENSE-2.0
*  
*  Unless required by applicable law or agreed to in writing, software
*  distributed under the License is distributed on an "AS IS" BASIS,
*  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
*  See the License for the specific language governing permissions and
*  limitations under the License.
*  
***************************************************************************/

namespace LwAuthorityKeeper;

class LwAuthorityKeeper
{
    private $AuthorityNameSpace;
    private $loggedIn;
    private $SessionData;

    public function __construct($AuthorityNameSpace = "default")
    {
        $this->AuthorityNameSpace = $AuthorityNameSpace;
        $ok = $this->checkSession();
        if ($ok) {
            $this->loadData();
        }
    }
    
    private function checkSession()
    {
        if (isset($_SESSION[$this->AuthorityNameSpace]) && is_array($_SESSION[$this->AuthorityNameSpace]['SessionData']) && count($_SESSION[$this->AuthorityNameSpace]['SessionData'])>0) {
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
        if ($this->AuthorityNameSpace == "default") {
            session_unset();
            session_destroy();
        } 
        else {
            unset($_SESSION[$this->AuthorityNameSpace]);
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
    	$_SESSION[$this->AuthorityNameSpace]['SessionData'] = $SessionData;
    }
    
    private function getSession()
    {
        return $_SESSION[$this->AuthorityNameSpace]['SessionData'];
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
        $_SESSION[$this->AuthorityNameSpace]['additional'][$key] = $value;
    }
    
    public function getAdditionalVars($key)
    {
        return $_SESSION[$this->AuthorityNameSpace]['additional'][$key];
    }
    
    public function unsetAdditionalVars($key)
    {
        $_SESSION[$this->AuthorityNameSpace]['additional'][$key] = false;
    }    
}
