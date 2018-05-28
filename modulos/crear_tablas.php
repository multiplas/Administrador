<?php 
function set_connection_vars(){
	$conn = establecer_conexion();

	$table = 'administrador_base';
	
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error . ". Por favor, crea la base de datos adminisrador.<br>");
	} 
	if ($result = $conn->query("SHOW TABLES LIKE '".$table."'")) 
		if($result->num_rows != 1)
			create_admin_table();	
}

function create_admin_table(){
	$conn = establecer_conexion();
	
	// sql to create table
	$sql = "CREATE TABLE IF NOT EXISTS administrador_base (
	id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY, 
	id_tarea INT(10) NOT NULL,
	n_horas DOUBLE NOT NULL DEFAULT 0,	
	nombre_web VARCHAR(50) NOT NULL,
	direccion_web VARCHAR(100) NOT NULL,
	breve_descripcion VARCHAR (500) DEFAULT NULL,
	fecha_actualizacion TIMESTAMP,
	fecha_creacion TIMESTAMP,
	estado INT(1) NOT NULL DEFAULT '0'
	)";
	
	if ($conn->query($sql) === TRUE) {//Create table
		echo "Table MyGuests created successfully";
	} else {
		echo "Error creating table: " . $conn->error;
	}	
	
	$conn->close();
}

function get_current_info(){
	$conn = establecer_conexion();
	
	$sql = 'SELECT * FROM administrador_base';
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
    // output data of each row
		while($row = $result->fetch_assoc()) {
			$dbInfo[] = $row;
		}
	} else {
		echo "0 results";
	}
	
	$conn->close();			
	return $dbInfo;
}

function get_current_tar($id_tarea){
	$conn = establecer_conexion();
	
	$sql = "SELECT * FROM administrador_base WHERE id_tarea = $id_tarea";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
    // output data of each row
		while($row = $result->fetch_assoc()) {
			$dbInfo[] = $row;
		}
	} else {
		echo "0 results";
	}
	
	$conn->close();			
	return $dbInfo;

}

function insert_into_db($id_tarea, $n_horas, $nombre_web, $direccion_web, $breve_descripcion, $estado){
	$conn = establecer_conexion();

	$sql_verificacion = "SELECT * FROM administrador_base WHERE id_tarea = $id_tarea";

	$result = $conn->query($sql_verificacion);
	
	if ($result->num_rows == 0) {
		$sql = "INSERT INTO administrador_base (id_tarea, n_horas, nombre_web, direccion_web, breve_descripcion, fecha_actualizacion, fecha_creacion, estado) VALUES ($id_tarea, $n_horas, '$nombre_web', '$direccion_web', '$breve_descripcion', NOW(), NOW(), '$estado');";
		if ($conn->query($sql) === TRUE) {//Create table
			?>
			<div class="alert alert-success">
				<strong>Success!</strong>Insertado.
			</div>
			<?php
		} else {
			?>
			<div class="alert alert-danger">
				<strong>No insertado!</strong>
			</div>
			<?php
			echo "Error insertando contenido: " . $conn->error;
			echo "<br> La consulta: " . $sql;
		}	
	}
}

function update_database($id_tarea, $n_horas, $estado){
	$conn = establecer_conexion();

	$sql = "UPDATE administrador_base SET n_horas = $n_horas, estado = '$estado', fecha_actualizacion = NOW() WHERE id_tarea = $id_tarea;";

	if ($conn->query($sql) === TRUE) {//Create table
		?>
		<div class="alert alert-success">
			<strong>Success!</strong>Actualizado.
		</div>
		<?php
	} else {
		?>
		<div class="alert alert-danger">
			<strong>No Actualizado!</strong>
	  	</div>
		<?php
		echo "Error Actualizado contenido: " . $conn->error;
		echo "<br> La consulta: " . $sql;
	}	
	
}

