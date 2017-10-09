<?php
/*
 * Andrew Madrid
 * CS 3360 - Design and Implementaton of Programming Langauges
 * Project 1: Web Scripting with PHP
 * Fall 2017
 * Purpose:
 *  Index is what the user will access when they go to new URL.  
 *  This handles all the errors when the user tries to reach new,
 *  as well as create a new file and PID to start a game.
 */
define('STRATEGY', 'strategy');
$strategies = array("smart", "random");
// Checks if the user entered a strategy
if(!array_key_exists(STRATEGY, $_GET))
{
    $result = array("response" => false, "reason" => "Strategy not specified");
    echo json_encode($result);
}
else 
{
    $strategy = $_GET[STRATEGY];
    $strategy = strtolower($strategy);
    // Initalizes a new game with the Smart Strategy
    if($strategy == $strategies[0])
    {
        $pid = uniqid();
        $result = array("response" => true, "pid" => $pid);
        echo json_encode($result);
        $file_Name = "../writable/SavedGame";
        $file = fopen("$file_Name", "w") or die("Unable to open file!");
        fputs($file, json_encode(array('pid' => $pid, 'strategy' => $strategy, 'player' => [], 'computer' => [])));
        fclose($file);
    }
    // Initializes a new game with the Random Strategy
    else if($strategy == $strategies[1])
    {
        $pid = uniqid();
        $result = array("response" => true, "pid" => $pid);
        echo json_encode($result);
        $file_Name = "../writable/SavedGame";
        $file = fopen("$file_Name", "w") or die("Unable to open file!");
        fputs($file, json_encode(array('pid' => $pid, 'strategy' => $strategy, 'player' => [], 'computer' => [])));
        fclose($file);
    }
    // States that the entered strategy is not valid
    else 
    {
        $result = array("response" => false, "reason" => "Unknown Strategy");
        echo json_encode($result);
    }
}
?>