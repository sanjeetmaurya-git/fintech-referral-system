<?php

use Config\Services;

/**
 * Encrypts a value using CodeIgniter's Encryption service.
 */
if (!function_exists('encrypt_value')) {
    function encrypt_value(string $value): string
    {
        $encrypter = Services::encrypter();
        return bin2hex($encrypter->encrypt($value));
    }
}

/**
 * Decrypts a value using CodeIgniter's Encryption service.
 */
if (!function_exists('decrypt_value')) {
    function decrypt_value(string $value): string
    {
        try {
            $encrypter = Services::encrypter();
            return $encrypter->decrypt(hex2bin($value));
        } catch (\Exception $e) {
            return $value; // Return original if decryption fails (e.g. wasn't encrypted)
        }
    }
}
