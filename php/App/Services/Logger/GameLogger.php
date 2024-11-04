<?php

namespace Joc4enRatlla\Services\Logger;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 * Class GameLogger
 *
 * Proporciona funcionalidad para registrar eventos del juego utilizando Monolog.
 * Permite registrar movimientos y errores en archivos de log separados.
 */
class GameLogger
{
    /**
     * @var Logger $logger Instancia del registrador de Monolog.
     */
    private Logger $logger;

    /**
     * GameLogger constructor.
     *
     * Inicializa el logger y configura los handlers para los archivos de log.
     */
    public function __construct()
    {
        $this->logger = new Logger('game_logger');
        $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../../logs/game.log', Logger::INFO));
        $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../../logs/error.log', Logger::ERROR));
    }

    /**
     * Registra un movimiento en el juego.
     *
     * @param string $message Mensaje que describe el movimiento realizado.
     */
    public function logMovement(string $message): void
    {
        $this->logger->info($message);
    }

    /**
     * Registra un error ocurrido en el juego.
     *
     * @param string $message Mensaje que describe el error.
     */
    public function logError(string $message): void
    {
        $this->logger->error($message);
    }
}
