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
	<title>Libros swift</title>
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/style_nav.css" rel="stylesheet">
	<style>
		.content {
			margin-top: 80px;
		}
		.d-flex{
			display: flex;
			flex-direction: row;
			gap: 10px;
			align-items: center;
		}

	</style>
</head>
<body>
	<div class="container">
		<div class="content">
            <img src="https://assets.zyrosite.com/dJoEe25VXqu63jL4/logosintxt_250px-AE05Djx4MnHLLx5B.gif">
			<h2>Lista de libros</h2>
			<hr />
			<?php
			if(isset($_GET['res'])){
				switch ($_GET['res']) {
					case '1':
						echo '<div class="alert alert-success alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Datos cargados correctamente.</div>';
						break;
					case '2':
						echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Error al abrir el archivo csv.</div>';
						break;
					case '3':
						echo '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> Error al subir el archivo csv.</div>';
						break;
				}
			}
			?>
			<div class="d-flex">
				<form class="form-inline" method="get">
					<div class="d-flex">
						<input type="text" value="<?php echo isset($_GET["filter"]) ? $_GET["filter"] : ''?>" name="filter" palceholder="filtro" class="form-control"/>
						<button class="btn btn-outline-primary" onclick="form.submit()"><span class="glyphicon glyphicon-search" aria-hidden="true"></button>
						<div></div><div></div>
					</div>
				</form>
			</div>
			<br />
			<div class="table-responsive">
			<table class="table table-striped table-hover">
				<tr>
					<th>Imagen</th>
					<th>Título</th>
					<th>Autor</th>
					<th>Palabras Clave</th>
					<th>Materia</th>
					<th>ISBN</th>
					<th>Editor</th>
					<th>Precio</th>
					<th>Acciones</th>
				</tr>
				<?php
				$limit = 10; // Cantidad de resultados por página
				$page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Página actual

				if(isset($_GET["filter"])){
					$filter = mysqli_real_escape_string($con, $_GET["filter"]); 
					$sql = mysqli_query($con, "SELECT * FROM libros WHERE Titulo LIKE '%$filter%' OR Autor LIKE '%$filter%' OR Editor LIKE '%$filter%' OR Materia LIKE '%$filter%' OR PalabrasClave LIKE '%$filter%' ORDER BY id ASC LIMIT " . ($page - 1) * $limit . ", $limit");
				} else {
					$sql = mysqli_query($con, "SELECT * FROM libros ORDER BY id ASC LIMIT " . ($page - 1) * $limit . ", $limit");
				}
				
				if(mysqli_num_rows($sql) == 0){
					echo '<tr><td colspan="8">No hay datos.</td></tr>';
				}else{
					while($row = mysqli_fetch_assoc($sql)){
						echo '
						<tr>
							<td><img width="80" height="120" src="'.$row['Imagen'].'"/></td>
							<td>'.$row['Titulo'].'</td>
							<td>'.$row['Autor'].'</td>
                            <td>'.$row['PalabrasClave'].'</td>
                            <td>'.$row['Materia'].'</td>
							<td>'.$row['ISBN'].'</td>
                            <td>'.$row['Editor'].'</td>
							<td>'.$row['Precio'].'</td>
							</td>
						</tr>';
					}
				}
				?>
			</table>
			</div>

			<!-- Paginación -->
			<ul class="pagination">
				<?php
				if(isset($_GET["filter"])){
					$total_rows = mysqli_query($con, "SELECT COUNT(*) as total FROM libros WHERE Titulo LIKE '%$filter%' OR Autor LIKE '%$filter%' OR Editor LIKE '%$filter%' OR Materia LIKE '%$filter%' OR PalabrasClave LIKE '%$filter%'")->fetch_assoc()['total'];
				} else {
					$total_rows = mysqli_query($con, "SELECT COUNT(*) as total FROM libros")->fetch_assoc()['total'];
				}
				
				$total_pages = ceil($total_rows / $limit);

				if ($page > 1) {
					echo '<li><a href="index.php?page=' . ($page - 1) . '">Anterior</a></li>';
				}
				for ($i = 1; $i <= $total_pages; $i++) {
					echo '<li' . ($page == $i ? ' class="active"' : '') . '><a href="index.php?page=' . $i . '">' . $i . '</a></li>';
				}
				if ($page < $total_pages) {
					echo '<li><a href="index.php?page=' . ($page + 1) . '">Siguiente</a></li>';
				}
				?>
			</ul>
		</div>
	</div>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
	<script src="js/bootstrap.min.js"></script>
</body>
</html>
