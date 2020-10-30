<?php//1904802?>
<?php include('functions.php') ?>


<?php
	header("Cache-Control: no-cache");
	global $db, $username, $errors, $check;
	$gameId = $_GET["id"];
	$userId = (int) $_SESSION["user"]["id"];
	
	$query = "SELECT * FROM games WHERE id = $gameId";
	$results = mysqli_query($db, $query);
	$game = mysqli_fetch_assoc($results);	
	$title = $game["title"];
	$file = $game["image"];
	$genre = $game["genre"];
	$gameRating = $game["rating"];

	$query = "SELECT title FROM genres WHERE id = '$genre'";
	$results = mysqli_query($db, $query);
	$genre = mysqli_fetch_assoc($results);
	$genre = $genre["title"];
	
	
	$query = "SELECT id, review FROM reviews WHERE user_id = '".$userId."' AND game_id = '".$gameId."'";
	$results = mysqli_query($db, $query);				
	$rows = mysqli_num_rows($results);
	if ($rows > 0){
		$_SESSION['message'] = "You have already reviewd this game";
	}

	$query = "SELECT * FROM bookmarks WHERE user_id = $userId AND game_id = $gameId";
	$results = mysqli_query($db, $query);
	if (mysqli_num_rows($results) == 0){
		$bookmarkbtn = "BOOKMARK";

		//$message = "bookmark";
		//echo "<script type='text/javascript'>alert('$message');</script>";
	}else{
		$bookmarkbtn = "UNBOOKMARK";

		//$message = "unbookmark";
		//echo "<script type='text/javascript'>alert('$message');</script>";
	}
?>

<?php
	if (isset($_POST["bookmark"])){
		$bookmarkbtn = bookmark();		
		header("location: game.php?id=$gameId");
	}
?>

