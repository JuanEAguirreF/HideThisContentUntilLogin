<?php
/**
 * Script para generar un archivo ZIP del plugin con la versión en el nombre
 */

// Obtener la versión del plugin desde el archivo principal
$plugin_file = file_get_contents('hide-this-content-until-login.php');
preg_match('/Version:\s*([0-9\.]+)/', $plugin_file, $matches);
$version = isset($matches[1]) ? $matches[1] : '0.0.0';

// Nombre del archivo ZIP
$zip_filename = 'hide-this-content-until-login-' . $version . '.zip';

// Crear un nuevo objeto ZipArchive
$zip = new ZipArchive();

// Abrir el archivo ZIP para escritura
if ($zip->open($zip_filename, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
    exit("No se pudo crear el archivo ZIP\n");
}

// Archivos y carpetas a incluir
$files_to_include = [
    'hide-this-content-until-login.php',
    'README.md',
    'admin/class-htcul-admin.php',
    'build/index.js',
    'build/index.css',
    'build/index.asset.php',
    'src/index.js',
    'src/editor.scss'
];

// Añadir cada archivo al ZIP
foreach ($files_to_include as $file) {
    if (file_exists($file)) {
        if (is_dir($file)) {
            // Si es un directorio, añadir recursivamente
            addDirToZip($zip, $file, '');
        } else {
            // Si es un archivo, añadirlo directamente
            $zip->addFile($file, $file);
        }
    } else {
        echo "Advertencia: El archivo $file no existe y no se incluirá en el ZIP.\n";
    }
}

// Función para añadir directorios recursivamente
function addDirToZip($zip, $dir, $base_dir) {
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file == '.' || $file == '..') continue;
        
        $file_path = $dir . '/' . $file;
        $relative_path = ($base_dir === '') ? $file_path : $base_dir . '/' . $file;
        
        if (is_dir($file_path)) {
            // Crear directorio en el ZIP
            $zip->addEmptyDir($relative_path);
            // Añadir contenido del directorio
            addDirToZip($zip, $file_path, $relative_path);
        } else {
            // Añadir archivo
            $zip->addFile($file_path, $relative_path);
        }
    }
}

// Cerrar el archivo ZIP
$zip->close();

echo "Archivo ZIP creado correctamente: $zip_filename\n";