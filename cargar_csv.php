<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_FILES['csvFile']) && $_FILES['csvFile']['error'] === UPLOAD_ERR_OK) {
        $csvFile = $_FILES['csvFile']['tmp_name'];
        $handle = fopen($csvFile, 'r');
        if ($handle !== false) {
            include("conexion.php"); // Incluye la conexión a la base de datos

            while (($data = fgetcsv($handle)) !== false) {
                if (count($data) == 7) { // Asegura que hay 7 campos en cada fila del CSV
                    $autor = mysqli_real_escape_string($con, $data[0]);
                    $titulo = mysqli_real_escape_string($con, $data[1]);
                    $palabrasClave = mysqli_real_escape_string($con, $data[2]);
                    $isbn = mysqli_real_escape_string($con, $data[3]);
                    $materia = mysqli_real_escape_string($con, $data[4]);
                    $editor = mysqli_real_escape_string($con, $data[5]);
                    $precio = floatval($data[6]);

                    // Realiza la inserción en la base de datos
                    $insertQuery = "INSERT INTO libros(Autor, Titulo, PalabrasClave, ISBN, Materia, Editor, Precio) 
                    VALUES ('$autor', '$titulo', '$palabrasClave', '$isbn', '$materia', '$editor', $precio)";
                    
                    mysqli_query($con, $insertQuery);
                }
            }

            fclose($handle);
            mysqli_close($con);
            header("Location: index.php?res=1");
        } else {
            header("Location: index.php?res=2");
        }
    } else {
        header("Location: index.php?res=3");
    }
} else {
    echo "Acceso no autorizado.";
}
?>
