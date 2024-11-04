<?php

use Joc4enRatlla\Models\Game;
use Joc4enRatlla\Models\Player;
use Joc4enRatlla\Models\Board;
use Joc4enRatlla\Exceptions\ColumnFullException;

class GameTest extends PHPUnit\Framework\TestCase
{
    private Game $game;
    private Player $player1;
    private Player $player2;

    protected function setUp(): void
    {
        $this->player1 = new Player("Jugador 1", "Rojo");
        $this->player2 = new Player("Jugador 2", "Amarillo");
        $this->game = new Game($this->player1, $this->player2);
    }

    public function testInitialBoardSetup()
    {
        $board = $this->game->getBoard();
        $this->assertCount(6, $board->getSlots()); // Debe haber 6 filas
        foreach ($board->getSlots() as $row) {
            $this->assertCount(7, $row); // Debe haber 7 columnas
            foreach ($row as $slot) {
                $this->assertNull($slot); // Todos los espacios deben estar vacíos
            }
        }
    }

    public function testMovementIsAppliedCorrectly()
    {
        $column = 1;
        $this->game->play($column);
        $this->assertEquals(1, $this->game->getBoard()->getSlots()[6][1]); // Verificar que el jugador 1 ocupa el espacio
    }

    public function testDetectsDraw()
    {
        // Llenar el tablero sin ganador
        for ($i = 1; $i <= 7; $i++) {
            for ($j = 1; $j <= 6; $j++) {
                $this->game->play($i);
                $this->game->setNextPlayer($j % 2 == 0 ? 1 : 2);
            }
        }
        $this->assertTrue($this->game->getBoard()->isFull());
        $this->assertNull($this->game->getWinner());
    }

    public function testGameStateIsSaved()
    {
        $this->game->play(1);
        $this->game->save();
        
        $restoredGame = Game::restore();
        $this->assertEquals($this->game->getNextPlayer(), $restoredGame->getNextPlayer());
        $this->assertEquals($this->game->getBoard()->getSlots(), $restoredGame->getBoard()->getSlots());
    }

    public function testColumnFullException()
    {
        for ($i = 1; $i <= 6; $i++) {
            $this->game->play(1);
        }

        $this->expectException(ColumnFullException::class);
        $this->game->play(1); // Intentar jugar en una columna llena
    }
}
