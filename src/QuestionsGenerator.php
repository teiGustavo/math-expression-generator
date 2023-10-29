<?php

namespace Gteixeira\MathExpressionGenerator;

class QuestionsGenerator
{
    public int $errors = 0;

    public static function generate(int $quantity = QUANTIDADE_DE_QUESTOES) : QuestionsGenerator
    {
        return new QuestionsGenerator($quantity);
    }

    public function __construct(
        public int $quantity = QUANTIDADE_DE_QUESTOES,
        public array $questions = []
    ) {
        for ($i = 0; $i < $this->quantity; $i++) {
            $question = IntegerResultQuestion::get();
            $this->questions[$i] = $question;

            if (is_double($question->solved)) {
                $this->errors += 1;
            }
        }
    }
}