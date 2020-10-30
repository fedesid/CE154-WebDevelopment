<?php//1904802?>
<?php include('functions.php') ?>

<?php
	if(($_SESSION["user"]["is_admin"]) !="1"){
		header("location: user.php");
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
		
		<div class="managegames">
			
			<div class="addgame_container">			
				<div class="managegame_text">
					<h1>ADD A NEW GAME</h1>
				</div>
				<div class="managegame">
					<form action="" method="post">
						<label for="title">Game Title:</label><br>
						<input type="text" id="gametitle" name="title" placeholder="Title"><br><br>
						<label for="img">Image:</label><br>
						<input type="url" id="img" name="img" placeholder="Image (URL)"><br><br>
									
						<label for="cars">Choose a genre:</label>
						<select id="genre" name="genre">
							<option value="rpg" name="genre">RPG</option>
							<option value="fps" name="genre">FPS</option>
							<option value="sim" name="genre">Sim</option>
							<option value="str" name="genre">Strategy</option>
							<option value="???" name="genre">Other</option>
						</select> <br><br>
						
						<label for="rating">Select rating</label>
						<input type="number" id="rating" value="rating" name="rating" min="0" max="100"><br><br>
						
						<input type="submit" name="addgame_btn" value="Add Game"><br><br>
						<div style ="color:#cc0000;"><?php if(isset($_POST['addgame_btn'])){ echo display_error();} ?></div><br>
					</form>
					
				</div>
			</div>

			<div class="removegame_container">
				<div class="managegame_text">
					<h1>REMOVE A GAME</h1>
				</div>
				<div class="managegame" style="width: 110%; padding: 3%">
					<form action="" method="post" onsubmit="return confirm('Do you really want to remove this game? All the reviews will be deleted as well.');">
						<label for="title">Select a Game</label><br>
						<select id="gameTitle" name="gameTitle">
							<?php
								$query = "SELECT title FROM games";
								$results = mysqli_query($db, $query);								

								foreach($results as $gameTitle){
									$gameTitle = $gameTitle['title'];
									echo "<option value='$gameTitle' name='gameTitle'>$gameTitle</option>";
								}
							?>
						</select><br><br>
						<input type="submit" name="removegame_btn" value="Remove Game">
						<div style ="color:#cc0000;"><?php if(isset($_POST['removegame_btn'])){ echo display_error();} ?></div><br>
					</from>
					
				</div>

			</div>

		</div>
		
		<footer>
			<h3>Copyright © 2020. All rights reserved.</h3>
		</footer>
		
		<script>
			var slideIndex = 0;
			showSlides();

			function showSlides() {
			  var i;
			  var slides = document.getElementsByClassName("mySlides");
			  var dots = document.getElementsByClassName("dot");
			  for (i = 0; i < slides.length; i++) {
				slides[i].style.display = "none";  
			  }
			  slideIndex++;
			  if (slideIndex > slides.length) {slideIndex = 1}    
			  for (i = 0; i < dots.length; i++) {
				dots[i].className = dots[i].className.replace(" active", "");
			  }
			  slides[slideIndex-1].style.display = "block";  
			  dots[slideIndex-1].className += " active";
			  setTimeout(showSlides, 2000); // Change image every 2 seconds
			}
		</script>
	</body>
</html>