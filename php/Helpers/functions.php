<?php

/**
 * Carga una vista y pasa datos a ella.
 *
 * @param string $view Nombre de la vista a cargar.
 * @param array $data Datos que se pasarán a la vista (opcional).
 */
function loadView(string $view, array $data = []): void
{
    Joc4enRatlla\Services\Service::loadView($view, $data);
}

/**
 * Imprime los datos de forma legible y detiene la ejecución del script.
 *
 * @param mixed ...$data Datos a imprimir.
 */
function dd(...$data): void
{
    echo "<pre>";
    foreach ($data as $d) {
        var_dump($d);
    }

    echo "</pre>";
    die();
}
