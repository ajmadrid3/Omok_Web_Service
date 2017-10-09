<?php
/*
 * Andrew Madrid
 * CS 3360 - Design and Implementaton of Programming Langauges
 * Project 1: Web Scripting with PHP
 * Fall 2017
 * Purpose:
 *  Game holds all of the functions in order to play Omok.  It includes methods
 *  such as loading a game, taking the player and computer turns, and sending out
 *  a response.
 */
include 'Board.php';
include 'MoveStrategy.php';
class Game
{
    var $board;                 // Saves the board layout
    var $player_Win;            // True if the player won
    var $player_Draw;           // True if there is a draw between the player and computer
    var $player_Row;            // Array that holds the winning positions of the row
    var $computer_Strategy;     // Holds the strategy of the computer, Smart or Random
    var $computer_Win;          // True if the computer won
    var $computer_Draw;         // True if there is a draw between the player and computer
    var $computer_Row;          // Array that holds the winning positions of the row
    
    // Construtor method.  Creates a new board and sets all attributes to default value.
    function __construct()
    {
        $this->board = new Board(BOARD_SIZE);
        $this->player_Win = false;
        $this->player_Draw = false;
        $this->player_Row = [];
        $this->computer_Win = false;
        $this->computer_Draw = false;
        $this->computer_Row = [];
    }
    
    // Used to check where, if any, previous stones have been placed, as well as what strategy is being used.
    function import_Game($game_File)
    {
        $this->computer_Strategy = $game_File->{'strategy'};
        if(!empty($game_File->{'player'}))
        {
            $moves = $game_File->{'player'};
            for ($i = 0; $i < count($moves); $i++) {
                $this->board->place_Tile(1, $moves[$i][0], $moves[$i][1]);
            }
        }
        if(!empty($game_File->{'computer'}))
        {
            $moves = $game_File->{'computer'};
            for ($i = 0; $i < count($moves); $i++) {
                $this->board->place_Tile(2, $moves[$i][0], $moves[$i][1]);
            }
        }
    }
    
    /*
     * Checks to see if the entered coordinates allow the player to place a stone.
     * If true, places stone and looks to see if the player won or if there is a draw.
     */
    function make_Player_Move($player_Move)
    {
        if($this->board->get_Tile($player_Move[0], $player_Move[1]) != 0)
        {
            return false;
        }
        else 
        {
            $this->board->place_Tile(1, $player_Move[0], $player_Move[1]);
            $this->check_Win(1, $player_Move[0], $player_Move[1]);
            if(!$this->player_Win)
            {
                $this->player_Draw = $this->check_Draw();
            }
            return true;
        }
    }
    
    // Based on the strategy, computer places stone and checks for win or draw.
    function make_Opponent_Move($player_Move)
    {
        if($this->computer_Strategy == "random")
        {
            $strategy = new Random_Strategy();
            $computer_Move = $strategy->place($this->board);
            $this->check_Win(2, $computer_Move[0], $computer_Move[1]);
            if(!$this->computer_Win)
            {
                $this->computer_Draw = $this->check_Draw();
            }
            return $computer_Move;
        }
        else
        {
            $strategy = new Smart_Strategy();
            $computer_Move = $strategy->place($this->board, $player_Move);
            $this->check_Win(2, $computer_Move[0], $computer_Move[1]);
            if(!$this->computer_Win)
            {
                $this->computer_Draw = $this->check_Draw();
            }
            return $computer_Move;
        }
    }
    
    // Checks for all possible win conditions based on last stone placed.
    function check_Win($user, $x, $y)
    {
        $x = (int)$x;
        $y = (int)$y;
        if(!$this->check_Horizontal($user, $x, $y)) {
            if(!$this->check_Vertical($user, $x, $y)) {
                if(!$this->check_Left_Diagonal($user, $x, $y)) {
                    if(!$this->check_Right_Diagonal($user, $x, $y)) {
                        $win_Found = false;
                    }
                }
            }
        }
    }
    
