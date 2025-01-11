<?php

namespace Tests\Unit;

use App\CaesarCipher;
use PHPUnit\Framework\TestCase;

class CaesarCipherTest extends TestCase
{
    public function testEncryptDecrypt(): void
    {
        $cipher = new CaesarCipher(3, CaesarCipher::ALPHABET_EN);

        $originalText = 'Hello World!';

        $encryptedText = $cipher->encrypt($originalText);

        $this->assertEquals('Khoor Zruog!', $encryptedText);

        $decryptedText = $cipher->decrypt($encryptedText);

        $this->assertEquals($originalText, $decryptedText);
    }

    public function testEncryptDecryptWithRuAlphabet(): void
    {
        $cipher = new CaesarCipher(20);

        $originalText = 'Хлеб — всему голова!';

        $encryptedText = $cipher->encrypt($originalText);

        $this->assertEquals('Ияшф — хешаж цвявху!', $encryptedText);

        $decryptedText = $cipher->decrypt($encryptedText);

        $this->assertEquals($originalText, $decryptedText);
    }

    public function testMixedTextWithNonAlphabetCharacters(): void
    {
        $cipher = (new CaesarCipher(2))->alphabet(CaesarCipher::ALPHABET_EN);

        $originalText = 'Test123, mixed! Text.';

        $encryptedText = $cipher->encrypt($originalText);

        $this->assertEquals('Vguv123, okzgf! Vgzv.', $encryptedText);

        $decryptedText = $cipher->decrypt($encryptedText);

        $this->assertEquals($originalText, $decryptedText);
    }

    public function testEmptyString(): void
    {
        $cipher = new CaesarCipher(10);

        $originalText = '';
        $encryptedText = $cipher->encrypt($originalText);

        $this->assertEquals('', $encryptedText);

        $decryptedText = $cipher->decrypt($encryptedText);

        $this->assertEquals('', $decryptedText);
    }

    public function testShiftExceedingAlphabetLength(): void
    {
        $cipher = new CaesarCipher(30, CaesarCipher::ALPHABET_EN);

        $originalText = 'abc';
        $encryptedText = $cipher->encrypt($originalText);

        // Сдвиг 30 эквивалентен сдвигу 4 (30 % 26 = 4)
        $this->assertEquals('efg', $encryptedText);

        $decryptedText = $cipher->decrypt($encryptedText);

        $this->assertEquals($originalText, $decryptedText);
    }

    public function testNegativeShift(): void
    {
        $cipher = new CaesarCipher(-3, CaesarCipher::ALPHABET_EN);

        $originalText = 'xyz';
        $encryptedText = $cipher->encrypt($originalText);

        // Сдвиг -3 эквивалентен сдвигу 23 (26 - 3)
        $this->assertEquals('uvw', $encryptedText);

        $decryptedText = $cipher->decrypt($encryptedText);

        $this->assertEquals($originalText, $decryptedText);
    }

    public function testFullAlphabetCycle(): void
    {
        $cipher = new CaesarCipher(26, CaesarCipher::ALPHABET_EN);

        $originalText = 'Complete Cycle';
        $encryptedText = $cipher->encrypt($originalText);

        // Сдвиг 26 равен полному циклу, текст должен остаться неизменным
        $this->assertEquals($originalText, $encryptedText);

        $decryptedText = $cipher->decrypt($encryptedText);

        $this->assertEquals($originalText, $decryptedText);
    }
}
