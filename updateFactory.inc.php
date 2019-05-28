<?php

	//session_save_path("/tmp/");
	//session_start();

	if(isset($_POST['submit'])) {
		include_once 'database.inc.php';
    $NodeTitle = $_POST['title'];
		$NodeNumChildren = $_POST['numChildren'];
		$NodeChildMax = $_POST['childMax'];
		$NodeChildMin = $_POST['childMin'];
		$NodeId = $_POST['nodeId'];

		if (!empty($NodeTitle) && !empty($NodeNumChildren) && !empty($NodeChildMax) && !empty($NodeChildMax)) {

			if (intval($NodeChildMax) <= intval($NodeChildMin)) {
				header("Location: ../index.php?index=Error:MinGreaterThanMax");
				exit();
			}

			$statement = $conn->prepare("DELETE FROM childNode WHERE parentNodeId=?");
			$statement->bind_param("i", $pId);
			$pId = intval($NodeId);
			if ($statement->execute()) {
			
				$statement = $conn->prepare("UPDATE parentNode SET title=?, min=?, max=?, children=? WHERE id=?");
				$statement->bind_param("siiii", $title, $min, $max, $children, $parentId);
			
				$parentId = intval($NodeId);
				$title = testInput($NodeTitle);
				$min = testInput($NodeChildMin);
				$max = testInput($NodeChildMax);
				$children = testInput($NodeNumChildren);

				if ($statement->execute()) {
					mysqli_stmt_close($statement);
					$parentNodeId = $parentId;
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
					header("Location: ../index.php?index=parentDeleteUnsuccessful");
					exit();
				}
			}
			else {
				mysqli_stmt_close($statement);
				header("Location: ../index.php?index=childDeleteUnsuccessful");
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
		echo("NODE VALUES ARE MISSING");
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