    /* 
     * Checks all possbile horizontal rows that can be made using the give x,y values.
     * Determines if it is for the human or computer.
     * Returns true if there is a winning row, false if there is none.
     */
    function check_Horizontal($user, $x, $y)
    {
        $row = array($this->board->get_Tile($x, $y), $this->board->get_Tile($x+1, $y), $this->board->get_Tile($x+2, $y), $this->board->get_Tile($x+3, $y), $this->board->get_Tile($x+4, $y));
        if(!$this->find_Null($row)) 
        {
            if($this->same_User($row, $user)) 
            {
                if($user == 1) 
                {
                    $this->player_Win = true;
                    $this->player_Row = (int)array($x, $y, $x+1, $y, $x+2, $y, $x+3, $y, $x+4, $y);
                    return true;
                } 
                else 
                {
                    $this->computer_Win = true;
                    $this->computer_Row = (int)array($x, $y, $x+1, $y, $x+2, $y, $x+3, $y, $x+4, $y);
                    return true;
                }
            }
        }
        $row = array($this->board->get_Tile($x-1, $y), $this->board->get_Tile($x, $y), $this->board->get_Tile($x+1, $y), $this->board->get_Tile($x+2, $y), $this->board->get_Tile($x+3, $y));
        if(!$this->find_Null($row)) 
        {
            if($this->same_User($row, $user)) 
            {
                if($user == 1) 
                {
                    $this->player_Win = true;
                    $this->player_Row = (int)array($x-1, $y, $x, $y, $x+1, $y, $x+2, $y, $x+3, $y);
                    return true;
                } 
                else 
                {
                    $this->computer_Win = true;
                    $this->computer_Row = (int)array($x-1, $y, $x, $y, $x+1, $y, $x+2, $y, $x+3, $y);
                    return true;
                }
            }
        }
        $row = array($this->board->get_Tile($x-2, $y), $this->board->get_Tile($x-1, $y), $this->board->get_Tile($x, $y), $this->board->get_Tile($x+1, $y), $this->board->get_Tile($x+2, $y));
        if(!$this->find_Null($row)) 
        {
            if($this->same_User($row, $user)) 
            {
                if($user == 1) 
                {
                    $this->player_Win = true;
                    $this->player_Row = array($x-2, $y, $x-1, $y, $x, $y, $x+1, $y, $x+2, $y);
                    return true;
                } 
                else 
                {
                    $this->computer_Win = true;
                    $this->computer_Row = array($x-2, $y, $x-1, $y, $x, $y, $x+1, $y, $x+2, $y);
                    return true;
                }
            }
        }
        $row = array($this->board->get_Tile($x-3, $y), $this->board->get_Tile($x-2, $y), $this->board->get_Tile($x-1, $y), $this->board->get_Tile($x, $y), $this->board->get_Tile($x+1, $y));
        if(!$this->find_Null($row)) 
        {
            if($this->same_User($row, $user)) 
            {
                if($user == 1) 
                {
                    $this->player_Win = true;
                    $this->player_Row = array($x-3, $y, $x-2, $y, $x-1, $y, $x, $y, $x+1, $y);
                    return true;
                } 
                else 
                {
                    $this->computer_Win = true;
                    $this->computer_Row = array($x-3, $y, $x-2, $y, $x-1, $y, $x, $y, $x+1, $y);
                    return true;
                }
            }
        }
        $row = array($this->board->get_Tile($x-4, $y), $this->board->get_Tile($x-3, $y), $this->board->get_Tile($x-2, $y), $this->board->get_Tile($x-1, $y), $this->board->get_Tile($x, $y));
        if(!$this->find_Null($row)) 
        {
            if($this->same_User($row, $user)) 
            {
                if($user == 1) 
                {
                    $this->player_Win = true;
                    $this->player_Row = array($x-4, $y, $x-3, $y, $x-2, $y, $x-1, $y, $x, $y);
                    return true;
                } 
                else 
                {
                    $this->computer_Win = true;
                    $this->computer_Row = array($x-4, $y, $x-3, $y, $x-2, $y, $x-1, $y, $x, $y);
                    return true;
                }
            }
        }
        return false;
    }
    
