<?php
namespace utils;

use DateTime;
use Exception;

class ValidationUtils
{
    /**
     * Sanea y Valida numero y lo devuelve en Integer
     * @param $input string a sanear y validar
     * @return int|null devuelve el numero en integer o null si no es un numero
     */
    public static function SVNumero(string $input): ?int
    {
        $cleanedInput = htmlspecialchars(trim($input), ENT_QUOTES, 'utf-8');
        if (ctype_digit($cleanedInput)) {
            $validatedInteger = (int)$cleanedInput;
            return $validatedInteger;
        } else {
            return null;
        }
    }

    /**
     * Sanea y Valida numero y lo devuelve en Float
     * @param string $input
     * @return float|null
     */
    public static function SVNumeroFloat($num): ?float
    {
        $cleanedInput = htmlspecialchars(trim($num), ENT_QUOTES, 'utf-8');
        if (is_numeric($cleanedInput)) {
            $validatedInteger = (float)$cleanedInput;
            return $validatedInteger;
        } else {
            return null;
        }
    }

    /**
     * Sanea un booleano
     * @param $value
     * @return mixed
     */
    public static function SBoolean($value) {
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Funcion que sanea un string, retirando etiquetas,limpiando string por ambas partes,
     * corrige entidades html como por ejemplo quot y las convierte a sus respectivos
     * caracteres
     * @param $valor string a sanear
     * @return string string saneado
     */
    public static function sanidarStringFiltro(string $valor): string
    {

        //Retira etiquetas html y php
        $valor = strip_tags($valor);

        //limpia el string por delante y x detras
        $valor = htmlspecialchars($valor, ENT_QUOTES);

        //corige entidades html y las cambia
        $valor = str_replace(["quot;", "&#039;"], ["&#34;", "&#39;"], $valor);

        //convierte las entidades como "quot;" en sus respectivos caracteres
        return html_entity_decode($valor);
    }

    /**
     * si $texto esta vacio retornara false, de lo contrario retornara true
     * @param $texto string a comprobar
     * @return bool true si no esta vacio, false si esta vacio
     */
    public static function noEstaVacio( string $texto): bool
    {
        return !(trim($texto) == '');
    }

    /**
     * Comprueba que solo contiene letras minusculas/mayusculas, ascetnos y espacios
     * (\s acepta espacios)
     * @param $texto string a comprobar
     * @return bool true si solo contiene letras, false si no
     */
    public static function son_letras(string $texto): bool
    {
        $patron = "/^[a-zA-Záéíóú\s]+$/";
        return preg_match($patron, $texto);
    }

    /**
     * Comprueba que solo contiene letras minusculas/mayusculas, ascetnos y espacios
     * @param $texto string a comprobar
     * @return bool true si solo contiene letras, false si no
     */
    public static function son_letras_y_numeros(string $texto): bool
    {
        $patron = "/^[a-zA-Záéíóú0-9\s]+$/u";
        return preg_match($patron, $texto) === 1;
    }

    /**
     * Comprueba que el tamaño del texto no sea mayor que el maximo
     * @param $texto string a comprobar
     * @param $maximo int tamaño maximo del texto
     * @return bool true si no es mayor, false si es mayor
     */
    public static function TextoNoEsMayorQue(string $texto,int $maximo): bool
    {
        return strlen($texto) <= $maximo;
    }

    /**
     * Funcion que comprueba que una contraseña solo tiene determinados caracteres
     * @param $password string contraseña a comprobar
     * @return bool true si la contraseña es valida, false si no
     */
    public static function validarContrasena(string $password): bool
    {
        // Verifica si la contraseña contiene solo letras (mayúsculas y minúsculas), números y los caracteres especiales: _ / .
        return preg_match('/^[A-Za-z0-9_\/.]+$/', $password) === 1;
    }

    /**
     * Valida una fecha y la devuelve la fecha o null si no es valida
     * @param $date string fecha a validar
     * @param $format string formato de la fecha
     * @return null si la fecha no es valida
     */
    public static function sanitizeAndValidateDate($date, $format = 'Y-m-d') {
        try {
            $dateTime = new DateTime($date);

            if ($dateTime && $dateTime->format($format) === $date) {
                return $dateTime->format($format);
            } else {
                return null;
            }
        } catch (Exception $e) {
            return null;
        }
    }

}