function delete_row_fromdb($id_tarea){
	$conn = establecer_conexion();

	$sql = "DELETE FROM administrador_base WHERE id_tarea = $id_tarea";
	if ($conn->query($sql) === TRUE) {//Create table
		?>
		<div class="alert alert-success">
			<strong>Success!</strong>Eliminado.
		</div>
		<?php
	} else {
		?>
		<div class="alert alert-danger">
			<strong>No eliminado!</strong>
	  	</div>
		<?php
		echo "Error eliminando contenido: " . $conn->error;
		echo "<br> La consulta: " . $sql;
	}	
}
	
	set_connection_vars();
	
	$currentDBInfo = get_current_info();

	$entra = false;
	
	if($_POST['accion'] == 'actualizar'){
		update_database($_POST['id_tarea'], $_POST['n_horas'], $_POST['estado']);
		$entra = true;
	}
	elseif($_POST['accion'] == 'insertar'){
		insert_into_db($_POST['id_tarea'], $_POST['n_horas'], $_POST['nombre_web'], $_POST['direccion_web'], $_POST['breve_descripcion'], $_POST['estado']);
		$entra = true;
	}
	elseif($_POST['accion'] == 'eliminar'){
		delete_row_fromdb($_POST['id_tarea']);
		$entra = true;
	}
	elseif($_POST['accion'] == 'traer_tarea'){
		echo "<input type='hidden' id='accion_helper' value='traer_tarea'>";
		$tarea = get_current_tar($_POST['id_tarea']);
	}
	if($entra)
		header('Location: index.php');
	?>
	<div class="row">
		<h2 class="col-sm-12 text-center" style="padding-bottom:20px;">Información actual</h2>
	</div>
	<?php 
	if(!empty($currentDBInfo[0])): ?>
		<div class="row">			
			<div class="col-sm-2"><strong>ID Tarea</strong></div>
			<div class="col-sm-2"><strong>Minutos</strong></div>
			<div class="col-sm-2"><strong>Nombre Web</strong></div>
			<div class="col-sm-2"><strong>Dirección Web</strong></div>
			<div class="col-sm-2"><strong>Descripción</strong></div>
			<div class="col-sm-2"><strong>Fecha Actualizacion</strong></div>			
		</div>
		<hr>
	<?php endif; 
	$i = 0;
	foreach($currentDBInfo as $info){
		$total_s = $info['n_horas'] * 60;
		$total_hR = ($info['n_horas'] + ($info['n_horas']*25/100))*60;

		?>
		<div class="row" <?php if($info['estado'] == 0) echo "style='color:red;'"; else echo "style='color:green'"; ?>>
			<div class="col-sm-2"><?=$info['id_tarea']?></div>
			<div class="col-sm-2"><?=$info['n_horas']?></div>
			<div class="col-sm-2"><?=$info['nombre_web']?></div>
			<div class="col-sm-2"><?=$info['direccion_web']?></div>
			<div class="col-sm-2"><?=$info['breve_descripcion']?></div>
			<div class="col-sm-2"><?=$info['fecha_actualizacion']?></div>
			<div class="col-sm-12 text-right">
				<small class="activa_info" id="activa_<?=$i?>">Ver más</small>
			</div>
		</div>
		<div class="row" id="info_activa_<?=$i?>" style="display:none">
			<div class="col-sm-3">Total Segundos: <?=$total_s?></div>
			<div class="col-sm-3">Total Seg Rec. (+25%): <?=$total_hR?></div>
			<div class="col-sm-3">Fecha Cr: <?=$info['fecha_creacion']?></div>
		</div>
		<hr>
		<?php
		$i++;
	}
	?>

	<div id="updated-box" class="alert alert-success" style="display:none">
		<strong>La BD se está actualizando en vivo. Haga click en Actualizar si desea ver los cambios o seguir con otra tarea.</strong>.
	</div>	

	<form id="formulario_tablas" class="col-xs-12" action='' method='post'>
		<input type="hidden" id="accion" name="accion" value="">
		<div class="form-group row">
			<div class="col-sm-2">
				<label for="id_tarea">ID de la tarea</label>
				<input name="id_tarea" id="id_tarea" class="form-control" type="text" value="<?php if(!empty($tarea[0]))echo $tarea[0]['id_tarea']; ?>" required>		
			</div>
			<div class="col-sm-2">
				<label for="n_horas">Tiempo dedicado</label>
				<input name="n_horas" id="n_horas" class="form-control" type="number" value="<?php if(!empty($tarea[0]))echo $tarea[0]['n_horas'];else echo 0; ?>">
				<small> (minutos)</small>		
			</div>
			<div class="col-sm-3">
				<label for="nombre_web">Nombre Web</label>
				<input name="nombre_web" class="form-control" type="text" value="<?php if(!empty($tarea[0]))echo $tarea[0]['nombre_web']; ?>">		
			</div>
			<div class="col-sm-5">
				<label for="direccion_web">Dirección Web</label>
				<input name="direccion_web" class="form-control" type="url" value="<?php if(!empty($tarea[0]))echo $tarea[0]['direccion_web']; ?>">		
			</div>
		</div>
		<div class="form-group row">
			<div class="col-sm-12">
				<label for="breve_descripcion">Breve descripción</label>
				<textarea name="breve_descripcion"  class="form-control" id="breve_descripcion" rows="2" ><?php if(!empty($tarea[0]))echo $tarea[0]['breve_descripcion']; ?></textarea>
			</div>
		</div>
		<div class="form-group row">
			<div class="col-sm-12">
				<label for="estado">Estado de la tarea:</label>
				<select name="estado" class="form-control">
					<option value="0" <?php if($tarea[0]['estado'] == 0)echo 'selected'; ?>>En proceso</option>
					<option value="1" <?php if($tarea[0]['estado'] == 1)echo 'selected'; ?>>Terminada</option>
				</select>
			</div>	
		</div>
		<div class="form-group row">
			<input type="button" style="cursor:pointer;" class="btn btn-primary col-sm-3" id="insertar" value="Insertar">
			<div class="col-sm-1"></div>
			<input type='button' style="cursor:pointer;" class="btn btn-primary col-sm-3" id="actualizar" value='Actualizar'/> 
			<div class="col-sm-1"></div>
			<input type="button" style="cursor:pointer;" class="btn btn-primary col-sm-3" id="traer_tarea" value="Traer Tarea">		
		</div>
		<div class="form-group row">
			<input type="button" style="cursor:pointer;background:red;border:red;" class="btn btn-primary col-sm-5" id="eliminar" value="Eliminar">
			<div class="col-sm-1"></div>
			<input type="button" style="cursor:pointer;background:green;border:green;" class="btn btn-primary col-sm-5" id="btn-comenzar" value="Comenzar">			
		</div>		
	</form>
