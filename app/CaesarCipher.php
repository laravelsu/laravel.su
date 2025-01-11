<?php

namespace App;

class CaesarCipher
{
    const ALPHABET_EN = 'abcdefghijklmnopqrstuvwxyz';
    const ALPHABET_RU = 'абвгдеёжзийклмнопрстуфхцчшщъыьэюя';

    /**
     * @param int    $shift
     * @param string $alphabet
     */
    public function __construct(private int $shift, private string $alphabet = self::ALPHABET_RU) {}

    /**
     * @param string|null $alphabet
     *
     * @return $this
     */
    public function alphabet(?string $alphabet = self::ALPHABET_RU): static
    {
        $this->alphabet = $alphabet;

        return $this;
    }

    /**
     * @param string $text
     * @param int    $shift
     *
     * @return string
     */
    private function process(string $text, int $shift): string
    {
        $processedText = '';
        $alphabetLength = mb_strlen($this->alphabet);

        for ($i = 0; $i < mb_strlen($text); $i++) {
            $char = mb_substr($text, $i, 1);

            // Ищем позицию символа в алфавите
            $position = mb_strpos($this->alphabet, mb_strtolower($char));

            // Если символ не найден в алфавите, оставляем его без изменений
            if ($position === false) {
                $processedText .= $char;
                continue;
            }

            // Сдвигаем позицию символа
            $newPosition = ($position + $shift + $alphabetLength) % $alphabetLength;
            $processedChar = mb_substr($this->alphabet, $newPosition, 1);

            // Учитываем регистр символа
            $processedText .= mb_strtoupper($char) === $char
                ? mb_strtoupper($processedChar)
                : $processedChar;
        }

        return $processedText;
    }

    /**
     * @param string $text
     *
     * @return string
     */
    public function encrypt(string $text): string
    {
        return $this->process($text, $this->shift);
    }

    /**
     * @param string $encryptedText
     *
     * @return string
     */
    public function decrypt(string $encryptedText): string
    {
        return $this->process($encryptedText, -$this->shift);
    }
}
