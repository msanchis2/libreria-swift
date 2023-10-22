<?php
include("conexion.php");
session_start();
if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit; 
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Libros Swift</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/bootstrap-datepicker.css" rel="stylesheet">
	<link href="css/style_nav.css" rel="stylesheet">
	<style>
		.content {
			margin-top: 80px;
		}
	</style>
</head>
<body>
	<nav class="navbar navbar-default navbar-fixed-top">
		<?php include("nav.php");?>
	</nav>
	<div class="container">
		<div class="content">
			<h2>Nuevo libro</h2>
			<hr />

			<?php
			if(isset($_POST['add'])){
				$autor		     = mysqli_real_escape_string($con,(strip_tags($_POST["Autor"],ENT_QUOTES)));//Escanpando caracteres 
				$titulo		     = mysqli_real_escape_string($con,(strip_tags($_POST["Titulo"],ENT_QUOTES)));//Escanpando caracteres 
				$palabrasClave	 = mysqli_real_escape_string($con,(strip_tags($_POST["PalabrasClave"],ENT_QUOTES)));//Escanpando caracteres 
				$materia	 = mysqli_real_escape_string($con,(strip_tags($_POST["Materia"],ENT_QUOTES)));//Escanpando caracteres 
				$isbn	     = mysqli_real_escape_string($con,(strip_tags($_POST["ISBN"],ENT_QUOTES)));//Escanpando caracteres 
				$editor		 = mysqli_real_escape_string($con,(strip_tags($_POST["Editor"],ENT_QUOTES)));//Escanpando caracteres 
				$precio		 = mysqli_real_escape_string($con,(strip_tags($_POST["Precio"],ENT_QUOTES)));//Escanpando caracteres 

				$insert = mysqli_query($con, "INSERT INTO libros(Autor, Titulo, PalabrasClave, ISBN, Materia, Editor, Precio) 
				VALUES('$autor','$titulo', '$palabrasClave', '$isbn', '$materia', '$editor', '$precio')") or die(mysqli_error());

				if($insert){
					try {
						if(isset($_FILES['InputImagen']['tmp_name'])) {
							$nik = mysqli_insert_id($con);
							$dir_subida = 'images/'; 
							$fichero_subido = $dir_subida . basename($_FILES['InputImagen']['name']); 
							if (move_uploaded_file($_FILES['InputImagen']['tmp_name'], $fichero_subido)) {
								$imagen = 'images/' . basename($_FILES['InputImagen']['name']);
								$updateImg = mysqli_query($con, "UPDATE libros SET Imagen='$imagen' WHERE id='$nik'") or die(mysqli_error());
							} else {
								echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error al subir la imagen</div>';
							}
						}
						header("Location: index.php");
					} catch (error) {
						echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error. No se pudo guardar los datos !</div>';
					}
				}else{
					echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>Error. No se pudo guardar los datos !</div>';
				}
				
			}
			?>

			<form class="form-horizontal" action="" enctype="multipart/form-data" method="post">
				<div class="form-group">
					<label class="col-sm-3 control-label">Imagen</label>
					<div class="col-sm-2">
						<input type="file" name="InputImagen" accept="image/*" id="previewImage">
						<img width="80" height="120" src="" id="imagePreview"/>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Titulo</label>
					<div class="col-sm-4">
						<input type="text" name="Titulo" class="form-control" placeholder="Titulo" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Autor</label>
					<div class="col-sm-4">
						<input type="text" name="Autor" class="form-control" placeholder="Autor" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">PalabrasClave</label>
					<div class="col-sm-4">
						<input type="text" name="PalabrasClave" class="form-control" placeholder="PalabrasClave" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Materia</label>
					<div class="col-sm-4">
						<input type="text" name="Materia" class="form-control"  placeholder="Materia" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">ISBN</label>
					<div class="col-sm-3">
						<input type="text" name="ISBN" class="form-control"  placeholder="ISBN" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Editor</label>
					<div class="col-sm-3">
						<input type="text" name="Editor" class="form-control" placeholder="Editor" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">Precio</label>
					<div class="col-sm-3">
						<input type="text" name="Precio" class="form-control" placeholder="Precio" required>
					</div>
				</div>
				<div class="form-group">
					<label class="col-sm-3 control-label">&nbsp;</label>
					<div class="col-sm-6">
						<input type="submit" name="add" class="btn btn-sm btn-primary" value="Guardar datos">
						<a href="index.php" class="btn btn-sm btn-danger">Cancelar</a>
					</div>
				</div>
			</form>
		</div>
	</div>

	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/bootstrap-datepicker.js"></script>
	<script>
        $('.date').datepicker({
            format: 'dd-mm-yyyy',
        });

        document.getElementById("previewImage").addEventListener("change", function() {
            const preview = document.getElementById("imagePreview");
            if (this.files && this.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                };
                reader.readAsDataURL(this.files[0]);
            }
        });
    </script>
</body>
</html>
