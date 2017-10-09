<?php 
/*
 * Andrew Madrid
 * CS 3360 - Design and Implementaton of Programming Langauges 
 * Project 1: Web Scripting with PHP
 * Fall 2017
 * Purpose:
 *  Info is used to provide the basic information of Omok.
 */
include '../common/constants.php';

$strategies = array("Smart" => "SmartStrategy", "Random" => "RandomStrategy");

$info = array("size" => BOARD_SIZE, "strategies" => array_keys($strategies));
echo json_encode($info);


?>