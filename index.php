<?php//1904802?>
<?php include('functions.php') ?>
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
				
				<a href="login.php" class="aNav">LOG IN</a>
				
				<a href="register.php" class="aNav">REGISTER</a>

				<div class="searchbar">
					<form action="" method="post">
						<input type="text" name="search" placeholder="Search titles..."></a>
					</form>
				</div>			
			</div>		
			
		</div>
<!--		
		<div class="banner">
			<img src="assets/img/background/tiger.jpg">
		</div>
-->		
<div class="bestgameheader">
			<h2 style="flex-grow: 1.3">BEST RATED GAME</h2>
			<h2 style="flex-grow: 1">FOLLOW UPS</h2>
		</div>
		
		<div class="topbox">
			<div class="slideshow" style="flex-grow: 5">
				<?php
					global $db, $username, $errors;
					
					$query = "SELECT title, image, rating, id FROM games ORDER BY rating DESC LIMIT 1";
					$results = mysqli_query($db, $query);

					$results = mysqli_fetch_assoc($results);
					$gameTitle = $results["title"];
					$file = $results["image"];
					$gameRating = $results["rating"];
					$gameId = $results["id"];

					echo $title;
					echo $rating;
					echo $img;

					echo 	"<div class=\"slideshow\">
								<a href='game.php?id=$gameId'>
									<img src=\"$file\" alt=\"error\" class=\"game_img\">
								</a>
							</div>";

				?>
			</div>
			
			<div class="topgames" style="flex-grow: 1">
				
				<?php
					global $db, $username, $errors;
					
					$query = "SELECT title, image, rating, id FROM games ORDER BY rating DESC LIMIT 6 OFFSET 1";
					$results = mysqli_query($db, $query);
					
					
					foreach($results as $game){
						$gameTitle = $game["title"];
						$file = $game["image"];
						$gameRating = $game["rating"];
						$gameId = $game["id"];

						echo "<div class=\"topgame\">";
						echo "<a href='game.php?id=$gameId'>";
						echo "<img src=\"$file\">";
						echo "</a>";
						echo "<div class=\"gameinfo\">";
						echo $gameTitle . "<br>";
						echo "Rating: " . '<strong>'.$gameRating.'</strong>';
						echo "</div>";
						echo "</div>";
					}

				?>
<!--
				<div class="topgame">
					<img src="assets/img/games/Sid Meiers Civilization V Brave New World.jfif">
					<div class="gameinfo">
						<p>GAME 1 X Reviews</p>
					</div>
				</div>
				<div class="topgame">
					<img src="assets/img/games/crusader kings 2.jpg">
					<div class="gameinfo">
						<p>GAME 1 X Reviews</p>
					</div>
				</div>
				<div class="topgame">
					<img src="assets/img/games/cover-else-heart.break.png">
					<div class="gameinfo">
						<p>GAME 1 X Reviews</p>
					</div>
				</div>
				<div class="topgame">
					<img src="assets/img/games/Train Simulator 2020.jfif">
					<div class="gameinfo">
						<p>GAME 1 X Reviews</p>
					</div>
				</div>
				<div class="topgame">
					<img src="assets/img/games/Shadowrun Hong Kong - Extended Edition.jpg">
					<div class="gameinfo">
						<p>GAME 1 X Reviews</p>
					</div>
				</div>
				<div class="topgame">
					<img src="assets/img/games/Cave Story+.jpg">
					<div class="gameinfo">
						<p>GAME 1 X Reviews</p>
					</div>
				</div>
-->
			</div>
			
		</div>
		
		<div class="midheader">
			<h2>GAME LIST</h2>
		</div>
		<div class="filters">
				<form action="" method="get">
					<select name = "order" onchange="this.form.submit()">
						<option class="filters">Order By</option>
						<option class="filters" type="submit" value="A-Z (A first)" name="sort">A-Z (A first)</option>
						<option class="filters" type="submit" value="A-Z (Z first)" name="sort">A-Z (Z first)</option>
						<option class="filters" type="submit" value="Rating (Best first)" name="sort">Rating (Best first)</option>
						<option class="filters" type="submit" value="Rating (Worst first)" name="sort">Rating (Worst first)</option>
					</select>
				</form>
				<form action="" method="get"> 
					<select name ="order" onchange="this.form.submit()">
						<option class="filters">Filter By Genre</option>
						<option class="filters" type="submit" value="Rpg" name="sort">Role-Playing Game</option>
						<option class="filters" type="submit" value="Sim" name="sort">Simulation Game</option>
						<option class="filters" type="submit" value="Strategy" name="sort">Strategy</option>
						<option class="filters" type="submit" value="FPS" name="sort">First Person Shooter</option>
						<option class="filters" type="submit" value="???" name="sort">Other</option>
					</select>
				</form>
			</div>
		
		<div class="games_container" id="games">
		<?php
				global $db, $username, $errors;
				$sort = $_GET["order"];
				$search = e($_POST['search']);
				
				if(!empty($search)){
					$query = "SELECT * FROM games WHERE title LIKE '%".$search."%'";
				}elseif($sort == "Rpg"){
					$query = "SELECT * FROM games WHERE genre = 'rpg'";
				}elseif($sort == "Sim"){
					$query = "SELECT * FROM games WHERE genre = 'sim'";					
				}elseif($sort == "Strategy"){
					$query = "SELECT * FROM games WHERE genre = 'str'";
				}elseif($sort == "FPS"){
					$query = "SELECT * FROM games WHERE genre = 'fps'";
				}elseif($sort == "???"){
					$query = "SELECT * FROM games WHERE genre = '???'";
				}elseif($sort == "A-Z (A first)"){
					$query = "SELECT * FROM games ORDER BY title";
				}elseif($sort == "A-Z (Z first)"){
					$query = "SELECT * FROM games ORDER BY title DESC";
				}elseif($sort == "Rating (Best first)"){
					$query = "SELECT * FROM games ORDER BY rating DESC";
				}elseif($sort == "Rating (Worst first)"){
					$query = "SELECT * FROM games ORDER BY rating ASC";					
				}else{
					$query = "SELECT * FROM games";
				}
				
				$results = mysqli_query($db, $query);
				$rows = mysqli_num_rows($results);
				
				if (mysqli_num_rows($results) != 0){

					while($row = mysqli_fetch_assoc($results)){
						$games[] = $row;
					}				
					
					$i = 0;
					
					echo "<table>";
					while($i < $rows){
						
						$file = $games[$i]["image"];
						$gameTitle = $games[$i]["title"];
						$gameId = $games[$i]["id"];
						$_SESSION["games"] = $games;
						echo "<tr>";
						echo "</tr>";
						echo 	"<div class=\"game_box\">
									<img src=\"$file\" alt=\"error\" class=\"game_img\">
									<a href='game.php?id=$gameId'>
										<div class=\"middle\">
											<p class=\"text\">$gameTitle</p>
										</div>
									</a>
								</div>";
						
						$i++;
						
					}
					echo "</table>";
				
				}else{
					echo "<strong style='justify-self: center; font-size: 30px'>";
					echo "No Results";
					echo "</strong>";
				}
			?>
