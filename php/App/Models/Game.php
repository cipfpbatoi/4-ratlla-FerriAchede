<?php

namespace Joc4enRatlla\Models;

use Joc4enRatlla\Models\Board;
use Joc4enRatlla\Models\Player;

/**
 * Class Game
 *
 * Representa el juego de 4 en Ratlla. 
 * Gestiona el estado del tablero, los jugadores, 
 * los movimientos y las condiciones de victoria.
 */
class Game
{
    /**
     * @var Board $board Instancia del tablero del juego.
     */
    private Board $board;

    /**
     * @var int $nextPlayer El jugador que tiene el turno.
     */
    private int $nextPlayer;

    /**
     * @var array $players Arreglo que contiene los jugadores.
     */
    private array $players;

    /**
     * @var Player|null $winner Jugador que ha ganado, si existe.
     */
    private ?Player $winner;

    /**
     * @var array $scores Puntajes de los jugadores.
     */
    private array $scores = [1 => 0, 2 => 0];

    /**
     * Game constructor.
     *
     * Inicializa el juego con dos jugadores.
     *
     * @param Player $jugador1 Primer jugador.
     * @param Player $jugador2 Segundo jugador.
     */
    public function __construct(Player $jugador1, Player $jugador2){
        $this->players = [1 => $jugador1, 2 => $jugador2];
        $this->nextPlayer = 1;
        $this->board = new Board();
        $this->winner = null;
    }

    // Getters

    /**
     * Obtiene el tablero del juego.
     *
     * @return Board El tablero actual del juego.
     */
    public function getBoard(): Board {
        return $this->board;
    }

    /**
     * Obtiene el jugador que tiene el turno.
     *
     * @return int El número del jugador que tiene el turno.
     */
    public function getNextPlayer(): int {
        return $this->nextPlayer;
    }

    /**
     * Obtiene los jugadores del juego.
     *
     * @return array Arreglo que contiene los jugadores.
     */
    public function getPlayers(): array {
        return $this->players;
    }

    /**
     * Obtiene el ganador del juego, si existe.
     *
     * @return Player|null El jugador ganador o null si no hay ganador.
     */
    public function getWinner(): ?Player {
        return $this->winner;
    }

    /**
     * Obtiene los puntajes de los jugadores.
     *
     * @return array Arreglo que contiene los puntajes de los jugadores.
     */
    public function getScores(): array {
        return $this->scores;
    }

    // Setters

    /**
     * Establece el tablero del juego.
     *
     * @param Board $board El nuevo tablero a establecer.
     */
    public function setBoard(Board $board): void {
        $this->board = $board;
    }

    /**
     * Establece el siguiente jugador.
     *
     * @param int $nextPlayer El número del siguiente jugador.
     */
    public function setNextPlayer(int $nextPlayer): void {
        $this->nextPlayer = $nextPlayer;
    }

    /**
     * Establece los jugadores del juego.
     *
     * @param array $players Arreglo de jugadores a establecer.
     */
    public function setPlayers(array $players): void {
        $this->players = $players;
    }

    /**
     * Establece el ganador del juego.
     *
     * @param Player|null $winner El jugador ganador o null si no hay ganador.
     */
    public function setWinner(?Player $winner): void {
        $this->winner = $winner;
    }

    /**
     * Establece los puntajes de los jugadores.
     *
     * @param array $scores Arreglo de puntajes a establecer.
     */
    public function setScores(array $scores): void {
        $this->scores = $scores;
    }

    /**
     * Reinicia el juego, restableciendo el tablero y el estado de los jugadores.
     */
    public function reset(): void {
        $this->board = new Board();
        $this->nextPlayer = 1;
        $this->winner = null;
    }
     
    /**
     * Realiza un movimiento en el tablero.
     *
     * @param int $columna La columna donde se desea realizar el movimiento.
     */
    public function play(int $columna): void {
        $this->board->setMovementOnBoard($columna, $this->nextPlayer);
    }

    /**
     * Realiza un movimiento automático para el jugador.
     * El jugador intentará ganar, bloquear al oponente o elegir una columna aleatoria.
     */
    public function playAutomatic(): void {
        $opponent = $this->nextPlayer === 1 ? 2 : 1;
    
        // Intentar ganar
        for ($col = 1; $col <= Board::COLUMNS; $col++) {
            $tempBoard = clone($this->board);
            if ($tempBoard->isValidMove($col)) { // Verifica que el movimiento sea válido
                $coord = $tempBoard->setMovementOnBoard($col, $this->nextPlayer);
                if ($tempBoard->checkWin($coord)) {
                    $this->play($col); // Realiza el movimiento en el juego real
                    $this->setWinner($this->players[$this->nextPlayer]); // Asigna al ganador
                    $this->scores[2]++;
                    return; // Termina el método después de hacer el movimiento
                }
            }
        }
    
        // Intentar bloquear al oponente si está a punto de ganar
        for ($col = 1; $col <= Board::COLUMNS; $col++) {
            $tempBoard = clone($this->board);
            if ($tempBoard->isValidMove($col)) { // Verifica que el movimiento sea válido
                $coord = $tempBoard->setMovementOnBoard($col, $opponent);
                if ($tempBoard->checkWin($coord)) {
                    $this->play($col); // Realiza el movimiento en el juego real
                    return; // Termina el método después de hacer el movimiento
                }
            }
        }
    
        // Elegir una columna aleatoria disponible
        $possibles = [];
        for ($col = 1; $col <= Board::COLUMNS; $col++) {
            if ($this->board->isValidMove($col)) {
                $possibles[] = $col;
            }
        }
    
        if (count($possibles) > 0) {
            $random = rand(-1, 1);
            $middle = (int)(count($possibles) / 2) + $random;
    
            // Asegúrate de que no excedas los límites de los índices
            if ($middle < 0) {
                $middle = 0;
            } elseif ($middle >= count($possibles)) {
                $middle = count($possibles) - 1;
            }
    
            $inthemiddle = $possibles[$middle];
            $this->play($inthemiddle); // Realiza el movimiento en el juego real
        }
    }
    
    /**
     * Guarda el estado del juego en la sesión.
     */
    public function save(): void {
        $_SESSION['game'] = serialize($this);
    }

    /**
     * Restaura el estado del juego desde la sesión.
     *
     * @return Game|null El objeto Game restaurado o null si no existe en la sesión.
     */
    public static function restore(): ?Game {
        if (isset($_SESSION['game'])) {
            return unserialize($_SESSION['game'], [Game::class]);
        }
        return null;
    }
}
