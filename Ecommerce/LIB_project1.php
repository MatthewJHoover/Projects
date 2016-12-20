<?php
function createNavigation($pageID){ //creates a navigation system for the site using a specific page id
	if($pageID == 1){ //navigation output for index.php
		echo "<ul id='navlist'>";
		echo "<li><a href='#' id='current'>Home</a></li>";
		echo "<li><a href='cart.php'>Cart</a></li>";
		echo "<li><a href='admin.php'>Admin</a></li>";
		echo "</ul>";
	}
	elseif($pageID == 2){ //navigation output for cart.php
		echo "<ul id='navlist'>";
		echo "<li><a href='index.php'>Home</a></li>";
		echo "<li><a href='#' id='current'>Cart</a></li>";
		echo "<li><a href='admin.php'>Admin</a></li>";
		echo "</ul>";
	}
	else{ //navigation output for admin.php
		echo "<ul id='navlist'>";
		echo "<li><a href='index.php'>Home</a></li>";
		echo "<li><a href='cart.php'>Cart</a></li>";
		echo "<li><a href='#' id='current'>Admin</a></li>";
		echo "</ul>";
	}
}

function createBanner(){ //creates a banner for the site
	echo "<div id='banner'>";
	echo "<img src='img/logo.png' alt='logo' />";
	echo "<span class='banner'>The Pizza Shop</span>";
	echo "</div>";
}

function createControls($db){ //creates paging for the site using the database object
	$data = $db->getProductItems();
	$count = count($data);
	$total_pages = ceil($count / 5);
	for ($i=1; $i<=$total_pages; $i++){ 
		$page = 1;
		if(isset($_GET["page"])){
			$page = $_GET["page"];
		}
		if($i == $page){  //paging output for current page
			$maximum = 5;
			$max = $maximum * $page;
			$min = $max - 4;
			echo "<span class='aright' >Showing items $min - $max</span>";
			echo "&nbsp;&nbsp;[<b>$i</b>]&nbsp;&nbsp;";
		}else{ //paging output for all pages
			echo "&nbsp;&nbsp;<a href='index.php?page=".$i."'>".$i."</a>&nbsp;&nbsp;"; 
		}
	}; 
}

function fillForm($data){ //creates second form from select data using the data for a specific product from the database
			echo  "<form method='post' enctype='multipart/form-data'>";
			echo "<input type='hidden' name='item' value='{$data[0]['productId']}' />";
			echo "<input type='hidden' name='oldImage' value='{$data[0]['imageName']}' />";
			echo "<table>";
			echo "<tr><td colspan='2' class='areaHeading'>Edit Item:</td></tr>";
			echo "<tr><td>Name:</td><td><input type='text' name='name' size='40' value='{$data[0]['productName']}' /></td></tr>";
			echo "<tr><td>Description:</td><td><textarea name='description' rows='3' cols='60'>{$data[0]['description']}</textarea></td></tr>";
			echo "<tr><td>Price:</td><td><input type='text' name='price' size='40' value='{$data[0]['price']}' /></td></tr>";
			echo "<tr><td>Quantity on hand:</td><td><input type='text' name='quantity' size='40' value='{$data[0]['quantity']}' /></td></tr>";
			echo "<tr><td>Sale Price:</td><td><input type='text' name='salesPrice' size='40' value='{$data[0]['salePrice']}' /></td></tr>";
			echo "<tr><td>New Image:</td><td><input type='file' name='image' /></td></tr>";
			echo "<tr><td><strong>Your Username: </strong></td><td><input type='text' name='username' size='15' /></td></tr>";
			echo "<tr><td><strong>Your Password: </strong></td><td><input type='password' name='password' size='15' /></td></tr>";
			echo "</table>";
			echo "<br />";
			echo "<input type='reset' value='Reset Form' />&nbsp;";
			echo "<input type='submit' name='submit_changes' value='Submit Changes' />";
			echo "</form><br />";
}

function sanitize($input){ //sanitizes input using a specific input value
   $input = trim($input);
   $input = stripslashes($input);
   $input = strip_tags($input);
   $input = htmlspecialchars($input);
   return $input;
}

function validateDecimal($input){ //validates input as a decimal using a specific input value
	$reg = "/^[0-9]+(\.[0-9]{1,2})?$/";
	return preg_match($reg,$input);
}

function validateInteger($input) { //validates input as an integer using a specific input value
	$reg = "/(^-?\d\d*$)/";
	return preg_match($reg,$input);
}