<html>
	<head>
		<title>Roasted Games</title>
		<link rel="stylesheet" type="text/css" href="style1.css">
		<link href="https://fonts.googleapis.com/css2?family=Jura:wght@500&display=swap" rel="stylesheet">
	</head>
	<body>
		<a href="admin.php" style="text-decoration: none">
			<div class="top_header">
				<p id="title">ROASTED GAMES</p>
			</div>
		</a>
		
		<div class="ciao">

			<div class="spacetaker">
			</div>
		
			<div class="top_nav">
				
				<a href="admin.php">GAME LIST</a>
				
				<?php
					if(isset($_SESSION["user"])){
						echo	"<div> <a href=profile.php class=\"aNav\">PROFILE</a> </div>";				
						echo	"<a href=logout.php class=\"aNav\">SIGN OUT</a>";
					}else{
						echo	"<div> <a href=login.php class=\"aNav\">LOG IN</a> </div>";				
						echo	"<a href=register.php class=\"aNav\">REGISTER</a>";
					}			
				?>

				<?php
					if(($_SESSION["user"]["is_admin"]) =="1"){
					echo "<div> <a href= newgame.php class=\"aNav\">MANAGE GAMES</a> </div>";
					}
				?>	
						
			</div>
			
			<?php
				if(isset($_SESSION["user"])){
				echo	"<div class=profilepreview>";
				echo	"<h4>Welcome Back!</h4>";
				echo	"<p>";
				echo	$_SESSION['user']['uname'];
				echo	"</p>";
				echo	"</div>";
					
				}
			?>			
		</div>
		
		<div class="gamepage_container">
			<div class="infogame_box">
				<div class="img"><?php echo "<img src=\"$file\">"; ?></div>
				<div class="texts">
					<label style="align-self: center; font-weight: 999; text-shadow: 0.5px 0.5px 1px black;">Game Name</label>
					<div class="title"><?php echo "<br>" . $title . "<br> <br>" ?></div>
					<label style="align-self: center; font-weight: 999; text-shadow: 0.5px 0.5px 1px black;">Game Genre</label>
					<div class="genre"><?php echo  "<br>". $genre . "<br> <br>" ?></div>
					<label style="align-self: center; font-weight: 999; text-shadow: 0.5px 0.5px 1px black;">Game Rating</label>
					<div class="rating"><?php echo $gameRating ?></div>
					<?php
						if (isset($_SESSION["user"])){
							echo '<form action="" method="post"><div class="bookmark"><input onclick="buttonReset()" type="submit" id="bookmarkbtn" name="bookmark" value= '.$bookmarkbtn.'></input></div></form>';
						}
					?>
				</div>
			</div>
			
			<?php
				if (isset($_SESSION["user"])){
					echo	'<div class="makeReview_container">';
					echo	'<div class="makeReview">';
								$query = "SELECT title, review, rating FROM reviews WHERE user_id = $userId AND game_id = $gameId";
								$results = mysqli_query($db, $query);

								if (mysqli_num_rows($results) == 1){
									$results = mysqli_fetch_assoc($results);
									$valueTitle = $results["title"];
									$valueReview = $results["review"];
									$valueRating = $results["rating"];
									$valueButton = "Modify";						
								}else{
									$valueButton = "Submit";
								}
							
								echo	'<form action="" method="post">';
								echo	'<label for="title">Review Title</label><br>';
								echo	'<input type="text" id="reviewtitle" name="reviewtitle" value= "'.$valueTitle.'" placeholder="Title" required><br><br>';
								echo	'<label for="msg">Review</label><br>';
								echo	'<textarea name="msg" style="width:100%; height:100px;" placeholder="Review" required>'.$valueReview.'</textarea>';
								echo	'<label for="rating">Select rating</label>';
								echo	'<input type="number" id="rating" name="rating" value= '.$valueRating.' placeholder="Rating" min="0" max="100" required><br><br>';
								echo	'<input type="submit" value= '.$valueButton.' name="submit">';
								echo	'<div style ="color:#cc0000;"><?php echo showError($errorMsg) ?></div><br>';
								echo	'</form>';					

								
								
					echo	'</div>';
					echo	'</div>';
				}
			?>
			
			<?php
				global $db, $username, $errors;
				
				$check = 0; //I created this variable because the rating of the game would halve every time I reloaded the page
				
				$reviewTitle = e($_POST["reviewtitle"]);
				$review = e($_POST["msg"]);
				(int) $rating = e($_POST["rating"]);
				$userId = $_SESSION["user"]["id"];
				$btn = $_POST["submit"];

				echo $btn;
				
				$query = "SELECT id, review FROM reviews WHERE user_id = '".$userId."' AND game_id = '".$gameId."'";
				$results = mysqli_query($db, $query);				
				$rows = mysqli_num_rows($results);

				if (isset($_POST["submit"])){
					if ($rows == 0){
						
						
						$query = "INSERT INTO reviews (user_id, game_id, rating, title, review) VALUES	('$userId', '$gameId', '$rating', '$reviewTitle', '$review')";
						$results = mysqli_query($db, $query);
						
						
						$btn = false;

					}else{
						if ($rows == 1){
							$query = "DELETE FROM reviews WHERE user_id = $userId AND game_id = $gameId";
							$results = mysqli_query($db, $query);

							$query = "INSERT INTO reviews (user_id, game_id, rating, title, review) VALUES	('$userId', '$gameId', '$rating', '$reviewTitle', '$review')";
							$results = mysqli_query($db, $query);
							echo "DENTROOOO";
						}					

						$errorMsg = "You have already reviewd this game";
						$errori = "You have already reviewd this game";
						$btn = false;;
					}

					if ($gameRating > 0) {
						$query = "SELECT rating FROM reviews WHERE game_id = $gameId";
						$results = mysqli_query($db, $query);

						foreach($results as $row){
							$gameRatings = $gameRatings + $row["rating"];
						}
						$gameRating = ($gameRating + $gameRatings)/(mysqli_num_rows($results) + 1);
						
					}else{
						$gameRating = $rating;
					}

					$query = "UPDATE games SET rating = '$gameRating' WHERE id = '$gameId'";
					$results = mysqli_query($db, $query);
					
					header("location: game.php?id=$gameId");
				}				
			?>
			
			<div class="reviewsbox">
				<label>Reviews</label><br>
				<?php
					global $db, $username, $errors;
					
					$query = "SELECT reviews.id, reviews.user_id, reviews.rating, reviews.title, reviews.review FROM reviews WHERE  reviews.game_id = '$gameId'";
					$results = mysqli_query($db, $query);
					
					foreach($results as $review){	
						
						$reviewId = $review['id'];
						$reviewUserId = $review['user_id'];
						$reviewRating = $review["rating"];
						$reviewTitle = $review["title"];
						$reviewReview = $review["review"];
						
						$query2 = "SELECT users.uname FROM users, reviews WHERE users.id = '$reviewUserId'";
						$results2 = mysqli_query($db, $query2);						
						$reviewUser = mysqli_fetch_assoc($results2);
						$reviewUser = $reviewUser["uname"];
						
						echo "<div class=\"review_container\">";
								echo "<br>";
								echo $gameTitle;
								echo "<br>";
								echo "Review title: "; echo "<strong>$reviewTitle</strong>"; echo " "; echo"<p style='float: right'>Rating: <strong>$reviewRating</strong></p> ";
								echo "<br>";
								echo "User: " . "<strong>$reviewUser</strong>";
								echo "<br>";
								echo "<div class=\"reviewReview\">";
									echo "<p style='white-space: pre-line; word-wrap: break-word;'>$reviewReview</p>";
								echo "</div>";
								echo "<br>";
								echo "<br>";
						echo "</div>";
					}
				?>
			</div>
		</div>

		<footer>
			<h3>Copyright © 2020. All rights reserved.</h3>
		</footer>
		
		<script>
			function change() // no ';' here
			{
				var elem = document.getElementById("bookmarkbtn");
				if (elem.value=="BOOKMARK") elem.value = "UNBOOKMARK";
				else elem.value = "BOOKMARK";
			}
		</script>
	</body>
</html>