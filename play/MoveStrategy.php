<?php
/*
 * Andrew Madrid
 * CS 3360 - Design and Implementaton of Programming Langauges
 * Project 1: Web Scripting with PHP
 * Fall 2017
 * Purpose:
 *  MoveStrategy is used to hold the functions for the two strategies that can be played.
 *  It includes the Random_Strategy, where a stone is placed on a random space on the board,
 *  and the Smart_Strategy, which places a stone based on if there is a row that has 3 or
 *  more stones.
 */

// Random Strategy will place a stone on a random space on the board
class Random_Strategy
{
    // Blank Constructor Method
    function __construct()
    {

    }
    
    // Places a stone on a valid space
    function place(Board $board)
    {
        $valid_Position = false;
        while(!$valid_Position) 
        {
            $x = rand(0, 14);
            $y = rand(0, 14);
            if($board->get_Tile($x, $y) == 0)
            {
                $board->place_Tile(2, $x, $y);
                $valid_Position = true;
            }
        }
        return array($x, $y);
    }
}

// Smart is able to detect rows that have 3 or 4 stones and place accordingly
class Smart_Strategy
{
    // Blank Constructor Method
    function __construct()
    {
        
    }
    
    function place(Board $board, $player_Move)
    {
        $px = $player_Move[0];
        $py = $player_Move[1];
        $row = $this->find_Longest_Row($board, 1, $px, $py);
        
        // Detects if there is a row of 3 to block Player
        if(count($row) == 8) 
        { 
            $board->place_Tile(2, $row[6], $row[7]);
            return array($row[6], $row[7]);
        }
        // Detects if there is a row of 4 to block Player
        else if(count($row) == 10)
        {
            $board->place_Tile(2, $row[8], $row[9]);
            return array($row[8], $row[9]);
        }
        // Places somewhere else
        else
        {
            $valid_Position = false;
            while(!$valid_Position)
            {
                $x = rand(0, 14);
                $y = rand(0, 14);
                if($board->get_Tile($x, $y) == 0)
                {
                    $board->place_Tile(2, $x, $y);
                    $valid_Position = true;
                }
            }
            return array($x, $y);
        }
    }
    
