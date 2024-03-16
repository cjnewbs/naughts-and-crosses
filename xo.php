<?php

class XO
{
    private $stdin;
    private string $places;

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
            if ($keypress && in_array($keypress, ['1', '2', '3', '4', '5', '6', '7', '8', '9'])) {
                $this->writeMove($keypress);
                $this->clearScreen();
                $this->drawGameBoard();
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
            $this->places[0],
            $this->places[1],
            $this->places[2],
            $this->places[3],
            $this->places[4],
            $this->places[5],
            $this->places[6],
            $this->places[7],
            $this->places[8],
        );

    }

    private function resetGameBoard()
    {
        $this->places = '         ';
    }

    private function writeMove($keypress)
    {
        switch ($keypress) {
            case '7':
                $index = 0;
                break;
            case '8':
                $index = 1;
                break;
            case '9':
                $index = 2;
                break;
            case '4':
                $index = 3;
                break;
            case '5':
                $index = 4;
                break;
            case '6':
                $index = 5;
                break;
            case '1':
                $index = 6;
                break;
            case '2':
                $index = 7;
                break;
            case '3':
                $index = 8;
                break;
        }
        $this->resetGameBoard();
        $this->places[$index] = 'X';
    }
}
(new XO())->play();