    /*
     * Checks all possbile vertical rows that can be made using the give x,y values.
     * Determines if it is for the human or computer.
     * Returns true if there is a winning row, false if there is none.
     */
    function check_Vertical($user, $x, $y)
    {
        $row = array($this->board->get_Tile($x, $y), $this->board->get_Tile($x, $y+1), $this->board->get_Tile($x, $y+2), $this->board->get_Tile($x, $y+3), $this->board->get_Tile($x, $y+4));
        if(!$this->find_Null($row)) 
        {
            if($this->same_User($row, $user)) 
            {
                if($user == 1) 
                {
                    $this->player_Win = true;
                    $this->player_Row = array($x, $y, $x, $y+1, $x, $y+2, $x, $y+3, $x, $y+4);
                    return true;
                } 
                else 
                {
                    $this->computer_Win = true;
                    $this->computer_Row = array($x, $y, $x, $y+1, $x, $y+2, $x, $y+3, $x, $y+4);
                    return true;
                }
            }
        }
        $row = array($this->board->get_Tile($x, $y-1), $this->board->get_Tile($x, $y), $this->board->get_Tile($x, $y+1), $this->board->get_Tile($x, $y+2), $this->board->get_Tile($x, $y+3));
        if(!$this->find_Null($row)) 
        {
            if($this->same_User($row, $user)) 
            {
                if($user == 1) 
                {
                    $this->player_Win = true;
                    $this->player_Row = array($x, $y-1, $x, $y, $x, $y+1, $x, $y+2, $x, $y+3);
                    return true;
                } 
                else 
                {
                    $this->computer_Win = true;
                    $this->computer_Row = array($x, $y-1, $x, $y, $x, $y+1, $x, $y+2, $x, $y+3);
                    return true;
                }
            }
        }
        $row = array($this->board->get_Tile($x, $y-2), $this->board->get_Tile($x, $y-1), $this->board->get_Tile($x, $y), $this->board->get_Tile($x, $y+1), $this->board->get_Tile($x, $y+2));
        if(!$this->find_Null($row)) 
        {
            if($this->same_User($row, $user)) 
            {
                if($user == 1) 
                {
                    $this->player_Win = true;
                    $this->player_Row = array($x, $y-2, $x, $y-1, $x, $y, $x, $y+1, $x, $y+2);
                    return true;
                } 
                else 
                {
                    $this->computer_Win = true;
                    $this->computer_Row = array($x, $y-2, $x, $y-1, $x, $y, $x, $y+1, $x, $y+2);
                    return true;
                }
            }
        }
        $row = array($this->board->get_Tile($x, $y-3), $this->board->get_Tile($x, $y-2), $this->board->get_Tile($x, $y-1), $this->board->get_Tile($x, $y), $this->board->get_Tile($x, $y+1));
        if(!$this->find_Null($row)) 
        {
            if($this->same_User($row, $user)) 
            {
                if($user == 1) 
                {
                    $this->player_Win = true;
                    $this->player_Row = array($x, $y-3, $x, $y-2, $x, $y-1, $x, $y, $x, $y+1);
                    return true;
                } 
                else 
                {
                    $this->computer_Win = true;
                    $this->computer_Row = array($x, $y-3, $x, $y-2, $x, $y-1, $x, $y, $x, $y+1);
                    return true;
                }
            }
        }
        $row = array($this->board->get_Tile($x, $y-4), $this->board->get_Tile($x, $y-3), $this->board->get_Tile($x, $y-2), $this->board->get_Tile($x, $y-1), $this->board->get_Tile($x, $y));
        if(!$this->find_Null($row)) 
        {
            if($this->same_User($row, $user)) 
            {
                if($user == 1) 
                {
                    $this->player_Win = true;
                    $this->player_Row = array($x, $y-4, $x, $y-3, $x, $y-2, $x, $y-1, $x, $y);
                    return true;
                } 
                else 
                {
                    $this->computer_Win = true;
                    $this->computer_Row = array($x, $y-4, $x, $y-3, $x, $y-2, $x, $y-1, $x, $y);
                    return true;
                }
            }
        }
        return false;
    }
    
