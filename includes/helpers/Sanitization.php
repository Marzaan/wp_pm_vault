<?php

namespace PM_Vault\helper;

class Sanitization {
    // Sanitize Vaule With Checking Empty
    public static function sanitize_value( $value ) {
        return (isset($value) && !empty($value)) ? sanitize_text_field($value) : NULL;
    }

    // Sanitze Value With Accepting Falsy Value Without Null
    public static function sanitize_with_fallback_zero( $value ) {
        return isset($value) ? sanitize_text_field( $value ) : 0;
    }
}