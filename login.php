<?php

// Include the file containing the database connection code
include "db.php";

// Start a session to store user data
session_start();

// Check if email and password fields are set in the POST request
if(isset($_POST["email"]) && isset($_POST["password"])){

	// Sanitize the email to prevent SQL injection attacks
	$email = mysqli_real_escape_string($con,$_POST["email"]);

	// Hash the password using md5() for security
	$password = md5($_POST["password"]);

	// Query the database to check if there is a user with the given email and password
	$sql = "SELECT * FROM user_info WHERE email = '$email' AND password = '$password'";
	$run_query = mysqli_query($con,$sql);
	$count = mysqli_num_rows($run_query);

	// If a user record is found in the database, set session variables and handle the user's cart
	if($count == 1){
		// Fetch user data from the database and store it in session variables
		$row = mysqli_fetch_array($run_query);
		$_SESSION["uid"] = $row["user_id"];
		$_SESSION["name"] = $row["first_name"];

		// Get the user's IP address
		$ip_add = getenv("REMOTE_ADDR");

		// If there are products in the user's cart stored in a cookie, handle them
		if (isset($_COOKIE["product_list"])) {
			// Decode the JSON data stored in the cookie and store it in an array
			$p_list = stripcslashes($_COOKIE["product_list"]);
			$product_list = json_decode($p_list,true);

			// Loop through each product in the array and check if it is already in the user's cart
			for ($i=0; $i < count($product_list); $i++) {
				// Query the database to check if the product is already in the user's cart
				$verify_cart = "SELECT id FROM cart WHERE user_id = $_SESSION[uid] AND p_id = ".$product_list[$i];
				$result  = mysqli_query($con,$verify_cart);
				// If the product is not already in the user's cart, add it
				if(mysqli_num_rows($result) < 1){
					$update_cart = "UPDATE cart SET user_id = '$_SESSION[uid]' WHERE ip_add = '$ip_add' AND user_id = -1";
					mysqli_query($con,$update_cart);
				}
				// If the product is already in the user's cart, delete it from the cart with the temporary user ID
				else{
					$delete_existing_product = "DELETE FROM cart WHERE user_id = -1 AND ip_add = '$ip_add' AND p_id = ".$product_list[$i];
					mysqli_query($con,$delete_existing_product);
				}
			}

			// Clear the cookie containing the product list
			setcookie("product_list","",strtotime("-1 day"),"/");

			// Return a success message to the client-side JavaScript
			echo "cart_login";
			exit();
		}

		// If there are no products in the user's cart, return a success message to the client-side JavaScript
		echo "login_success";
		exit();
	}
	// If no user record is found in the database, return an error message to the client-side JavaScript
	else{
		echo "<span style='color:red;'>Please register before login..!</span>";
		exit();
	}
}
?>