    /*
     * Given the current position of x,y, this function determines if there is a row of 3 or 4 of the same stones.
     * If there is, it will return an array of the positions as well as the coordinates to place a new stone.
     * If not, it will return an empty array.
     */
    function find_Longest_Row(Board $board, $user, $x, $y)
    {   
        $row = [];
        if(!is_null($board->get_Tile($x+1, $y)) && $board->get_Tile($x+1, $y) == $user)
        {
            if(!is_null($board->get_Tile($x+2, $y)) && $board->get_Tile($x+2, $y) == $user)
            {
                if(!is_null($board->get_Tile($x+3, $y)) && $board->get_Tile($x+3, $y) == $user)
                {
                    if(!is_null($board->get_Tile($x+4, $y)) && $board->get_Tile($x+4, $y) == 0)
                    {
                        $row = array($x, $y, $x+1, $y, $x+2, $y, $x+3, $y, $x+4, $y);
                        return $row;
                    }
                    else if(!is_null($board->get_Tile($x-1, $y)) && $board->get_Tile($x-1, $y) == 0)
                    {
                        $row = array($x, $y, $x+1, $y, $x+2, $y, $x+3, $y, $x-1, $y);
                        return $row;
                    }
                }
                else if(!is_null($board->get_Tile($x+3, $y)) && $board->get_Tile($x+3, $y) == 0)
                {
                    $row =  array($x, $y, $x+1, $y, $x+2, $y, $x+3, $y);
                    return $row;
                }
            }
        }
        
        if(!is_null($board->get_Tile($x-1, $y)) && $board->get_Tile($x-1, $y) == $user)
        {
            if(!is_null($board->get_Tile($x-2, $y)) && $board->get_Tile($x-2, $y) == $user)
            {
                if(!is_null($board->get_Tile($x-3, $y)) && $board->get_Tile($x-3, $y) == $user)
                {
                    if(!is_null($board->get_Tile($x-4, $y)) && $board->get_Tile($x-4, $y) == 0)
                    {
                        $row = array($x, $y, $x-1, $y, $x-2, $y, $x-3, $y, $x-4, $y);
                        return $row;
                    }
                    else if(!is_null($board->get_Tile($x+1, $y)) && $board->get_Tile($x+1, $y) == 0)
                    {
                        $row = array($x, $y, $x-1, $y, $x-2, $y, $x-3, $y, $x+1, $y);
                        return $row;
                    }
                }
                else if(!is_null($board->get_Tile($x-3, $y)) && $board->get_Tile($x-3, $y) == 0)
                {
                    $row =  array($x, $y, $x-1, $y, $x-2, $y, $x-3, $y);
                    return $row;
                }
            }
        }
        if(!is_null($board->get_Tile($x, $y+1)) && $board->get_Tile($x, $y+1) == $user)
        {
            if(!is_null($board->get_Tile($x, $y+2)) && $board->get_Tile($x, $y+2) == $user)
            {
                if(!is_null($board->get_Tile($x, $y+3)) && $board->get_Tile($x, $y+3) == $user)
                {
                    if(!is_null($board->get_Tile($x, $y+4)) && $board->get_Tile($x, $y+4) == 0)
                    {
                        $row = array($x, $y, $x, $y+1, $x, $y+2, $x, $y+3, $x, $y+4);
                        return $row;
                    }
                    else if(!is_null($board->get_Tile($x, $y-1)) && $board->get_Tile($x, $y-1) == 0)
                    {
                        $row = array($x, $y, $x, $y+1, $x, $y+2, $x, $y+3, $x, $y-1);
                        return $row;
                    }
                }
                else if(!is_null($board->get_Tile($x, $y+3)) && $board->get_Tile($x, $y+3) == 0)
                {
                    $row =  array($x, $y, $x, $y+1, $x, $y+2, $x, $y+3);
                    return $row;
                }
            }
        }
        if(!is_null($board->get_Tile($x, $y-1)) && $board->get_Tile($x, $y-1) == $user)
        {
            if(!is_null($board->get_Tile($x, $y-2)) && $board->get_Tile($x, $y-2) == $user)
            {
                if(!is_null($board->get_Tile($x, $y-3)) && $board->get_Tile($x, $y-3) == $user)
                {
                    if(!is_null($board->get_Tile($x, $y-4)) && $board->get_Tile($x, $y-4) == 0)
                    {
                        $row = array($x, $y, $x, $y-1, $x, $y-2, $x, $y-3, $x, $y-4);
                        return $row;
                    }
                    else if(!is_null($board->get_Tile($x, $y+1)) && $board->get_Tile($x, $y+1) == 0)
                    {
                        $row = array($x, $y, $x, $y-1, $x, $y-2, $x, $y-3, $x, $y+1);
                        return $row;
                    }
                }
                else if(!is_null($board->get_Tile($x, $y-3)) && $board->get_Tile($x, $y-3) == 0)
                {
                    $row =  array($x, $y, $x, $y-1, $x, $y-2, $x, $y-3);
                    return $row;
                }
            }
        }
        if(!is_null($board->get_Tile($x+1, $y-1)) && $board->get_Tile($x+1, $y-1) == $user)
        {
            if(!is_null($board->get_Tile($x+2, $y-2)) && $board->get_Tile($x+2, $y-2) == $user)
            {
                if(!is_null($board->get_Tile($x+3, $y-3)) && $board->get_Tile($x+3, $y-3) == $user)
                {
                    if(!is_null($board->get_Tile($x+4, $y-4)) && $board->get_Tile($x+4, $y-4) == 0)
                    {
                        $row = array($x, $y, $x+1, $y-1, $x+2, $y-2, $x+3, $y-3, $x+4, $y-4);
                        return $row;
                    }
                    else if(!is_null($board->get_Tile($x-1, $y+1)) && $board->get_Tile($x-1, $y+1) == 0)
                    {
                        $row = array($x, $y, $x+1, $y-1, $x+2, $y-2, $x+3, $y-3, $x-1, $y+1);
                        return $row;
                    }
                }
                else if(!is_null($board->get_Tile($x+3, $y-3)) && $board->get_Tile($x+3, $y-3) == 0)
                {
                    $row = array($x, $y, $x+1, $y-1, $x+2, $y-2, $x+3, $y-3);
                    return $row;
                }
            }
        }
        else if(!is_null($board->get_Tile($x-1, $y+1)) && $board->get_Tile($x-1, $y+1) == $user)
        {
            if(!is_null($board->get_Tile($x-2, $y+2)) && $board->get_Tile($x-2, $y+2) == $user)
            {
                if(!is_null($board->get_Tile($x-3, $y+3)) && $board->get_Tile($x-3, $y+3) == $user)
                {
                    if(!is_null($board->get_Tile($x-4, $y+4)) && $board->get_Tile($x-4, $y+4) == 0)
                    {
                        $row = array($x, $y, $x-1, $y+1, $x-2, $y+2, $x-3, $y+3, $x-4, $y+4);
                        return $row;
                    }
                    else if(!is_null($board->get_Tile($x+1, $y-1)) && $board->get_Tile($x+1, $y-1) == 0)
                    {
                        $row = array($x, $y, $x-1, $y+1, $x-2, $y+2, $x-3, $y+3, $x+1, $y-1);
                        return $row;
                    }
                }
                else if(!is_null($board->get_Tile($x-3, $y+3)) && $board->get_Tile($x-3, $y+3) == 0)
                {
                    $row = array($x, $y, $x-1, $y+1, $x-2, $y+2, $x-3, $y+3);
                    return $row;
                }
            }
        }
        if(!is_null($board->get_Tile($x+1, $y+1)) && $board->get_Tile($x+1, $y+1) == $user)
        {
            if(!is_null($board->get_Tile($x+2, $y+2)) && $board->get_Tile($x+2, $y+2) == $user)
            {
                if(!is_null($board->get_Tile($x+3, $y+3)) && $board->get_Tile($x+3, $y+3) == $user)
                {
                    if(!is_null($board->get_Tile($x+4, $y+4)) && $board->get_Tile($x+4, $y+4) == 0)
                    {
                        $row = array($x, $y, $x+1, $y+1, $x+2, $y+2, $x+3, $y+3, $x+4, $y+4);
                        return $row;
                    }
                    else if(!is_null($board->get_Tile($x-1, $y-1)) && $board->get_Tile($x-1, $y-1) == 0)
                    {
                        $row = array($x, $y, $x+1, $y+1, $x+2, $y+2, $x+3, $y+3, $x-1, $y-1);
                        return $row;
                    }
                }
                else if(!is_null($board->get_Tile($x+3, $y+3)) && $board->get_Tile($x+3, $y+3) == 0)
                {
                    $row = array($x, $y, $x+1, $y+1, $x+2, $y+2, $x+3, $y+3);
                    return $row;
                }
            }
        }
        else if(!is_null($board->get_Tile($x-1, $y-1)) && $board->get_Tile($x-1, $y-1) == $user)
        {
            if(!is_null($board->get_Tile($x-2, $y-2)) && $board->get_Tile($x-2, $y-2) == $user)
            {
                if(!is_null($board->get_Tile($x-3, $y-3)) && $board->get_Tile($x-3, $y-3) == $user)
                {
                    if(!is_null($board->get_Tile($x-4, $y-4)) && $board->get_Tile($x-4, $y-4) == 0)
                    {
                        $row = array($x, $y, $x-1, $y-1, $x-2, $y-2, $x-3, $y-3, $x-4, $y-4);
                        return $row;
                    }
                    else if(!is_null($board->get_Tile($x+1, $y+1)) && $board->get_Tile($x+1, $y+1) == 0)
                    {
                        $row = array($x, $y, $x-1, $y-1, $x-2, $y-2, $x-3, $y-3, $x+1, $y+1);
                        return $row;
                    }
                }
                else if(!is_null($board->get_Tile($x-3, $y-3)) && $board->get_Tile($x-3, $y-3) == 0)
                {
                    $row = array($x, $y, $x-1, $y-1, $x-2, $y-2, $x-3, $y-3);
                    return $row;
                }
            }
        }
        return $row;
    }
}
?>