    /*
     * Checks all possbile left diagonal rows that can be made using the give x,y values.
     * Determines if it is for the human or computer.
     * Returns true if there is a winning row, false if there is none.
     */
    function check_Left_Diagonal($user, $x, $y)
    {
        $row = array($this->board->get_Tile($x, $y), $this->board->get_Tile($x+1, $y-1), $this->board->get_Tile($x+2, $y-2), $this->board->get_Tile($x+3, $y-3), $this->board->get_Tile($x+4, $y-4));
        if(!$this->find_Null($row)) 
        {
            if($this->same_User($row, $user)) 
            {
                if($user == 1) 
                {
                    $this->player_Win = true;
                    $this->player_Row = array($x, $y, $x+1, $y-1, $x+2, $y-2, $x+3, $y-3, $x+4, $y-4);
                    return true;
                } 
                else 
                {
                    $this->computer_Win = true;
                    $this->computer_Row = array($x, $y, $x+1, $y-1, $x+2, $y-2, $x+3, $y-3, $x+4, $y-4);
                    return true;
                }
            }
        }
        $row = array($this->board->get_Tile($x-1, $y+1), $this->board->get_Tile($x, $y), $this->board->get_Tile($x+1, $y-1), $this->board->get_Tile($x+2, $y-2), $this->board->get_Tile($x+3, $y-3));
        if(!$this->find_Null($row)) 
        {
            if($this->same_User($row, $user)) 
            {
                if($user == 1) 
                {
                    $this->player_Win = true;
                    $this->player_Row = array($x-1, $y+1, $x, $y, $x+1, $y-1, $x+2, $y-2, $x+3, $y-3);
                    return true;
                } 
                else 
                {
                    $this->computer_Win = true;
                    $this->computer_Row = array($x-1, $y+1, $x, $y, $x+1, $y-1, $x+2, $y-2, $x+3, $y-3);
                    return true;
                }
            }
        }
        $row = array($this->board->get_Tile($x-2, $y+2), $this->board->get_Tile($x-1, $y+1), $this->board->get_Tile($x, $y), $this->board->get_Tile($x+1, $y-1), $this->board->get_Tile($x+2, $y-2));
        if(!$this->find_Null($row)) 
        {
            if($this->same_User($row, $user)) 
            {
                if($user == 1) 
                {
                    $this->player_Win = true;
                    $this->player_Row = array($x-2, $y+2, $x-1, $y+1, $x, $y, $x+1, $y-1, $x+2, $y-2);
                    return true;
                } 
                else 
                {
                    $this->computer_Win = true;
                    $this->computer_Row = array($x-2, $y+2, $x-1, $y+1, $x, $y, $x+1, $y-1, $x+2, $y-2);
                    return true;
                }
            }
        }
        $row = array($this->board->get_Tile($x-3, $y+3), $this->board->get_Tile($x-2, $y+2), $this->board->get_Tile($x-1, $y+1), $this->board->get_Tile($x, $y), $this->board->get_Tile($x+1, $y-1));
        if(!$this->find_Null($row)) 
        {
            if($this->same_User($row, $user)) 
            {
                if($user == 1) 
                {
                    $this->player_Win = true;
                    $this->player_Row = array($x-3, $y+3, $x-2, $y+2, $x-1, $y+1, $x, $y, $x+1, $y-1);
                    return true;
                } 
                else 
                {
                    $this->computer_Win = true;
                    $this->computer_Row = array($x-3, $y+3, $x-2, $y+2, $x-1, $y+1, $x, $y, $x+1, $y-1);
                    return true;
                }
            }
        }
        $row = array($this->board->get_Tile($x-4, $y+4), $this->board->get_Tile($x-3, $y+3), $this->board->get_Tile($x-2, $y+2), $this->board->get_Tile($x-1, $y+1), $this->board->get_Tile($x, $y));
        if(!$this->find_Null($row)) 
        {
            if($this->same_User($row, $user)) 
            {
                if($user == 1) 
                {
                    $this->player_Win = true;
                    $this->player_Row = array($x-4, $y+4, $x-3, $y+3, $x-2, $y+2, $x-1, $y+1, $x, $y);
                    return true;
                } 
                else 
                {
                    $this->computer_Win = true;
                    $this->computer_Row = array($x-4, $y+4, $x-3, $y+3, $x-2, $y+2, $x-1, $y+1, $x, $y);
                    return true;
                }
            }
        }
        return false;
    }
    