<!--
			<div class="game_box">
				<img src="assets/img/games/Sid Meiers Civilization V Brave New World.jfif" class="game_img">
				<div class="middle">					
					<div class="text">Sid Meier's Civilization V: Brave New World</div>					
				</div>
			</div>
			
			<div class="game_box">
				<img src="assets/img/games/crusader kings 2.jpg" class="game_img">
				<div class="middle">					
					<div class="text">Crusader Kings II</div>					
				</div>
			</div>
			
			<div class="game_box">
				<img src="assets/img/games/Warcraft III Reforged.jfif" class="game_img">
				<div class="middle">					
					<div class="text">Warcraft III: Reforged</div>					
				</div>
			</div>
			
			<div class="game_box">
				<img src="assets/img/games/cover-else-heart.break.png" class="game_img">
				<div class="middle">					
					<div class="text">Else Heart.Break()</div>					
				</div>
			</div>
			
			<div class="game_box">
				<img src="assets/img/games/Shadowrun Dragonfall - Directors Cut.png" class="game_img">
				<div class="middle">					
					<div class="text">Shadowrun: Dragonfall - Director's Cut</div>					
				</div>
			</div>
			
			<div class="game_box">
				<img src="assets/img/games/Stardew Valley.jfif" class="game_img">
				<div class="middle">					
					<div class="text">Stardew Valley</div>					
				</div>
			</div>
			
			<div class="game_box">
				<img src="assets/img/games/disco-elysium-art-and-logo.jpeg" class="game_img">
				<div class="middle">					
					<div class="text">Disco Elysium</div>					
				</div>
			</div>
			
			<div class="game_box">
				<img src="assets/img/games/RimWorld.jpg" class="game_img">
				<div class="middle">					
					<div class="text">RimWorld</div>					
				</div>
			</div>
			
			<div class="game_box">
				<img src="assets/img/games/Tom Clancys Rainbow Six Siege.jpg" class="game_img">
				<div class="middle">					
					<div class="text">Tom Clancy's Rainbow Six Siege</div>					
				</div>
			</div>
			
			<div class="game_box">
				<img src="assets/img/games/Euro Truck Simulator 2.jfif" class="game_img">
				<div class="middle">					
					<div class="text">Euro Truck Simulator 2</div>					
				</div>
			</div>
			
			<div class="game_box">
				<img src="assets/img/games/farming-simulator-19-cover.jpg" class="game_img">
				<div class="middle">					
					<div class="text">Farming Simulator 19</div>					
				</div>
			</div>
			
			<div class="game_box">
				<img src="assets/img/games/Train Simulator 2020.jfif" class="game_img">
				<div class="middle">					
					<div class="text">Train Simulator 2020</div>					
				</div>
			</div>
			
			<div class="game_box">
				<img src="assets/img/games/Project Zomboid.jpg" class="game_img">
				<div class="middle">					
					<div class="text">Project Zomboid</div>					
				</div>
			</div>
			
			<div class="game_box">
				<img src="assets/img/games/Shadowrun Returns.jpg" class="game_img">
				<div class="middle">					
					<div class="text">Shadowrun Returns</div>					
				</div>
			</div>
			
			<div class="game_box">
				<img src="assets/img/games/Shadowrun Hong Kong - Extended Edition.jpg" class="game_img">
				<div class="middle">					
					<div class="text">Shadowrun: Hong Kong - Extended Edition</div>					
				</div>
			</div>
			
			<div class="game_box">
				<img src="assets/img/games/Cave Story+.jpg" class="game_img">
				<div class="middle">					
					<div class="text">Cave Story+</div>					
				</div>
			</div>
			
			<div class="game_box">
				<img src="assets/img/games/Sorcery! Parts 1 & 2.jpg" class="game_img">
				<div class="middle">					
					<div class="text">Sorcery! Parts 1 & 2</div>					
				</div>
			</div>
			
			<div class="game_box">
				<img src="assets/img/games/Dwarf Fortress.png" class="game_img">
				<div class="middle">					
					<div class="text">Dwarf Fortress</div>					
				</div>
			</div>
-->
		</div>
		
		<footer>
			<h3>Copyright © 2020. All rights reserved.</h3>
		</footer>
		
	</body>
</html>