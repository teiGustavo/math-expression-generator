<?php

require __DIR__ . '/vendor/autoload.php';

use Gteixeira\MathExpressionGenerator\QuestionsGenerator;


$generator = QuestionsGenerator::generate();

foreach ($generator->questions as $number => $question) {
    echo "\n";
    echo "QUESTION : " . ($number + 1) .  "\n";
    echo "  " . $question->expression . "\n\n";

    for ($i = 1; $i <= sizeof($question->answers); $i++) {
        echo "  ANSWER $i: " . $question->answers[($i - 1)] . "\n";
    }

    echo "\n  CORRECT ANSWER: " . $question->solved . "\n";
    echo "\n";
}
