<?php//1904802?>
<?php 
session_start();

$db = mysqli_connect("localhost", "root", "", "ce154");

$username = "";
$email    = "";
$errors   = array(); 

if (isset($_POST['register_btn'])) {
	register();
}

function randomSalt($len = 5) {
	$chars = 'abcdefghijklmnopqrstuvwxyz0123456789';
	$l = strlen($chars) - 1;
	$str = '';
	for ($i = 0; $i < $len; ++$i) {
		$str .= $chars[rand(0, $l)];
 	}
	return $str;
}


// REGISTER USER
function register(){
	
	global $db, $errors, $username, $email;
	
	$username    =  e($_POST['username']);
	$password_1  =  e($_POST['password_1']);
	$password_2  =  e($_POST['password_2']);
	
	if (empty($username)) { 
		array_push($errors, "Username is required"); 
	}
	if (empty($password_1)) { 
		array_push($errors, "Password is required"); 
	}
	if ($password_1 != $password_2) {
		array_push($errors, "The two passwords do not match");
	}

	// register user if there are no errors in the form
	if (count($errors) == 0) {

		$salt = randomSalt();
		$password = sha1( $password_1 . $salt );

		$query = "SELECT * FROM users WHERE uname = '$username'";
		$results = mysqli_query($db, $query);

		if (mysqli_num_rows($results) == 0) { //register user if username is not taken

			$query = "INSERT INTO users (uname, pass, salt, is_admin) VALUES('$username', '$password', '$salt', '0')";
			mysqli_query($db, $query);
			
			$query = "SELECT * FROM users WHERE uname = '$username'";
			$results = mysqli_query($db, $query);
			$logged_in_user = mysqli_fetch_assoc($results);
			$_SESSION["user"] = $logged_in_user;
			$_SESSION["admin"] = "0";
			header('location: user.php');				
		}else{
			array_push($errors, "User already exists");
		}
	}
}

function e($val){
	global $db;
	return mysqli_real_escape_string($db, trim($val));
}

function display_error() {
	global $errors;

	if (count($errors) > 0){
		echo '<div class="error">';
			foreach ($errors as $error){
				echo $error .'<br>';
			}
		echo '</div>';
	}
}

if (isset($_POST['login_btn'])) {
	login();
}


function login(){
	global $db, $username, $errors;
	
	$username = e($_POST['username']);
	$password = e($_POST['password']);
	
	if (empty($username)) {
		array_push($errors, "Username is required");
	}
	if (empty($password)) {
		array_push($errors, "Password is required");
	}
	
	if (count($errors) == 0) {
		
		//fetching salt from the database. encrypt the password and check if it matches
		$query = "SELECT * FROM users WHERE uname='$username' LIMIT 1";		
		$results = mysqli_query($db, $query);		
		$not_logged_in_user = mysqli_fetch_array($results);		
		$salt = $not_logged_in_user["salt"];
		$password = sha1( $password . $salt );
		
		$query = "SELECT * FROM users WHERE uname='$username' AND pass='$password' LIMIT 1";
		$results = mysqli_query($db, $query);

		if (mysqli_num_rows($results) == 1) { // user found
			// check if user is admin or user
			
			$logged_in_user = mysqli_fetch_assoc($results);
			if ($logged_in_user['is_admin'] == TRUE) {

				$_SESSION['user'] = $logged_in_user;
				$_SESSION['success']  = "You are now logged in";
				$_SESSION["admin"] = "1";
				
				header('location: admin.php');		  
			}else{
				$_SESSION['user'] = $logged_in_user;
				$_SESSION['success']  = "You are now logged in";
				$_SESSION["admin"] = "0";

				header('location: user.php');
			}
		}else {
			array_push($errors, "Wrong username/password combination");
		}
	}
}

function logout(){
	
	session_start();
	session_unset();
	session_destroy();
	
	header("location: index.php");
}

if(isset($_POST['addgame_btn'])){
	addGame();
}

function addGame(){
	global $db, $username, $errors;
			
	$title = e($_POST['title']);
	$img = e($_POST['img']);
	$genre = e($_POST['genre']);
	$rating = (int) e($_POST['rating']);
	
	$query = "SELECT * FROM games WHERE title = '$title'";
	$results = mysqli_query($db, $query);		
	$rows = mysqli_num_rows($results);
	if($rows == 0){
		
		if(!empty($title) && !empty($img) && !empty($genre) && !empty($rating)){
			$query = "INSERT INTO games (title, image, genre, rating) VALUES ('$title', '$img', '$genre', '$rating')";
			
			if ($db->query($query) === TRUE) {
				array_push($errors, "New record creaeted succesfully");
			}else{
				echo "Error: " . $query . "<br>" . $db->error;
			}
			
		}else{
			array_push($errors, "Invalid input");
		}
		
	}else{
		array_push($errors, "Game already exists");
	}
	
}

if(isset($_POST['removegame_btn'])){
	removeGame();
}

function removeGame(){
	global $db, $username, $errors;

	$gameTitle = $_POST['gameTitle'];
	$query = "SELECT id FROM games WHERE title = '$gameTitle'";
	$results = mysqli_query($db, $query);
	$results = mysqli_fetch_assoc($results);
	$gameId = $results['id'];

	$query = "DELETE FROM games WHERE id = $gameId";
	if ($db->query($query) === TRUE) {
		array_push($errors, "Game deleted succesfully");
	}else{
		echo "Error: " . $query . "<br>" . $db->error;
	}
	

}

function bookmark(){
	global $db, $username, $errors;

	$userId = (int) $_SESSION["user"]["id"];
	$gameId = (int) $_GET["id"];

	//$message = "CIAO";
	//echo "<script type='text/javascript'>alert('$gameId');</script>";
	
	$query = "SELECT * FROM bookmarks WHERE user_id = $userId AND game_id = $gameId";
	$results = mysqli_query($db, $query);
	$rows = mysqli_num_rows($results);
	
	//echo "<script type='text/javascript'>alert('$rows');</script>";

	if (mysqli_num_rows($results) == 0){
		$query = "INSERT INTO bookmarks (user_id, game_id) VALUES ('$userId', '$gameId')";
		$results = mysqli_query($db, $query);
		
		$bookmarkbtn = "UNBOOKMARK";
		
	}else{
		$query = "DELETE FROM bookmarks WHERE user_id = $userId AND game_id = $gameId";
		$results = mysqli_query($db, $query);
		$bookmarkbtn = "BOOKMARK";

		//$message = "ELIMINATO";
		//echo "<script type='text/javascript'>alert('$message');</script>";
	}
	return $bookmarkbtn;
}
?>