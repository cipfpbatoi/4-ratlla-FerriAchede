<?php

namespace Joc4enRatlla\Exceptions;

use Exception;

/**
 * Class TableFullException
 *
 * Excepción lanzada cuando se intenta realizar un movimiento en un tablero que está lleno.
 */
class TableFullException extends Exception 
{
    /**
     * @var string $message Mensaje de error que describe la excepción.
     */
    protected $message;

    /**
     * TableFullException constructor.
     *
     * @param string $message Mensaje opcional que se puede pasar al constructor.
     */
    public function __construct($message = "El tablero está lleno.") 
    {
        $this->message = $message;
        parent::__construct($this->message);
    }
}
