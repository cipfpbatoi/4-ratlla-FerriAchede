<h3>Scores: </h3>
<main class="scores">
    <table>
        <ul>
            <?php foreach ($scores as $jugador => $score): ?>
                <li>Jugador <?php echo $jugador . ": " . $score; ?></li>
            <?php endforeach; ?>
        </ul>
    </table>
</main>
