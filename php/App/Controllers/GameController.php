<?php

namespace Joc4enRatlla\Controllers;

use Joc4enRatlla\Models\Player;
use Joc4enRatlla\Models\Game;
use Joc4enRatlla\Services\Logger\GameLogger;

/**
 * Class GameController
 *
 * Controlador para gestionar la lógica del juego de 4 en Ratlla.
 */
class GameController
{
    /**
     * @var Game $game Instancia del juego en curso.
     */
    private Game $game;

    // private GameLogger $logger; // Se puede descomentar si se usa el logger

    /**
     * GameController constructor.
     *
     * Inicializa un nuevo juego o restaura uno existente.
     *
     * @param array|null $request Datos de la solicitud, incluyendo nombres y colores de los jugadores.
     */
    public function __construct($request = null)
    {
        // $this->logger = new GameLogger(); // Se puede descomentar si se usa el logger
        if (isset($request['nombre1'], $request['color1']) && isset($request['nombre2'], $request['color2'])) {
            $_SESSION['jugador1'] = new Player(htmlspecialchars($request['nombre1']), htmlspecialchars($request['color1']));
            $_SESSION['jugador2'] = new Player(htmlspecialchars($request['nombre2']), htmlspecialchars($request['color2']), true);

            $this->game = new Game($_SESSION['jugador1'], $_SESSION['jugador2']);
            $this->game->save();

            // $this->logger->logMovement("Nueva partida iniciada entre {$_SESSION['jugador1']->getName()} y {$_SESSION['jugador2']->getName()}.");
        } else {
            $this->game = Game::restore();
            // $this->logger->logMovement("Restaurando el juego.");
        }
        $this->play($request);
        // dd($this->game);
    }

    /**
     * Juega una ronda del juego.
     *
     * Gestiona los movimientos de los jugadores, verifica ganadores y actualiza el estado del juego.
     *
     * @param array $request Datos de la solicitud, incluyendo movimientos y acciones (reiniciar, salir).
     * @throws \Joc4enRatlla\Exceptions\ColumnFullException Si la columna seleccionada está llena.
     * @throws \Joc4enRatlla\Exceptions\TableFullException Si la tabla está llena.
     */
    public function play(array $request)
    {
        // Gestió del joc

        $board = $this->game->getBoard();
        $players = $this->game->getPlayers();
        $winner = $this->game->getWinner();
        $scores = $this->game->getScores();

        if (isset($request['reset'])) {
            $this->game->reset();
            // $this->logger->logMovement("El juego se ha reiniciado.");
        }

        if (isset($request['exit'])) {
            session_destroy();
            header('Location: index.php');
            exit();
        }

        try {
            if ((isset($request['columna'])) && (!isset($request['reset']))) {
                $columna = (int)$request['columna'];
                $lastMoveCoords = $board->setMovementOnBoard($columna, $this->game->getNextPlayer());

                // $this->logger->logMovement("El jugador {$players[$this->game->getNextPlayer()]->getName()} ha realizado un movimiento en la columna $columna.");

                if (!empty($lastMoveCoords) && $board->checkWin($lastMoveCoords)) {
                    $winningPlayer = $players[$this->game->getNextPlayer()];
                    $this->game->setWinner($winningPlayer);
                    $scores[$this->game->getNextPlayer()]++;
                    $this->game->setScores($scores);

                    // $this->logger->logMovement("El jugador {$winningPlayer->getName()} ha ganado la partida.");
                } else {
                    $this->game->setNextPlayer($this->game->getNextPlayer() === 1 ? 2 : 1);
                    $this->game->playAutomatic();
                    $this->game->setNextPlayer($this->game->getNextPlayer() === 1 ? 2 : 1);
                    // $this->logger->logMovement("El jugador automático ha realizado un movimiento.");
                }
                
            }
        } catch (\Joc4enRatlla\Exceptions\ColumnFullException $e) {
            echo $e->getMessage();
            // $this->logger->logError("Error: {$e->getMessage()}");
        } catch (\Joc4enRatlla\Exceptions\TableFullException $e) {
            echo $e->getMessage();
            // $this->logger->logError("Error: {$e->getMessage()}");
        }

        $this->game->save();
        loadView('index', compact('board', 'players', 'winner', 'scores'));
    }
}
