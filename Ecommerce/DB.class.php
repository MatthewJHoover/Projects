<?php
class DB {
	private $connection;
	
	function __construct() { //database constructor
		$host = "localhost";
		$user = "mjh4402";
		$pass = "fr1end";
		$db = "mjh4402";
		$this->connection = new mysqli($host,$user,$pass,$db);
		if ($this->connection->connect_error) { //output when database connection fails
			echo "Connection failed: ".mysqli_connect_error();
			die();
		}
	}
	
	function getProducts(){ //retrieves all products not on sale limiting 5 per page and returns the products in an array
		try{
			$per_page=5;
			if (isset($_GET["page"])){ //output when page is set
				$page = $_GET["page"];
			}
			else{ //output when page is not set
				$page=1;
			}
			$start_from = ($page-1) * $per_page;
			$data = array();
			$stmt = $this->connection->prepare("select * from products where SalePrice = 0 LIMIT $start_from, $per_page"); 
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($productId, $productName, $description, $price, $quantity, $imageName, $salePrice);
			if ($stmt->num_rows > 0) { //output when there are products not on sale
				while($row = $stmt->fetch()){ //fetch products
					$data[] = array(
								'productId'=>$productId,
								'productName'=>$productName,
								'description'=>$description,
								'price'=>$price,
								'quantity'=>$quantity,
								'imageName'=>$imageName,
								'salePrice'=>$salePrice,
					);
				}
			}
			return $data;
		}catch(Exception $e){
			echo $e->getMessage();
			die();
		}
	}
	
	function getProductItems(){ //retrieves all products not on sale and returns the products in an array
		try{
			$data = array();
			$stmt = $this->connection->prepare("select * from products where SalePrice = 0");
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($productId, $productName, $description, $price, $quantity, $imageName, $salePrice);
			if ($stmt->num_rows > 0) { //output when there are products not on sale
				while($row = $stmt->fetch()){ //fetch products
					$data[] = array(
								'productId'=>$productId,
								'productName'=>$productName,
								'description'=>$description,
								'price'=>$price,
								'quantity'=>$quantity,
								'imageName'=>$imageName,
								'salePrice'=>$salePrice,
					);
				}
			}
			return $data;
		}catch(Exception $e){
			echo $e->getMessage();
			die();
		}
	}
	
	function getSaleProducts(){ //retrieves all products on sale and returns an array containing the products
		try{
			$data = array();
			$stmt = $this->connection->prepare("select * from products where not SalePrice = 0");
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($productId, $productName, $description, $price, $quantity, $imageName, $salePrice);
			if ($stmt->num_rows > 0) { //output when there are products on sale
				while($row = $stmt->fetch()){ //fetch products
					$data[] = array(
								'productId'=>$productId,
								'productName'=>$productName,
								'description'=>$description,
								'price'=>$price,
								'quantity'=>$quantity,
								'imageName'=>$imageName,
								'salePrice'=>$salePrice,
					);
				}
			}
			return $data;
		}catch(Exception $e){
			echo $e->getMessage();
			die();
		}
	}
	
	function getCatalog(){ //retrieves all products not on sale and fills the catalog section
		$data = $this->getProducts();
		if(count($data) > 0){ //output when there are products not on sale
			foreach($data as $row){	//output for each product		
				$price = "$".$row['price'];
				$page = 1;
				if(isset($_GET['page'])){ //output when page is set
					$page = $_GET['page'];
				}
				echo "<div class='one_item'><h3>{$row['productName']}</h3>";
				echo "<br /><img class= 'aleft' src='img/{$row['imageName']}' alt='product image' /><p>{$row['description']}</p>";
				echo "<p><strong>Price:</strong> $price.  Only <strong>{$row['quantity']}</strong> left!</p>";
                echo "<div><br /><form action='index.php?page=$page' method='post' ><input type='hidden' name='item' value='{$row[productId]}' /><input type='submit' name='add' value='Add To Cart' /></form></div></div>";
			}
		}
	}
	
	function getSaleItems(){ //retrieves all products on sale and fills the sale section with the products
		$data = $this->getSaleProducts();
		if(count($data) > 0){ //output when there are products on sale
			foreach($data as $row){ //output for each product
				$price = "$".$row['price'];
				$salePrice = "$".$row['salePrice'];
				$page = 1;
				if(isset($_GET['page'])){ //output when page is set
					$page = $_GET['page'];
				}
				echo "<div class='one_item'><h3>{$row['productName']}</h3>";
				echo "<br /><img class= 'aleft' src='img/{$row['imageName']}' alt='product image' /><p>{$row['description']}</p>";
				echo "<p><strong>Sale Price:</strong> $salePrice (Regularly: $price).  Only <strong>{$row['quantity']}</strong> left!</p>";
                echo "<div><br /><form action='index.php?page=$page' method='post' ><input type='hidden' name='item' value='{$row[productId]}' /><input type='submit' name='add' value='Add To Cart' /></form></div></div>";
			}
		}
	}
	
