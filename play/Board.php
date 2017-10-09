<?php
/*
 * Andrew Madrid
 * CS 3360 - Design and Implementaton of Programming Langauges
 * Project 1: Web Scripting with PHP
 * Fall 2017
 * Purpose:
 *  Board is used to represent the actual board of Omok.  It includes methods
 *  to place stones and to see whcih stone, if any, is on a space of the board.
 */
class Board
{
    var $size;      // Holds the size of the board
    var $places;    // The spots on the board that a stone can be placed
    
    /*
     * Initalizes a new board with all empty spaces
     * 0: Empty Space
     * 1: Human Stone
     * 2: Computer Stone
     */
    function __construct($size)
    {
        $this->size = $size;
        $this->places = array
        (
            array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
            array(0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0)
        );
        
    }
    
    // Places a given stone at x,y.
    function place_Tile($user, $x, $y)
    {
        $this->places[$x][$y] = $user;
    }
    
    /*
     * Checks to see if the x,y are valid.
     * If they are, returns the stone that is at x,y.
     */
    function get_Tile($x, $y)
    {
        if($x < 0 || $x > $this->size-1 || $y < 0 || $y >$this->size-1) {
            return null;
        } else {
            return $this->places[$x][$y];
        }
    }
}
?>