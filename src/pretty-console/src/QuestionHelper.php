<?php

declare(strict_types=1);
/**
 * This file is part of friendsofhyperf/components.
 *
 * @link     https://github.com/friendsofhyperf/components
 * @document https://github.com/friendsofhyperf/components/blob/main/README.md
 * @contact  huangdijia@gmail.com
 */
namespace FriendsOfHyperf\PrettyConsole;

use FriendsOfHyperf\PrettyConsole\View\Components\TwoColumnDetail;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Helper\SymfonyQuestionHelper;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

use function Hyperf\Stringable\str;
use function Hyperf\Support\with;

class QuestionHelper extends SymfonyQuestionHelper
{
    protected function writePrompt(OutputInterface $output, Question $question)
    {
        $text = OutputFormatter::escapeTrailingBackslash($question->getQuestion());
        $text = $this->ensureEndsWithPunctuation($text);
        $text = "  <fg=default;options=bold>{$text}</></>";
        $default = $question->getDefault();

        if ($question->isMultiline()) {
            $text .= sprintf(' (press %s to continue)', 'Windows' == PHP_OS_FAMILY
                ? '<comment>Ctrl+Z</comment> then <comment>Enter</comment>'
                : '<comment>Ctrl+D</comment>');
        }

        switch (true) {
            case $default === null:
                $text = sprintf('<info>%s</info>', $text);

                break;
            case $question instanceof ConfirmationQuestion:
                $text = sprintf('<info>%s (yes/no)</info> [<comment>%s</comment>]', $text, $default ? 'yes' : 'no');

                break;
            case $question instanceof ChoiceQuestion:
                $choices = $question->getChoices();
                $text = sprintf('<info>%s</info> [<comment>%s</comment>]', $text, OutputFormatter::escape($choices[$default] ?? $default));

                break;
        }

        $output->writeln($text);

        if ($question instanceof ChoiceQuestion) {
            foreach ($question->getChoices() as $key => $value) {
                with(new TwoColumnDetail($output))->render($value, $key);
            }
        }

        $output->write('<options=bold>❯ </>');
    }

    /**
     * Ensures the given string ends with punctuation.
     *
     * @param string $string
     * @return string
     */
    protected function ensureEndsWithPunctuation($string)
    {
        if (! str($string)->endsWith(['?', ':', '!', '.'])) {
            return "{$string}:";
        }

        return $string;
    }
}