function validateAlphabeticSpace($input) { //validates input as alphabetic with spaces using a specific input value
	$reg = "/^[A-Za-z ]+$/";
	return preg_match($reg,$input);
}

function validateAlphabeticPunct($input) { //validates input as alphabetic with punctuation using a specific input value
	$reg = "/^[A-Za-z _.,!?\"']+$/";
	return( preg_match($reg,$input));
}

function validateAlphabeticPunctFile($input) { //validates input as alphabetic with specific punctuation using a specific input value
	$reg = "/^[A-Za-z _.\"']+$/";
	return( preg_match($reg,$input));
}

function uploadFile(){ //uploads input file to the server
	$target_dir = 'img/';
	$target_file = $target_dir . basename($_FILES['image']['name']);
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
	$check = getimagesize($_FILES['image']['tmp_name']);
	if($check !== false) { //output when file is an image 
		echo "<h2 class='heading'>File is an image - " . $check['mime'] . ".</h2>";
		$uploadOk = 1;
		if (file_exists($target_file)) { //output when file already exisits 
			echo "<p><span class='error'>Sorry, file already exists.</span></p>";
			$uploadOk = 0;
		}
		if ($_FILES['image']['size'] > 500000) { //output when file is too large
			echo "<p><span class='error'>Sorry, your file is too large.</span></p>";
			$uploadOk = 0;
		}
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) { //output when file is not of given types
			echo "<p><span class='error'>Sorry, only JPG, JPEG, PNG & GIF files are allowed.</span></p>";
			$uploadOk = 0;
		}
		if ($uploadOk == 0) { //output when there is an error
			echo "<p><span class='error'>Sorry, your file was not uploaded.</span></p>";
		}else {
			if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) { //output when file is uploaded
				echo "<h2 class='heading'>The file ". basename( $_FILES["image"]["name"]). " has been uploaded.</h2>";
			} else { //output when there is an error uploading file
				echo "<p><span class='error'>Sorry, there was an error uploading your file.</span></p>";
			}
		} 
	}else { //output when file is not an image
		echo "<p><span class='error'>File is not an image.</span></p>";
		$uploadOk = 0;
	}
}

function editForm($db){ //creates the second form and updates the database with the input data using the database object
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_POST['edit'])){ //output when first form is submitted
			$id = $_POST['pickOne'];
			$data = $db->getProduct($id);
			fillForm($data);
		}
		if(isset($_POST['submit_changes'])){ //output when second form is submitted
			$id = $_POST['item'];
			$data = $db->getProduct($id);
			fillForm($data);
			if($_POST['username'] == 'mjh4402' && $_POST['password'] == 'student'){ //output when user is logged in
				$_SESSION['loggedIn'] = 'true';
				if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == 'true'){ //output when user is logged in
					$data = $db->countSales();
					$count = $data[0]['count'];
					$name = sanitize($_POST['name']);
					$description = sanitize($_POST['description']);
					$price = sanitize($_POST['price']);
					$quantity = sanitize($_POST['quantity']);
					$image = sanitize($_FILES['image']['name']);
					$sale = sanitize($_POST['salesPrice']);
					if($sale == 0){ //output when sale price is 0
						if(validateAlphabeticSpace($name) && validateAlphabeticPunct($description) && validateDecimal($price) && validateInteger($quantity) && validateAlphabeticPunctFile($image) && validateDecimal($sale) == true){ //output when input validates
							$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING); //sanitize post array
							uploadFile(); //upload file 
							$db->updateProduct($_POST, $image); //update database
							echo "<h2 class='heading'>Edited the product $name.</h2>";
							unset($_SESSION['loggedIn']);
							session_destroy();
						}else{ //output when input invalidates
							echo "<p><span class='error'>Incorrect product! Name: must be alphabetic (can contain spaces) - Description: must be alphabetic (can contain punctuation) - Price: must be a decimal (4, 2) - Quantity: must be an integer - Image Name: must be alphabetic (can contain punctuation) - Sale Price: must be a decimal (4, 2)</span></p>";
						}
					}else{
						if(3 <= $count && $count <= 4){ //output when there are 3-4 sale items in the database
							if(validateAlphabeticSpace($name) && validateAlphabeticPunct($description) && validateDecimal($price) && validateInteger($quantity) && validateAlphabeticPunctFile($image) && validateDecimal($sale) == true){ //output when input validates
								$_POST  = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING); //sanitize post array
								uploadFile(); //upload file 
								$db->updateProduct($_POST); //update database
								echo "<h2 class='heading'>Edited the product $name.</h2>";
								unset($_SESSION['loggedIn']);
								session_destroy();
							}else{ //output when input invalidates
								echo "<p><span class='error'>Incorrect product! Name: must be alphabetic (can contain spaces) - Description: must be alphabetic (can contain punctuation) - Price: must be a decimal (4, 2) - Quantity: must be an integer - Image Name: must be alphabetic (can contain punctuation) - Sale Price: must be a decimal (4, 2)</span></p>";
							}
						}else{ //output when there are less than 3 or more than 4 sale items in the database
							echo "<p><span class='error'>Could not edit product $name - there are $count items on sale (only 3-5 items can be on sale)</span></p>";
						}
					}
				}else{ //output when user is not logged in
					echo "<p><span class='error'>Incorrect login!</span></p>";
				}
			}else{ //output when user is not logged in
				echo "<p><span class='error'>Incorrect login!</span></p>";
			}
		}
	}
}

