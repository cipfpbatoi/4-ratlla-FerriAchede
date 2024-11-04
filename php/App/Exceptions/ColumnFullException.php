<?php

namespace Joc4enRatlla\Exceptions;

use Exception;

/**
 * Class ColumnFullException
 *
 * Excepción lanzada cuando se intenta realizar un movimiento en una columna que está llena.
 */
class ColumnFullException extends Exception 
{
    /**
     * @var string $message Mensaje de error que describe la excepción.
     */
    protected $message;

    /**
     * ColumnFullException constructor.
     *
     * @param string $message Mensaje opcional que se puede pasar al constructor.
     */
    public function __construct($message = "La columna está llena.") 
    {
        $this->message = $message;
        parent::__construct($this->message);
    }
}
