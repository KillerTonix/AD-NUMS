<?php
include '../config/ldap_config.php';
session_start();

$username = $_POST['username'];
$password = $_POST['password'];

$ldapConn = ldap_connect(LDAP_SERVER, LDAP_PORT);
ldap_set_option($ldapConn, LDAP_OPT_PROTOCOL_VERSION, 3);

if ($ldapConn && @ldap_bind($ldapConn, "$username@your.domain.local", $password)) {
    $filter = "(sAMAccountName=$username)";
    $result = ldap_search($ldapConn, LDAP_BASE_DN, $filter, ['memberof']);
    $entries = ldap_get_entries($ldapConn, $result);

    $memberof = $entries[0]['memberof'] ?? [];
    $isCreator = false;
    $isAdmin = false;

    foreach ($memberof as $group) {
        if (stripos($group, LDAP_GROUP_CREATORS) !== false) {
            $isCreator = true;
        }
        if (stripos($group, LDAP_GROUP_ADMINS) !== false) {
            $isAdmin = true;
        }
    }

    if ($isCreator || $isAdmin) {
        $_SESSION['username'] = $username;
        $_SESSION['is_admin'] = $isAdmin;
        header("Location: main.php");
        exit();
    } else {
        echo "Access denied: Not in allowed groups.";
    }
} else {
    echo "Invalid credentials.";
}
ldap_close($ldapConn);
?>