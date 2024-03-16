<?php

class XO
{
    private $stdin;
    private string $places;
    private string $currentPlayer = 'O';
    private bool $gameInPlay = true;

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
            if ($keypress === "\n") {
                $this->clearScreen();
                $this->resetGameBoard();
                $this->drawGameBoard();
                $this->gameInPlay = true;
            }
            if ($this->gameInPlay && in_array($keypress, ['1', '2', '3', '4', '5', '6', '7', '8', '9'])) {
                $this->playMove($keypress);
                if ($this->isGameOver()) {
                    $this->gameInPlay = false;
                    echo sprintf('GAME OVER: %s WINS!!', $this->currentPlayer);

                }
                $this->togglePlayer();
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

    private function isGameOver(): bool
    {
        $p = $this->currentPlayer;
        if (
            // Top line
            ($this->places[0] === $p && $this->places[1] === $p && $this->places[2] === $p) ||
            // Middle line
            ($this->places[3] === $p && $this->places[4] === $p && $this->places[5] === $p) ||
            // Bottom line
            ($this->places[6] === $p && $this->places[7] === $p && $this->places[8] === $p) ||
            // Left Column
            ($this->places[0] === $p && $this->places[3] === $p && $this->places[6] === $p) ||
            // Middle Column
            ($this->places[1] === $p && $this->places[4] === $p && $this->places[7] === $p) ||
            // Right Column
            ($this->places[2] === $p && $this->places[5] === $p && $this->places[8] === $p) ||
            // Top-Left to Bottom-Right
            ($this->places[0] === $p && $this->places[4] === $p && $this->places[8] === $p) ||
            // Bottom-Left to Top-Right
            ($this->places[6] === $p && $this->places[4] === $p && $this->places[2] === $p)
        ) {
            return true;
        }
        return false;
    }
}
(new XO())->play();