function addForm($db){ //inserts the input data into the database using the database object
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
		if(isset($_POST['submit_item'])){ //output when third form is submitted
			if($_POST['username'] == 'mjh4402' && $_POST['password'] == 'student'){ //output when user is logged in
					$_SESSION['loggedIn'] = 'true';
					if(isset($_SESSION['loggedIn']) && $_SESSION['loggedIn'] == 'true'){ //output when user is logged in
							$data = $db->countSales();
							$count = $data[0]['count'];
							$name = sanitize($_POST['name']);
							$description = sanitize($_POST['description']);
							$price = sanitize($_POST['price']);
							$quantity = sanitize($_POST['quantity']);
							$image = sanitize($_FILES['image']['name']);
							$sale = sanitize($_POST['salesPrice']);
							if($sale == 0){ //output when sale price is 0
								if(validateAlphabeticSpace($name) && validateAlphabeticPunct($description) && validateDecimal($price) && validateInteger($quantity) && validateAlphabeticPunctFile($image) && validateDecimal($sale) == true){ //output when input validates
									uploadFile(); //upload file
									$db->insertProduct($name,$description,$price,$quantity,$image,$sale); //insert product into database
									echo "<h2 class='heading'>Added the product $name</h2>";
									unset($_SESSION['loggedIn']);
									session_destroy(); //destroy session
								}else{ //output when input invalidates
									echo "<p><span class='error'>Incorrect product! Name: must be alphabetic (can contain spaces) - Description: must be alphabetic (can contain punctuation) - Price: must be a decimal (4, 2) - Quantity: must be an integer - Image Name: must be alphabetic (can contain punctuation) - Sale Price: must be a decimal (4, 2)</span></p>";
								}
							}else{
								if(3 <= $count && $count <= 4){ //output when there are 3-4 sale items in the database
									if(validateAlphabeticSpace($name) && validateAlphabeticPunct($description) && validateDecimal($price) && validateInteger($quantity) && validateAlphabeticPunctFile($image) && validateDecimal($sale) == true){ //output when input validates
										uploadFile(); //upload file
										$db->insertProduct($name,$description,$price,$quantity,$image,$sale); //insert product into database
										echo "<h2 class='heading'>Added the product $name</h2>";
										unset($_SESSION['loggedIn']);
										session_destroy(); //destroy session
									}else{ //output when input invalidates
										echo "<p><span class='error'>Incorrect product! Name: must be alphabetic (can contain spaces) - Description: must be alphabetic (can contain punctuation) - Price: must be a decimal (4, 2) - Quantity: must be an integer - Image Name: must be alphabetic (can contain punctuation) - Sale Price: must be a decimal (4, 2)</span></p>";
									}
								}else{ //output when there are less than 3 or more than 4 sale items in the database
									echo "<p><span class='error'>Could not add product $name - there are $count items on sale (only 3-5 items can be on sale)</span></p>";
								}
							}
					}else{ //output when user is not logged in
						echo "<p><span class='error'>Incorrect login!</span></p>";
					}
			}else{ //output when user is not logged in
				echo "<p><span class='error'>lncorrect login!</span></p>";
			}
		}
	}
}

function createFooter(){ //creates a footer at the bottom of the site
	echo "<div id='footer'>";
	echo "<div id='footerDiv'>";
    echo "<p><strong>User information:</strong></p>";
	echo "<ul><li><script>document.write('Your browser is ' + browserName + ' (' + fullVersion + ') and your IP address is <em>' + myip + '</em>');</script></li>";
	echo "<li><script>document.write('Your screen resolution is ' + screen.width + ' x ' + screen.height);</script></li>";
	echo "</ul>";
	echo "</div>";
	echo "</div>";
}
?>