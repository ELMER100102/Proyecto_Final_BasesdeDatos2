<?php

return [
    /*
     | Carpeta donde SQL Server guarda los .bak del botón "Respaldar".
     | Debe estar FUERA del proyecto (no en OneDrive ni en el repositorio) y la
     | cuenta de servicio de SQL Server debe tener permiso de escritura en ella.
     | Vacío = carpeta de respaldos por defecto de la instancia (sin descarga desde el panel
     | si PHP no tiene permiso de lectura ahí).
     */
    'carpeta' => env('RESPALDOS_CARPETA'),
];
