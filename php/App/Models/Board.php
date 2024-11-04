<?php

namespace Joc4enRatlla\Models;

/**
 * Class Board
 *
 * Representa el tablero del juego de 4 en Ratlla. 
 * Gestiona la inicialización del tablero, los movimientos de los jugadores, 
 * la verificación de condiciones de victoria y el estado del tablero.
 */
class Board
{
    public const FILES = 6; // Número de filas en el tablero
    public const COLUMNS = 7; // Número de columnas en el tablero

    /**
     * @var array DIRECTIONS
     * Diferentes direcciones en las que se puede verificar la victoria: 
     * horizontal, vertical, diagonal abajo-derecha y diagonal abajo-izquierda.
     */
    public const DIRECTIONS = [
        [0, 1],   // Horizontal derecha
        [1, 0],   // Vertical abajo
        [1, 1],   // Diagonal abajo-derecha
        [1, -1]   // Diagonal abajo-izquierda
    ];

    /**
     * @var array $slots Matriz que representa el estado del tablero.
     */
    private array $slots;

    /**
     * Board constructor.
     *
     * Inicializa el tablero con valores vacíos.
     */
    public function __construct()
    {
        $this->slots = $this->initializeBoard();
    }

    // Getters y Setters

    /**
     * Obtiene el estado actual de las casillas del tablero.
     *
     * @return array El arreglo que representa las casillas del tablero.
     */
    public function getSlots(): array
    {
        return $this->slots;
    }

    /**
     * Inicializa la grilla con valores vacíos.
     *
     * @return array La matriz inicializada con valores null.
     */
    private static function initializeBoard(): array
    {
        $table = [];
        for ($i = 1; $i <= self::FILES; $i++) {
            for ($j = 1; $j <= self::COLUMNS; $j++) {
                $table[$i][$j] = null;
            }
        }
        return $table;
    }

    /**
     * Realiza un movimiento en la grilla.
     *
     * @param int $column La columna donde se quiere realizar el movimiento.
     * @param int $player El identificador del jugador que realiza el movimiento.
     * @return array Las coordenadas del movimiento realizado.
     * @throws \Joc4enRatlla\Exceptions\ColumnFullException Si la columna está llena.
     */
    public function setMovementOnBoard(int $column, int $player): array
    {
        for ($i = self::FILES; $i >= 1; $i--) { // Cambié a 1 para comenzar desde 1
            if ($this->slots[$i][$column] === null) {
                $this->slots[$i][$column] = $player;
                return [$i, $column];
            }
        }
        throw new \Joc4enRatlla\Exceptions\ColumnFullException();
    }

    /**
     * Verifica si hay un ganador en el tablero.
     *
     * @param array $coord Coordenadas del último movimiento.
     * @return bool Verdadero si hay un ganador, falso de lo contrario.
     */
    public function checkWin(array $coord): bool
    {
        [$row, $col] = $coord;
        $player = $this->slots[$row][$col];

        foreach (self::DIRECTIONS as $direction) {
            $count = 1;
            $count += $this->countInDirection($row, $col, $direction[0], $direction[1], $player);
            $count += $this->countInDirection($row, $col, -$direction[0], -$direction[1], $player);

            if ($count >= 4) {
                return true;
            }
        }
        return false;
    }

    /**
     * Cuenta cuántas fichas del jugador hay en una dirección específica.
     *
     * @param int $row Fila inicial.
     * @param int $col Columna inicial.
     * @param int $dRow Cambio en la fila para moverse en la dirección.
     * @param int $dCol Cambio en la columna para moverse en la dirección.
     * @param int $player Identificador del jugador.
     * @return int El número de fichas del jugador en la dirección especificada.
     */
    private function countInDirection(int $row, int $col, int $dRow, int $dCol, int $player): int
    {
        $count = 0;
        $i = $row + $dRow;
        $j = $col + $dCol;

        while ($i >= 1 && $i <= self::FILES && $j >= 1 && $j <= self::COLUMNS && $this->slots[$i][$j] === $player) {
            $count++;
            $i += $dRow;
            $j += $dCol;
        }

        return $count;
    }

    /**
     * Verifica si el tablero está lleno.
     *
     * @return bool Verdadero si el tablero está lleno, falso de lo contrario.
     * @throws \Joc4enRatlla\Exceptions\TableFullException Si el tablero está lleno.
     */
    public function isFull(): bool
    {
        for ($j = 1; $j <= self::COLUMNS; $j++) {
            if ($this->slots[1][$j] === null) {
                return false; // Si hay al menos una casilla vacía, no está lleno
            }
        }
        return true;
        throw new \Joc4enRatlla\Exceptions\TableFullException();
    }

    /**
     * Verifica si un movimiento en una columna es válido.
     *
     * @param int $column La columna que se desea verificar.
     * @return bool Verdadero si el movimiento es válido, falso de lo contrario.
     */
    public function isValidMove(int $column): bool
    {
        // Verifica si hay algún espacio vacío en la columna especificada
        for ($i = self::FILES; $i >= 1; $i--) {
            if ($this->slots[$i][$column] === null) {
                return true; // Hay un espacio vacío en la columna
            }
        }
        return false; // La columna está llena
    }
}
