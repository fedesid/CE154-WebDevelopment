<?php//1904802?>
<?php include('functions.php') ?>

<?php
	if (!isset($_SESSION["user"])){
		header ("Location: index.php");
	}
?>

<?php
	global $db, $username, $errors;
	$userName = $_SESSION["user"]["uname"];
	$userId = $_SESSION["user"]["id"];
	
	$query = "SELECT id, game_id, rating, title, review FROM reviews WHERE user_id = '$userId'";
	$results = mysqli_query($db, $query);
	$review = mysqli_fetch_assoc($results);
	$reviewId = $review["id"];
	$game_id = $review["game_id"];
	$reviewRating = $review["rating"];
	$reviewTitle = $review["title"];
	$reviewReview = $review["review"];
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
				
				<a href="profile.php" class="aNav">PROFILE</a>
				
				<a href="logout.php" class="aNav">SIGN OUT</a>

				<?php
					if(($_SESSION["user"]["is_admin"]) =="1"){
					echo "<div> <a href= newgame.php class=\"aNav\">MANAGE GAMES</a> </div>";
					}
				?>			
			</div>
		
			<div class="profilepreview">
				<h4>Welcome Back!</h4>
				<p><?php echo $_SESSION["user"]["uname"]?></p>
			</div>

			
			
		</div>
		
		<div class="profile_container">
			<div class="profileinfobox">
				<?php
					$query = "SELECT * FROM bookmarks WHERE user_id = $userId";
					$results = mysqli_query($db, $query);

					if (mysqli_num_rows($results) == 0){
						echo "<br>No games bookmarked<br>";
					}else{
						echo "<br>Bookmarked games:<br>";
					}

				?>
				<div class="bookmarks-container">					
					<?php
						$query = "SELECT title, image, id FROM games WHERE id IN (SELECT game_id FROM bookmarks WHERE user_id = $userId)";
						$results = mysqli_query($db, $query);

						foreach ($results as $bGame){
							$gameTitle = $bGame["title"];
							$file = $bGame["image"];
							$gameId = $bGame["id"];
							
							echo "<br>";
							echo "<br>";
							echo "<div class=\"topgame\">";
							echo "<a href='game.php?id=$gameId'>";
							echo "<img src=\"$file\">";
							echo "</a>";
							echo "<div class=\"gameinfo\">";
							echo $gameTitle . "<br>";
							echo "</div>";
							echo "</div>";
						}
					?>
				</div>
			</div>
			
			<div class="reviewsbox">
				<label>My Reviews</label><br>
				
				<?php
					$query = "SELECT id, game_id, rating, title, review FROM reviews WHERE user_id = '$userId' ORDER BY id DESC";
					$results = mysqli_query($db, $query);
					$review = mysqli_fetch_assoc($results);
					
					foreach($results as $review){
						$reviewId = $review["id"];
						$game_id = $review["game_id"];
						$reviewRating = $review["rating"];
						$reviewTitle = $review["title"];
						$reviewReview = $review["review"];
						
						$query2 = "SELECT title FROM games WHERE id = '$game_id'";
						$results2 = mysqli_query($db, $query2);						
						$gameName = mysqli_fetch_assoc($results2);
						$gameTitle = $gameName["title"];
						
						echo "<div class=\"review_container\">";
								echo "<br>";
								echo "<a href='game.php?id=$game_id' style='text-decoration: none; color: black'><strong style='font-size: 25px'>$gameTitle</strong></a><br>"; 
								echo "<br>";
								echo "Review title: "; echo "<strong>$reviewTitle</strong>"; echo " "; echo"<p style='float: right'>Rating: <strong>$reviewRating</strong></p> ";
								echo "<br>";
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

	</body>
</html>