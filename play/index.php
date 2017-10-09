<?php
/*
 * Andrew Madrid
 * CS 3360 - Design and Implementaton of Programming Langauges
 * Project 1: Web Scripting with PHP
 * Fall 2017
 * Purpose:
 *  Index is what the user will access when they reach the play URL.
 *  This handles all of the erros when the user tries to reach Play,
 *  as well as execute the Game class.
 */
include '../common/constants.php';
include 'Game.php';

define('PID', 'pid');
define('MOVE', 'move');

// If a PID isn't entered
if(!array_key_exists(PID, $_GET))
{
    $result = array("response" => false, "reason" => "PID not specified");
    echo json_encode($result);
}
else 
{
    $file = file_get_contents("../writable/SavedGame");
    $read_File = json_decode($file);
    
    // If the PID does not match the given one
    if($_GET[PID] != $read_File->{'pid'})
    {
        $result = array("response" => false, "reason" => "Unknown pid");
        echo json_encode($result);
    }
    else 
    {
        // If the moves are not specified
        if(!array_key_exists(MOVE, $_GET))
        {
            $result = array("response" => false, "reason" => "Move not specified");
            echo json_encode($result);
        }
        else 
        {
            // If the move are not given as x,y
            $move = explode(",", $_GET[MOVE]);
            if(count($move) != 2)
            {
                $result = array("response" => false, "reason" => "Move not well-formed");
                echo json_encode($result);
            }
            else 
            {
                // If the x value is not within the range
                if($move[0] < 0 || $move[0] > 14)
                {
                    $result = array("response" => false, "reason" => "Invalid x coordinate, $move[0]");
                    echo json_encode($result);
                }
                else 
                {
                    // If the y value is not within the range
                    if($move[1] < 0 || $move[1] > 14)
                    {
                        $result = array("response" => false, "reason" => "Invalid y coordinate, $move[1]");
                        echo json_encode($result);
                    }
                    else 
                    {
                        $game = new Game();
                        $game->import_Game($read_File);
                        if($game->make_Player_Move($move))
                        {
                            if($game->player_Win || $game->player_Draw)
                            {
                                $result = array('response' => true, 'ack_move' => $game->json_Response(1, $move));
                                echo json_encode($result);
                            }
                            else 
                            {
                                $computer_Move = $game->make_Opponent_Move($move);
                                if($game->computer_Win || $game->computer_Draw)
                                {
                                    $result = array('response' => true, 'move' => $game->json_Response(2, $move));
                                    echo json_encode($result);
                                }
                                else 
                                {
                                    $result = array('response' => true, 'ack_move' => $game->json_Response(1, $move), 'move' => $game->json_Response(2, $computer_Move));
                                    echo json_encode($result);
                                    array_push($read_File->{'player'}, $move);
                                    array_push($read_File->{'computer'}, $computer_Move);
                                    $file_Name = "../writable/SavedGame";
                                    $file = fopen("$file_Name", "w") or die("Unable to open file!");
                                    fputs($file, json_encode($read_File));
                                    fclose($file);
                                }
                            }
                        }
                        else 
                        {
                            $result = array("response" => false, "reason" => "Place not empty, ($move[0], $move[1])");
                            echo json_encode($result);
                        }
                    }
                }
            }
        }
    }    
}

?>