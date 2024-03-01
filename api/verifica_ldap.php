<?php

//ini_set('display_errors', true);
//
//	error_reporting(E_ALL);
// Enable LDAP authentication (false by default)
function testar_login($login, $senha) {
    define('LDAP_AUTH', true);

//define('LDAP_SERVER', '192.168.133.10');
    define('LDAP_SERVER', '192.168.22.30');
    define('LDAP_PORT', 389); //para alterar a senha utilizar a porta 636
    define('LDAP_SSL_VERIFY', false);
    define('LDAP_START_TLS', false);
    define('LDAP_USERNAME_CASE_SENSITIVE', false);

    define('LDAP_BIND_TYPE', 'proxy');
//define('LDAP_USERNAME', 'kanboard.user@dcro.gov');
    define('LDAP_USERNAME', 'desenvolvimento@dcro.gov');
    define('LDAP_PASSWORD', 'ostras');

    define('LDAP_USER_BASE_DN', 'DC=dcro,DC=gov');
    define('LDAP_USER_FILTER', '(&(objectClass=user)(sAMAccountName=%s))');

    define('LDAP_USER_ATTRIBUTE_USERNAME', 'sAMAccountName');
    define('LDAP_USER_ATTRIBUTE_FULLNAME', 'displayname');
    define('LDAP_USER_ATTRIBUTE_EMAIL', 'mail');
    define('LDAP_USER_ATTRIBUTE_GROUPS', 'memberof');
//define('LDAP_USER_ATTRIBUTE_PHOTO', '');
//define('LDAP_USER_ATTRIBUTE_LANGUAGE', '');

    define('LDAP_USER_CREATION', true);

    define('LDAP_GROUP_ADMIN_DN', 'CN=Kanboard_admin,OU=Kanboard,DC=dcro,DC=gov');
    define('LDAP_GROUP_MANAGER_DN', 'CN=Kanboard_gerente,OU=Kanboard,DC=dcro,DC=gov');

    define('LDAP_GROUP_PROVIDER', true);
    define('LDAP_GROUP_BASE_DN', 'DC=dcro,DC=gov');
    define('LDAP_GROUP_FILTER', '(&(objectClass=group)(sAMAccountName=%s*))');
    define('LDAP_GROUP_ATTRIBUTE_NAME', 'cn');
    $data = date('Y-m-d');
    //  $fp = fopen("testalogin_ldapGERAL_".$data.".txt", 'a+');
    $_REQUEST['username'] = $login; // "maria.figueiredo";//
    $_REQUEST['password'] = $senha; // "AnnaLubia@2021";//
//
    //fwrite($fp, "login". $_REQUEST['username']. " senha".$_REQUEST['password']);
//print_r($_REQUEST);
    $logado = 0;
    $usuario = "";
    $cpf = "";
    IF (isset($_REQUEST['username']) AND $_REQUEST['password']) {

        $ldaprdn = trim($_REQUEST['username']);     // ldap rdn or dn
        $ldappass = trim($_REQUEST['password']);  // associated password
//print $ldappass." login".$ldaprdn;
        $ldapconn = ldap_connect("ldap://192.168.22.30", 389)
                or die("não conector LDAP server.");

        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);

        $wldapconn = (ldap_bind($ldapconn, "$ldaprdn@dcro.gov", "$ldappass"));
//print_r("conn".$wldapconn);
        if ($wldapconn) {


            $ldapbind = (ldap_bind($ldapconn, "$ldaprdn@dcro.gov", "$ldappass"));
//print_r($ldapbind);
//------------------
            $attributes_ad = array("displayName", "description", "cn", "givenName", "sn", "mail", "co", "mobile", "company", "displayName", "username");

            $base = "DC=dcro,DC=gov";

            $filter = "(sAMAccountName=*)";

            $filter = "(sAMAccountName=$ldaprdn)";

            $result = ldap_search($ldapconn, $base, $filter) or die("Error in search query");


            $info = ldap_get_entries($ldapconn, $result);

//print "<pre>";print_r($info);print "</pre>";

            for ($i = 0; $i < $info["count"]; $i++) {
                // to show the attribute displayName (note the case!)
                $usuario = $info[$i]["displayname"][0];
                $cpf = $info[$i]["cpfnumber"][0];
                // $cpf = substr($cpf, 0, 3) . "." . substr($cpf, 3, 3) . "." . substr($cpf, 6, 3) . "-" . substr($cpf, 9, '3');
            }
//---------------
            // verify binding
            if ($ldapbind) {
                $logado = 1;
            } else {
                $logado = 0;
            }
        }
    }
    $rows = array('logado' => $logado, 'cpf' => $cpf, 'usuario' => $usuario);
    return $rows;
}

