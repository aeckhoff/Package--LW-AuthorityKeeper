Package--LW-AuthorityKeeper
===========================

Instantiate a new AuthorityKeeper with "internal" namespace

    $authKeeper = new \LwAuthorityKeeper\LwAuthorityKeeper('testnamespace');


Execute login and set data array for logged in user:
    
    $authKeeper->login(array("name"=>"tester"))


Check if someone is logged in:

    $authKeeper->isLoggedIn()


Get specific Data from logged in User:

    $authKeeper->getValueByKey("name")


Get All Data of logged in user:

    $authKeeper->getValues()


Logout User:

    $authKeeper->logout()


