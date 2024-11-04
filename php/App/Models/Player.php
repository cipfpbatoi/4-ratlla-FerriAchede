<?php

namespace Joc4enRatlla\Models;

/**
 * Class Player
 *
 * Representa a un jugador en el juego.
 * Cada jugador tiene un nombre, un color para sus fichas
 * y una indicación de si juega de manera automática o manual.
 */
class Player {
    /**
     * @var string $name Nombre del jugador.
     */
    private string $name;

    /**
     * @var string $color Color de las fichas del jugador.
     */
    private string $color;

    /**
     * @var bool $isAutomatic Indica si el jugador juega de forma automática.
     */
    private bool $isAutomatic;

    /**
     * Player constructor.
     *
     * Inicializa un nuevo jugador con un nombre, color y modo de juego.
     *
     * @param string $name El nombre del jugador.
     * @param string $color El color de las fichas del jugador.
     * @param bool $isAutomatic Indica si el jugador juega de forma automática (por defecto es false).
     */
    public function __construct(string $name, string $color, bool $isAutomatic = false) {
        $this->name = $name;
        $this->color = $color;
        $this->isAutomatic = $isAutomatic;
    }

    // Getters

    /**
     * Obtiene el nombre del jugador.
     *
     * @return string El nombre del jugador.
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Obtiene el color de las fichas del jugador.
     *
     * @return string El color de las fichas.
     */
    public function getColor(): string {
        return $this->color;
    }

    /**
     * Indica si el jugador juega de forma automática.
     *
     * @return bool Verdadero si el jugador juega automáticamente, falso de lo contrario.
     */
    public function getIsAutomatic(): bool {
        return $this->isAutomatic;
    }

    // Setters

    /**
     * Establece el nombre del jugador.
     *
     * @param string $name El nuevo nombre del jugador.
     */
    public function setName(string $name): void {
        $this->name = $name;
    }

    /**
     * Establece el color de las fichas del jugador.
     *
     * @param string $color El nuevo color de las fichas.
     */
    public function setColor(string $color): void {
        $this->color = $color;
    }

    /**
     * Establece el modo de juego del jugador (automático/manual).
     *
     * @param bool $isAutomatic Verdadero para modo automático, falso para modo manual.
     */
    public function setIsAutomatic(bool $isAutomatic): void {
        $this->isAutomatic = $isAutomatic;
    }
}