    /*
     * Checks all possbile right diagonal rows that can be made using the give x,y values.
     * Determines if it is for the human or computer.
     * Returns true if there is a winning row, false if there is none.
     */
    function check_Right_Diagonal($user, $x, $y)
    {
        $row = array($this->board->get_Tile($x, $y), $this->board->get_Tile($x+1, $y+1), $this->board->get_Tile($x+2, $y+2), $this->board->get_Tile($x+3, $y+3), $this->board->get_Tile($x+4, $y+4));
        if(!$this->find_Null($row)) 
        {
            if($this->same_User($row, $user)) 
            {
                if($user == 1) 
                {
                    $this->player_Win = true;
                    $this->player_Row = array($x, $y, $x+1, $y+1, $x+2, $y+2, $x+3, $y+3, $x+4, $y+4);
                    return true;
                } 
                else 
                {
                    $this->computer_Win = true;
                    $this->computer_Row = array($x, $y, $x+1, $y+1, $x+2, $y+2, $x+3, $y+3, $x+4, $y+4);
                    return true;
                }
            }
        }
        $row = array($this->board->get_Tile($x-1, $y-1), $this->board->get_Tile($x, $y), $this->board->get_Tile($x+1, $y+1), $this->board->get_Tile($x+2, $y+2), $this->board->get_Tile($x+3, $y+3));
        if(!$this->find_Null($row)) 
        {
            if($this->same_User($row, $user)) 
            {
                if($user == 1) 
                {
                    $this->player_Win = true;
                    $this->player_Row = array($x-1, $y-1, $x, $y, $x+1, $y+1, $x+2, $y+2, $x+3, $y+3);
                    return true;
                } 
                else 
                {
                    $this->computer_Win = true;
                    $this->computer_Row = array($x-1, $y-1, $x, $y, $x+1, $y+1, $x+2, $y+2, $x+3, $y+3);
                    return true;
                }
            }
        }
        $row = array($this->board->get_Tile($x-2, $y-2), $this->board->get_Tile($x-1, $y-1), $this->board->get_Tile($x, $y), $this->board->get_Tile($x+1, $y+1), $this->board->get_Tile($x+2, $y+2));
        if(!$this->find_Null($row)) 
        {
            if($this->same_User($row, $user)) 
            {
                if($user == 1) 
                {
                    $this->player_Win = true;
                    $this->player_Row = array($x-2, $y-2, $x-1, $y-1, $x, $y, $x+1, $y+1, $x+2, $y+2);
                    return true;
                } 
                else 
                {
                    $this->computer_Win = true;
                    $this->computer_Row = array($x-2, $y-2, $x-1, $y-1, $x, $y, $x+1, $y+1, $x+2, $y+2);
                    return true;
                }
            }
        }
        $row = array($this->board->get_Tile($x-3, $y-3), $this->board->get_Tile($x-2, $y-2), $this->board->get_Tile($x-1, $y-1), $this->board->get_Tile($x, $y), $this->board->get_Tile($x+1, $y+1));
        if(!$this->find_Null($row)) 
        {
            if($this->same_User($row, $user)) 
            {
                if($user == 1) 
                {
                    $this->player_Win = true;
                    $this->player_Row = array($x-3, $y-3, $x-2, $y-2, $x-1, $y-1, $x, $y, $x+1, $y+1);
                    return true;
                } 
                else 
                {
                    $this->computer_Win = true;
                    $this->computer_Row = array($x-3, $y-3, $x-2, $y-2, $x-1, $y-1, $x, $y, $x+1, $y+1);
                    return true;
                }
            }
        }
        $row = array($this->board->get_Tile($x-4, $y-4), $this->board->get_Tile($x-3, $y-3), $this->board->get_Tile($x-2, $y-2), $this->board->get_Tile($x-1, $y-1), $this->board->get_Tile($x, $y));
        if(!$this->find_Null($row)) 
        {
            if($this->same_User($row, $user)) 
            {
                if($user == 1) 
                {
                    $this->player_Win = true;
                    $this->player_Row = array($x-4, $y-4, $x-3, $y-3, $x-2, $y-2, $x-1, $y-1, $x, $y);
                    return true;
                } 
                else 
                {
                    $this->computer_Win = true;
                    $this->computer_Row = array($x-4, $y-4, $x-3, $y-3, $x-2, $y-2, $x-1, $y-1, $x, $y);
                    return true;
                }
            }
        }
        return false;
    }
    
