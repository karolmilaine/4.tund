<?php
	// functions.php
	//var_dump($GLOBALS);
	
	// see fail, peab olema k�igil lehtedel kus 
	// tahan kasutada SESSION muutujat
	session_start();
	
	//***************
	//**** SIGNUP ***
	//***************
	
	function signUp ($email, $password) {
		
		$database = "if16_romil";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		$stmt = $mysqli->prepare("INSERT INTO user_sample (email, password) VALUES (?, ?)");
	
		echo $mysqli->error;
		
		$stmt->bind_param("ss", $email, $password);
		
		if($stmt->execute()) {
			echo "salvestamine �nnestus";
		} else {
		 	echo "ERROR ".$stmt->error;
		}
		
		$stmt->close();
		$mysqli->close();
		
	}
	
	
	function login ($email, $password) {
		
		$error = "";
		
		$database = "if16_romil";
		$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $database);
		$stmt = $mysqli->prepare("
		SELECT id, email, password, created 
		FROM user_sample
		WHERE email = ?");
	
		echo $mysqli->error;
		
		//asendan k�sim�rgi
		$stmt->bind_param("s", $email);
		
		//m��ran v��rtused muutujatesse
		$stmt->bind_result($id, $emailFromDb, $passwordFromDb, $created);
		$stmt->execute();
		
		//andmed tulid andmebaasist v�i mitte
		// on t�ene kui on v�hemalt �ks vaste
		if($stmt->fetch()){
			
			//oli sellise meiliga kasutaja
			//password millega kasutaja tahab sisse logida
			$hash = hash("sha512", $password);
			if ($hash == $passwordFromDb) {
				
				echo "Kasutaja logis sisse ".$id;
				
				//m��ran sessiooni muutujad, millele saan ligi
				// teistelt lehtedelt
				$_SESSION["userId"] = $id;
				$_SESSION["userEmail"] = $emailFromDb;
				
				header("Location: data.php");
				
			}else {
				$error = "vale parool";
			}
			
			
		} else {
			
			// ei leidnud kasutajat selle meiliga
			$error = "ei ole sellist emaili";
		}
		
		return $error;
		
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	/*function sum($x, $y) {
		
		return $x + $y;
		
	}
	
	
	function hello($firsname, $lastname) {
		
		return "Tere tulemast ".$firsname." ".$lastname."!";
		
	}
	
	echo sum(5123123,123123123);
	echo "<br>";
	echo hello("Romil", "Robtsenkov");
	echo "<br>";
	echo hello("Juku", "Juurikas");
	*/
?>