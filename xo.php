<?php

class XO
{
    private $stdin;
    private string $places;
    private string $currentPlayer = 'O';

    public function __construct()
    {
        $this->stdin = fopen('php://stdin', 'r');
        stream_set_blocking($this->stdin, false);
        system('stty cbreak -echo');
    }

    public function play()
    {
        $this->clearScreen();
        $this->resetGameBoard();
        $this->drawGameBoard();
        while (true) {
            $keypress = $this->readInput();
            if ($keypress === "\e") {
                $this->clearScreen();
                echo 'Goodbye...' . PHP_EOL;
                return;
            }
            if (in_array($keypress, ['1', '2', '3', '4', '5', '6', '7', '8', '9'])) {
                $this->playMove($keypress);
            }
        }
    }

    private function readInput()
    {
        return fgets($this->stdin);
    }

    private function clearScreen()
    {
        echo chr(27) . chr(91) . 'H' . chr(27) . chr(91) . 'J';
    }

    private function playMove($keypress)
    {
        $index = $this->mapIndex($keypress);
        if ($this->places[$index] === ' ') {
            $this->places[$index] = $this->currentPlayer;
            $this->clearScreen();
            $this->drawGameBoard();
            $this->togglePlayer();
        } else {
            $this->clearScreen();
            $this->drawGameBoard();
            echo 'Move cannot be played';
        }

    }

    private function mapIndex($keypress)
    {
        return match($keypress) {
            '7' => 0,
            '8' => 1,
            '9' => 2,
            '4' => 3,
            '5' => 4,
            '6' => 5,
            '1' => 6,
            '2' => 7,
            '3' => 8,
        };
    }

    private function drawGameBoard()
    {
        $board = <<<BOARD
|-----------|
| %s | %s | %s |
|---|---|---|
| %s | %s | %s |
|---|---|---|
| %s | %s | %s |
|-----------|

BOARD;
        echo sprintf(
            $board,
            ...str_split($this->places)
        );

    }

    private function resetGameBoard()
    {
        $this->places = '         ';
    }

    private function togglePlayer()
    {
        $this->currentPlayer === 'O' ? $this->currentPlayer = 'X' : $this->currentPlayer = 'O';
    }
}
(new XO())->play();
