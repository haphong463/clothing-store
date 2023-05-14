<?php
$mysql_hostname = 'localhost';
$mysql_user = 'root';
$mysql_password = '';
$mysql_database = 'clothing-project';

function execute($sql)
{
    global $mysql_hostname, $mysql_user, $mysql_password, $mysql_database;
    $con = mysqli_connect($mysql_hostname, $mysql_user, $mysql_password, $mysql_database);
    //insert, update, delete
    mysqli_query($con, $sql);
    //close connection
    mysqli_close($con);
}

function executeResult($sql)
{
    global $mysql_hostname, $mysql_user, $mysql_password, $mysql_database;
    $con = mysqli_connect($mysql_hostname, $mysql_user, $mysql_password, $mysql_database);
    //select
    $result = mysqli_query($con, $sql);
    $data = [];
    if ($result != null) {
        while ($row = mysqli_fetch_array($result, 1)) {
            $data[] = $row;
        }
    }
    //close connection
    mysqli_close($con);
    return $data;
}

function executeSingleResult($sql)
{
    global $mysql_hostname, $mysql_user, $mysql_password, $mysql_database;
    $con = mysqli_connect($mysql_hostname, $mysql_user, $mysql_password, $mysql_database);
    //search
    $result = mysqli_query($con, $sql);
    $row = null;
    if ($result != null) {
        $row = mysqli_fetch_array($result, 1);
    }
    //close connection
    mysqli_close($con);
    return $row;
}
