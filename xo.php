<?php

class XO
{
    private $stdin;

    public function __construct()
    {
        $this->stdin = fopen('php://stdin', 'r');
        stream_set_blocking($this->stdin, false);
        system('stty cbreak -echo');
    }

    public function play()
    {
        $this->clearScreen();
        while (true) {
            $keypress = $this->readInput();
            if ($keypress === "\e") {
                echo 'Goodbye...' . PHP_EOL;
                return;
            }
            if ($keypress) {
                $this->clearScreen();
                echo 'Key pressed: ' . $this->translateInput($keypress) . PHP_EOL;
            }
        }
    }

    private function readInput()
    {
        return fgets($this->stdin);
    }

    private function translateInput($string)
    {
        switch ($string) {
            case "\033[A":
                return "UP";
            case "\033[B":
                return "DOWN";
            case "\033[C":
                return "RIGHT";
            case "\033[D":
                return "LEFT";
            case "\n":
                return "ENTER";
            case " ":
                return "SPACE";
            case "\010":
            case "\177":
                return "BACKSPACE";
            case "\t":
                return "TAB";
            case "\e":
                return "ESC";
        }
        return $string;
    }

    private function clearScreen()
    {
        echo chr(27) . chr(91) . 'H' . chr(27) . chr(91) . 'J';
    }
}
(new XO())->play();
