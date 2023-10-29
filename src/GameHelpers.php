<?php

namespace Gteixeira\MathExpressionGenerator;

class GameHelpers
{
    public static function sortInt(int $min, int $max): int
    {
        return rand($min, $max);
    }

    public static function sortNumber(): int
    {
        return self::sortInt(MENOR_NUMERO, MAIOR_NUMERO);
    }

    public static function sortDivisible(int $number): int
    {
        $tentativa = 0;

        // Tenta 100 vezes até achar um número divisível
        while ($tentativa < 100) {
            $sortedNum = self::sortNumber();

            if (($sortedNum != 1) && ($sortedNum != $number) && ($number % $sortedNum == 0)) {
                return $sortedNum;
            }

            $tentativa++;
        }

        // Caso não tenha achado um número divisível, retorna 1
        return 1;
    }
}