    /*
     * Checks if the given row has a null value.
     * Returns true if there is.
     */
    function find_Null($row) {
        for($i = 0; $i < 5; $i++) {
            if(is_null($row[$i])) {
                return true;
            }
        }
        return false;
    }
    
    // Checks if the given row contains either all Human or all Computer stones based on given user.
    function same_User($row, $user) {
        for ($i = 0; $i < 5; $i++) {
            if($row[$i] != $user) {
                return false;
            }
        }
        return true;
    }
    // Checks to see if the board has any available spaces.
    function check_Draw()
    {
        for ($i = 0; $i < BOARD_SIZE; $i++) 
        {
            for ($j = 0; $j < BOARD_SIZE; $j++) 
            {
                if($this->board->get_Tile($i, $j) != 0)
                {
                    return false;
                }
            }
        }
        return true;
    }
    
    // Returns an array as the response based on the outcome of stone placement.
    function json_Response($user, $move)
    {
        $x = (int)$move[0];
        $y = (int)$move[1];
        if($user == 1)
        {
            if($this->player_Win)
            {
                $result = array('x' => $x, 'y' => $y, 'isWin' => $this->player_Win, 'isDraw' => $this->player_Draw, 'row' => [$this->player_Row[0], $this->player_Row[1], $this->player_Row[2], $this->player_Row[3], $this->player_Row[4], $this->player_Row[5], $this->player_Row[6], $this->player_Row[7], $this->player_Row[8], $this->player_Row[9]]);
                return $result;
            }
            else if($this->player_Draw)
            {
                $result = array('x' => $x, 'y' => $y, 'isWin' => $this->player_Win, 'isDraw' => $this->player_Draw, 'row' => []);
                return $result;
            }
            else
            {
                $result = array('x' => $x, 'y' => $y, 'isWin' => $this->player_Win, 'isDraw' => $this->player_Draw, 'row' => []);
                return $result;
            }
        }
        else
        {
            if($this->computer_Win)
            {
                $result = array('x' => $x, 'y' => $y, 'isWin' => $this->computer_Win, 'isDraw' => $this->computer_Draw, 'row' => [$this->computer_Row[0], $this->computer_Row[1], $this->computer_Row[2], $this->computer_Row[3], $this->computer_Row[4], $this->computer_Row[5], $this->computer_Row[6], $this->computer_Row[7], $this->computer_Row[8], $this->computer_Row[9]]);
                return $result;
            }
            else if($this->computer_Draw)
            {
                $result = array('x' => $x, 'y' => $y, 'isWin' => $this->computer_Win, 'isDraw' => $this->computer_Draw, 'row' => []);
                return $result;
            }
            else
            {
                $result = array('x' => $x, 'y' => $y, 'isWin' => $this->computer_Win, 'isDraw' => $this->computer_Draw, 'row' => []);
                return $result;
            }
        }
    }
}
?>