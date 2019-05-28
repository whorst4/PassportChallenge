<?php

	//session_save_path("/tmp/");
	//session_start();

	if(isset($_POST['submit'])) {
		include_once 'database.inc.php';
		$NodeId = $_POST['nodeId'];

		if (!empty($NodeId)) {
			
			$statement = $conn->prepare("DELETE FROM childNode WHERE parentNodeId=?");
			$statement->bind_param("i", $pId);
			$pId = intval($NodeId);
			
			if($statement->execute()) {
				$statement = $conn->prepare("DELETE FROM parentNode WHERE id=?");
				$statement->bind_param("i", $parentId);
				$parentId = intval($NodeId);
				if ($statement->execute()) {
					mysqli_stmt_close($statement);
					header("Location: ../index.php?index=successful");
					exit();
				}
				else {
					mysqli_stmt_close($statement);
					header("Location: ../index.php?index=parentDeleteUnsuccessful");
					exit();
				}
			}
			else {
				mysqli_stmt_close($statement);
				header("Location: ../index.php?index=childDeleteUnsuccessful");
				exit();
			}
			
		}
		else {
			echo("SOMETHING WENT WRONG");
			header("Location: ../index.php?index=IdMissing");
			exit();
		}
	}
	else {
		echo("NODE VALUES ARE MISSING");
		header("Location: ../index.php?index=unsuccessful");
		exit();
	}

?>