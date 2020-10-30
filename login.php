<?php//1904802?>
<?php include('functions.php') ?>

<html>
	<head>
		<title>Marvelous Reviews</title>
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
            <div class="top_nav">			
                <a href="index.php">GAME LIST</a>
                <a href="login.php">LOG IN</a>
                <a href="register.php">REGISTER</a>
            </div>
        </div>
		
		<div class="addgame_container" style="margin-top: 3%">			
			<div class="managegame" style="width: 15%">
				<label>Welcome back</label><br><br>
				<form action="login.php" method="post">
				
					<div style ="color:#cc0000;"><?php echo display_error(); ?></div><br>
					<input type="text" id="username" name="username" placeholder="Username"><br><br>
					
					<input type="password" id="password" name="password" placeholder="Password"><br><br>
					
					<input type="submit" name="login_btn" value="LOG IN">
				</form>
			</div>
		</div>

		<footer>
			<h3>Copyright © 2020. All rights reserved.</h3>
		</footer>

	</body>
</html>