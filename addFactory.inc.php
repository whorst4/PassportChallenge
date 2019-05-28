<?php

	session_save_path("/tmp/");
	session_start();

	if(isset($_POST['submit'])) {
		include_once 'database.inc.php';
    $NodeTitle = $_POST['title'];
		$NodeNumChildren = $_POST['numChildren'];
		$NodeChildMax = $_POST['childMax'];
		$NodeChildMin = $_POST['childMin'];

		if (!empty($NodeTitle) && !empty($NodeNumChildren) && !empty($NodeChildMax) && !empty($NodeChildMax)) {

			if (intval($NodeChildMax) <= intval($NodeChildMin)) {
				header("Location: ../index.php?index=Error:MinGreaterThanMax");
				exit();
			}
			$statement = $conn->prepare("INSERT INTO parentNode (title, min, max, children) VALUES (?, ?, ?, ?)");
			$statement->bind_param("siii", $title, $min, $max, $children);
		
			$title = testInput($NodeTitle);
			$min = testInput($NodeChildMin);
			$max = testInput($NodeChildMax);
			$children = testInput($NodeNumChildren);

			if ($statement->execute()) {
				mysqli_stmt_close($statement);
				$parentNodeId = mysqli_insert_id($conn);
				//echo "New record created successfully. Last inserted ID is: " . $parentNodeId;

				$statement = $conn->prepare("INSERT INTO childNode (parentNodeId, val) VALUES (?, ?)");
				$statement->bind_param("ii", $parentNodeId, $val);

				for ($i = 0; $i < $children; $i++) {
					$val = mt_rand($min, $max);
					if (!$statement->execute()) {
						mysqli_stmt_close($statement);
						echo("SOMETHING WENT WRONG");
						header("Location: ../index.php?index=childAddUnsuccessful");
						exit();
					}
				}
			}
			else {
				mysqli_stmt_close($statement);
				header("Location: ../index.php?index=parentAddUnsuccessful");
				exit();
			}

			mysqli_stmt_close($statement);
			header("Location: ../index.php?index=successful");
			exit();
			
		}
		else {
			echo("SOMETHING WENT WRONG");
			header("Location: ../index.php?index=FieldsMissing");
			exit();
		}
	}
	else {
		echo("SOMETHING WENT WRONG");
		header("Location: ../index.php?index=unsuccessful");
		exit();
	}

	function testInput($data) {
	  $data = trim($data);
	  $data = stripslashes($data);
	  $data = htmlspecialchars($data);
	  return $data;
	}
?>