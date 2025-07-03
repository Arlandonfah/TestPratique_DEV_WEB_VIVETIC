<?php

namespace App\Enum;

enum EventType: string
{
    case ENTRY = 'entrée';
    case EXIT = 'sortie';


    public static function fromString(string $value): self
    {
        $value = strtolower($value);

        if (str_contains($value, 'entree') || str_contains($value, 'entrée') || str_contains($value, 'entry')) {
            return self::ENTRY;
        }

        if (str_contains($value, 'sortie') || str_contains($value, 'exit')) {
            return self::EXIT;
        }

        throw new \InvalidArgumentException("Valeur d'événement non reconnue: $value");
    }
}
