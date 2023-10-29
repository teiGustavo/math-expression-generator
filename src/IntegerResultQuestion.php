<?php

namespace Gteixeira\MathExpressionGenerator;

class IntegerResultQuestion
{
    public static function get(): IntegerResultQuestion
    {
        return new IntegerResultQuestion();
    }

    public function __construct(
        public string $expression = "",
        public array $answers = [],
        public int $solved = 0,
        public int $warnings = 0
    ) {
        $this->generate();
    }

    private function generateExpression(int $tam) : string
    {
        $expression = "";

        // Função para sortear uma operação dentre as possíveis
        $sortOperation = fn() => OPERACOES[GameHelpers::sortInt(0, (sizeof(OPERACOES) - 1))];

        // Função que gera o primeiro bloco da expressão matemática
        $generatePartial = function () use ($sortOperation) {
            $operation = $sortOperation();
            $firstNum = GameHelpers::sortNumber();

            $partial = $firstNum . ' ' . $operation . ' ';

            if ($operation != DIVISAO) {
                return $partial . GameHelpers::sortNumber();
            }

            return $partial . GameHelpers::sortDivisible($firstNum);
        };

        // Geração do primeiro bloco da expressão matemática
        $expression .= $generatePartial();

        // Laço para gerar os blocos restantes (começa de três, pois nós já geramos 2 números)
        if ($tam > 2) {
            $getLastNumber = function () use ($expression) {
                // Percorre por todas as operações até conseguir separar os números
                for ($op = 0; $op < sizeof(OPERACOES); $op++) {
                    // Separa os números do primeiro bloco de expressão (Exemplo: '10 / 25' -> ['10 ', '25'])
                    $last = explode(OPERACOES[$op],  $expression);

                    /* Se o primeiro índice do array não for vazio, significa que achou a operação correta e
                     separou corretamente os números do primeiro bloco (Exemplo: '10 / 25' -> ['10 ', '25']) */
                    if (isset($last[1])) {
                        return $last[1];
                    }
                }

                return 0;
            };

            $lastNumber = $getLastNumber();

            for ($bloco = 3; $bloco <= $tam; $bloco++) {
                $operation = $sortOperation();

                $operation == DIVISAO ? $lastNumber = GameHelpers::sortDivisible($lastNumber)
                    : $lastNumber = GameHelpers::sortNumber();

                $expression .= ' ' . $operation . ' ' . $lastNumber;
            }
        }

        return $expression;
    }

    private function solve() : float
    {
        return eval("return $this->expression;");
    }

    private function generateAnswers(int $quantity = (QUANTIDADE_DE_ALTERNATIVAS - 1)) : array
    {
        $answers = [];

        $sortAnswer = function () {
            // Sorteia uma operação matemática para randomizar o resultado
            $sortedOperation = OPERACOES_ALTERNATIVAS[
                GameHelpers::sortInt(0, (sizeof(OPERACOES_ALTERNATIVAS) - 1))];

            // Sorteia um valor para compor a expressao usada na randomização do valor
            $diff = GameHelpers::sortInt(1, DIFERENCA_DAS_ALTERNATIVAS);

            return eval("return $this->solved $sortedOperation $diff;");
        };

        /* Sorteia a quantidade de alternativas sem contar com a correta ($quantity está subtraindo 1 do limite
            de alternativas) */
        for ($i = 0; $i < $quantity; $i++) {
            $answers[$i] = $sortAnswer();
        }

        // Coloca a alternativa correta dentro do conjunto de alternativas
        $answers[sizeof($answers)] = $this->solved;

        // Randomiza a ordem das alternativas
        shuffle($answers);

        return $answers;
    }

    private function generate() : void
    {
        $this->expression = $this->generateExpression(GameHelpers::sortInt(2, TAMANHO_MAXIMO_EXPRESSAO));

        $solve = $this->solve();

        while ($solve  != intval($solve)) {
            $this->expression = $this->generateExpression(GameHelpers::sortInt(2, TAMANHO_MAXIMO_EXPRESSAO));
            $solve = $this->solve();

            $this->warnings += 1;
        }

        $this->solved = intval($this->solve());

        $this->answers = $this->generateAnswers();
    }
}