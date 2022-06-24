<?php
session_start();
 
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<head>
    <title>Bem vindo</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <div class="corpo">
    <h2>
        <?php echo htmlspecialchars($_SESSION["username"]);?>
         é um usuario válido</h2>
        <a href="logout.php" class="btn btn-danger ml-3">logout</a>
</body>
</html>