	function getProduct($id){ //retrieves a product using a specific product id and returns the product in an array
		try{
			$data = Array();
			$stmt = $this->connection->prepare("select * from products where ProductID = ?");
			$stmt->bind_param("i",intval($id));
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($id, $productName, $description, $price, $quantity, $imageName, $salePrice);
			if ($stmt->num_rows > 0) { //output when there is a product matching the product id
				while($stmt->fetch()) { //fetch product
					$data[] = array(
							'productId'=>$id,
							'productName'=>$productName,
							'description'=>$description,
							'price'=>$price,
							'quantity'=>$quantity,
							'imageName'=>$imageName,
							'salePrice'=>$salePrice,
					);
				}
			}
		return $data;
		}catch(Exception $e){
			echo $e->getMessage();
			die();
		}
	}
	
	function getCart(){ //retrieves all products in cart and returns the products in an array
		try{
			$data = array();
			$stmt = $this->connection->prepare("select * from cart");
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($cartID, $productId, $productName, $description, $price, $quantity, $salePrice);
			if ($stmt->num_rows > 0) { //output when there are products in the cart
				while($row = $stmt->fetch()){ //fetch products
					$data[] = array(
								'cartId'=>$cartId,
								'productId'=>$productId,
								'productName'=>$productName,
								'description'=>$description,
								'price'=>$price,
								'quantity'=>$quantity,
								'salePrice'=>$salePrice,
					);
				}
			}
			return $data;
		}catch(Exception $e){
			echo $e->getMessage();
			die();
		}
	}
	
	function fillCart(){ //fills the cart with all selected products
		$data = $this->getCart();
		if(count($data) > 0){ //output when there are products in the cart 
			$cost = 0;
			foreach($data as $row){ //output for each product
				echo "<div class='one_item'><h3>{$row['productName']}</h3>";
				echo "<p>{$row['description']}</p>";
				if($row['salePrice'] == 0){ //output when sale price is 0
					$cost += $row['price'];
					$price = "$".$row['price'];
					echo "<p>Quantity: <strong>1</strong> at $price each. Total For Item: <strong>$price</strong></p></div>";
				}else{ //output when sale price is not 0
					$cost += $row['salePrice'];
					$price = "$".$row['salePrice'];
					echo "<p>Quantity: <strong>1</strong> at $price each. Total For Item: <strong>$price</strong></p></div>";
				}
			}
			$total = "$".$cost;
			echo "<p><h2 class='heading'>Total Cost: $total</h2><p><br /><form action='cart.php' method='post' ><input type='submit' name='empty' value='Empty Cart' /></form></p>";
		}else{ //output when there is nothing in the cart
			echo "<br /><h2 class='header'>Your cart is empty!</h2><br />";
		}
	}
	
	function addUpdate($id){ //inserts product into cart and updates the product quantity using a specific product id
		$data = $this->getProduct($id);
		if(count($data) > 0){ //output when there is a product that matches the product id 
			foreach($data as $row){ //output for product 
				$quantity = $row['quantity'] - 1;
				if($quantity >= 0){ //output when quantity is not 0
					$this->insert($row['productId'], $row['productName'], $row['description'], $row['price'], $row['quantity'], $row['salePrice']);
					$this->update($row['productId'], $quantity);
					echo "<h2 class='heading'>{$row['productName']} has been added to your cart</h2>";
				}else{ //output when quantity is 0
					echo "<h2 class='heading'>We're sorry, we are out of {$row['productName']}</h2>";
				}
			}
		}
	}
	
	function insert($productId,$productName,$description,$price,$quantity,$salePrice){ //inserts product into cart using specific table values returning the number of the insert id
			$queryString = "insert into cart (ProductID,ProductName,Description,Price,Quantity,SalePrice)
			values (?,?,?,?,?,?)";
			$insertId = "-1";
			if($stmt = $this->connection->prepare($queryString)){ //execute insert
				$stmt->bind_param("ssssss",$productId,$productName,$description,$price,$quantity,$salePrice);
				$stmt->execute();
				$stmt->store_result();
				$numRows = $stmt->affected_rows;
				$insertId = $stmt->insert_id;
			}
			return $insertId;
		}
		
