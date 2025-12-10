<?php

namespace App\Services;

class IdHasher
{
    private static $salt = 'TokoGH2024!';
    
    /**
     * Encode an ID to a hash string
     */
    public static function encode($id): string
    {
        $combined = self::$salt . $id . self::$salt;
        $hash = base64_encode($combined);
        // Make URL safe
        $hash = str_replace(['+', '/', '='], ['-', '_', ''], $hash);
        return $hash;
    }
    
    /**
     * Decode a hash string back to ID
     */
    public static function decode($hash): ?int
    {
        try {
            // Restore base64 characters
            $hash = str_replace(['-', '_'], ['+', '/'], $hash);
            // Add padding if needed
            $padding = 4 - (strlen($hash) % 4);
            if ($padding !== 4) {
                $hash .= str_repeat('=', $padding);
            }
            
            $decoded = base64_decode($hash);
            if ($decoded === false) {
                return null;
            }
            
            // Extract ID from decoded string
            $saltLength = strlen(self::$salt);
            $id = substr($decoded, $saltLength, -$saltLength);
            
            if (is_numeric($id)) {
                return (int) $id;
            }
            
            return null;
        } catch (\Exception $e) {
            return null;
        }
    }
}
