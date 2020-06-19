<?php


namespace App\Service;

/**
 * Class EncoderService
 *
 * @package App\Service
 */
class EncoderService
{
    const ENCRYPTION_ALGO = 'sha256';

    /**
     * @param string      $string
     *
     * @param string|null $salt
     *
     * @return array
     * @throws \Exception
     */
    public function encode(string $string, ?string $salt = null): array
    {
        $salt = $salt ?? $this->generateRandomHash();

        return [
            'hash' => hash_pbkdf2(self::ENCRYPTION_ALGO, $string, $salt, 1000),
            'salt' => $salt,
        ];
    }

    /**
     * @param $password
     * @param $salt
     * @param $excepted
     *
     * @return bool
     * @throws \Exception
     */
    public function checkPassword(string $password, string $salt, string $excepted): bool
    {
        return $this->encode($password, $salt)['hash'] === $excepted;
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function generateRandomHash(): string
    {
        return md5(random_bytes(16));
    }
}