	function update($id, $quantity){ //updates product in products using a specific product id and quantity returning the number of affected rows
			$queryString = "update products set Quantity = ? where ProductID = ?";
			$numRows = 0;
			if($stmt = $this->connection->prepare($queryString)){ //execute update
				$stmt->bind_param("ii", $quantity, $id);
				$stmt->execute();
				$stmt->store_result();
				$numRows = $stmt->affected_rows;
			}
			return $numRows;
		}
		
	function getAllProducts(){ //retrieves all products and returns the products in an array
		try{
			$data = array();
			$stmt = $this->connection->prepare("select * from products");
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($productId, $productName, $description, $price, $quantity, $imageName, $salePrice);
			if ($stmt->num_rows > 0) { //output when there are products
				while($row = $stmt->fetch()){ //fetch products
					$data[] = array(
								'productId'=>$productId,
								'productName'=>$productName,
								'description'=>$description,
								'price'=>$price,
								'quantity'=>$quantity,
								'imageName'=>$imageName,
								'salePrice'=>$salePrice,
					);
				}
			}
			return $data;
		}catch(Exception $e){
			echo $e->getMessage();
			die();
		}
	}
	
	function addOptions(){ //retrieves all products and creates options for each of the products
		$data = $this->getAllProducts();
		if(count($data) > 0){ //output when there are products
			foreach($data as $row){ //for each product
				echo "<option value='{$row['productId']}'>{$row['productName']} - {$row['description']}</option>";
			}
		}
	}
	
	function countSales(){ //retrieves the count of products on sale and returns an array
		try{
			$data = array();
			$stmt = $this->connection->prepare("select count(*) from products where not SalePrice = 0");
			$stmt->execute();
			$stmt->store_result();
			$stmt->bind_result($count);
			if ($stmt->num_rows > 0) { //output when there is a count
				while($row = $stmt->fetch()){ //fetch count
					$data[] = array(
								'count'=>$count,
					);
				}
			}
			return $data;
		}catch(Exception $e){
			echo $e->getMessage();
			die();
		}
	}
	
	function updateProduct($fields, $image){ //updates the product in products using a post array and a specific image name and returns the number of affected rows
		$queryString = "update products set ";
		$insertId = 0;
		$numRows = 0;
		foreach($fields as $k => $v){ //output for each field
			switch($k){ //outputs for specific fields
				case "name":
					$queryString.= "ProductName = '$v' , ";
					break;
				case "description":
					$queryString.= "Description = '$v' , ";
					break;
				case "price":
					$queryString.= "Price = '$v' , ";
					break;
				case "quantity":
					$queryString.= "Quantity = '$v' , ";
					break;
				case "oldImage":
					$queryString.= "ImageName = '$image' , ";
					break;
				case "salesPrice":
					$queryString.= "SalePrice = '$v' ";
					break;
				case "item":
					$insertId = intval($v);
					break;
			}
		}
		$queryString.= "where ProductID = ?";
		try{ //execute update
			$stmt = $this->connection->prepare($queryString);
			$stmt->bind_param("i",$insertId);
			$stmt->execute();
			$stmt->store_result();
			$numRows = $stmt->affected_rows;
			return $numRows;
		}catch(Exception $e){
			echo $e->getMessage();
		}
	}
	
	function insertProduct($productName,$description,$price,$quantity,$imageName,$salePrice){ //inserts the product into products using specific table values and returning the number of the insert id
		$queryString = "insert into products (ProductName,Description,Price,Quantity,ImageName,SalePrice)
			values (?,?,?,?,?,?)";
			$insertId = "-1";
			if($stmt = $this->connection->prepare($queryString)){ //execute insert
				$stmt->bind_param("ssssss",$productName,$description,$price,$quantity,$imageName,$salePrice);
				$stmt->execute();
				$stmt->store_result();
				$numRows = $stmt->affected_rows;
				$insertId = $stmt->insert_id;
			}
			return $insertId;
	}
	
	function deleteCart(){ //deletes all products from cart and returns the number of affected rows
			$queryString = "delete from cart";
			$numRows = 0;
			if($stmt = $this->connection->prepare($queryString)){ //execute delete
				$stmt->execute();
				$stmt->store_result();
				$numRows = $stmt->affected_rows;
			}
			return $numRows;
	}
}	
?>