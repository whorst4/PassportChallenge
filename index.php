<?php
	session_save_path("/tmp/");
	session_start();

	include_once 'database.inc.php';

	$query = "SELECT id, title, min, max, children FROM parentNode ORDER BY id ASC";
	$_SESSION['parent_rows'] = mysqli_query($conn, $query);
	$parentRows = $_SESSION['parent_rows'];

	$query = "SELECT id, parentNodeId, val FROM childNode ORDER BY parentNodeId ASC";
	$_SESSION['child_rows'] = mysqli_query($conn, $query);
	$childRows = $_SESSION['child_rows'];
	
?>
<!doctype html>
<html lang="en">
	
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	</head>

	<body>

		<nav class="navbar navbar-expand-lg navbar-dark bg-dark justify-content-center">
			<ul class="navbar-nav">
      	<li class="nav-item active">
          <span class="navbar-brand">Passport Web Programming Challenge</span>
        </li>
      </ul>
    </nav>
     
    <nav class="navbar navbar-dark bg-primary">
      <span class="navbar-brand">Root</span>
    	<button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#addFactoryModal">Add Factory</button>
    </nav>

    <br>
		<div class="container">
			<?php 
				$rowId = 0;
				while ($row = mysqli_fetch_assoc($parentRows)) { 
					$rowId = $row['id'];
					?>
  			<div class="row">
  				<div class="col text-left">
  					<span> <b> <?php echo $row['title']; ?> </b> </span> <br>

  					<?php 
  					while ($cRow = mysqli_fetch_assoc($childRows)) { 
		  				if (intval($cRow['parentNodeId']) === intval($rowId)) { ?>
		  					<span>&nbsp;&nbsp;&nbsp;- 
									<?php echo $cRow['val']; ?>
								</span><br>
		  				<?php }
		  			}
		  			mysqli_data_seek($childRows, 0); 
		  			?>

  				</div>
  				<div class="col text-left">
  					<span class="badge badge-secondary"> <?php echo "{$row['min']} : {$row['max']}"; ?> </span>
  				</div>
  				<div class="col text-left">
  					<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#UpdateFactoryModal">Update Factory</button>
  				</div>
  				<div class="col text-left">
  					<form id="deleteFactory" action="deleteFactory.inc.php" method="post">
  						<input type="hidden" name="nodeId" value="<?php echo $row['id'] ?>" id="nodeId" readOnly required>
  						<button type="submit" name="submit" class="btn btn-primary" id="submit">Delete</button>
  					</form>
  				</div>
	  		</div>
	  		<div class="modal fade" id="UpdateFactoryModal" tabindex="-1" role="dialog" aria-labelledby="UpdateFactoryModal" aria-hidden="true">
				  <div class="modal-dialog modal-dialog-centered" role="document">
				  	<form id="updateFactory" action="updateFactory.inc.php" method="post">
					    <div class="modal-content">
					      <div class="modal-header">
					        <h5 class="modal-title text-center" id="factoryModalTitle">Edit Factory</h5>
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
					          <span aria-hidden="true">&times;</span>
					        </button>
					      </div>
					      <div class="modal-body">
					      	<span class="float-left">Title</span>
					        <input class="float-right" type="text" name="title" id="title" form="updateFactory" value="<?php echo $row['title'] ?>" required="true" required>
					        <br><br><span class="float-left">Number of Children</span>
					        <input class="float-right" type="number" name="numChildren" id="numChildren" form="updateFactory" value="<?php echo $row['children'] ?>" min="1" required="true" required>
					        <br><br><span class="float-left">Child Minimum</span>
					        <input class="float-right" type="number" name="childMin" id="childMin" form="updateFactory" value="<?php echo $row['min'] ?>" min="0" required="true" required>
					        <br><br><span class="float-left">Child Maximum</span>
					        <input class="float-right" type="number" name="childMax" id="childMax" form="updateFactory" value="<?php echo $row['max'] ?>" min="1" required="true" required>
					        <input type="hidden" name="nodeId" value="<?php echo $row['id'] ?>" id="nodeId" readOnly required>
					      </div>
					      <div class="modal-footer">
					      	<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
									<button type="submit" name="submit" class="btn btn-primary" id="submit">Save and Regenerate Factory</button>
					      </div>
					    </div>
					  </form>
				  </div>
				</div>
				<br>
	  	<?php 
	  	} 
	  	mysqli_data_seek($childRows, 0);
	  	?>
  	</div>

  	<div class="modal fade" id="addFactoryModal" tabindex="-1" role="dialog" aria-labelledby="AddFactoryModal" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
		  	<form id="addFactory" action="addFactory.inc.php" method="post">
			    <div class="modal-content">
			      <div class="modal-header">
			        <h5 class="modal-title text-center" id="factoryModalTitle">Add Factory</h5>
			        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
			          <span aria-hidden="true">&times;</span>
			        </button>
			      </div>
			      <div class="modal-body">
			      	<span class="float-left">Title</span>
			        <input class="float-right" type="text" name="title" id="title" form="addFactory" required>
			        <br><br><span class="float-left">Number of Children</span>
			        <input class="float-right" type="number" name="numChildren" id="numChildren" form="addFactory" min="1" required>
			        <br><br><span class="float-left">Child Minimum</span>
			        <input class="float-right" type="number" name="childMin" id="childMin" form="addFactory" min="0" onkeyup="minMaxValidation()" required>
			        <br><br><span class="float-left">Child Maximum</span>
			        <input class="float-right" type="number" name="childMax" id="childMax" form="addFactory" min="1" onkeyup="minMaxValidation()" required>
			      </div>
			      <div class="modal-footer">
			      	<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
							<button type="submit" name="submit" class="btn btn-primary" id="submit">Add Factory</button>
			      </div>
			    </div>
			  </form>
		  </div>
		</div>

		<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	</body>

</html>

