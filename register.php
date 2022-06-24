<?php
require_once "config.php";

$username = $password   = "";
$username_err = $password_err   = "";
 

if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    if(empty(trim($_POST["username"]))){
        $username_err = "Por favor coloque um nome de usuário.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "<br>O usuário deve conter apenas letras e números";
    } else{
        $sql = "SELECT id FROM usuario WHERE username = :username";
        
        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $param_username = trim($_POST["username"]);
            
            if($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $username_err = "<br>[este usuario ja está em uso!]";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Erro inesperado";
            }

            unset($stmt);
        }
    }

    if(empty(trim($_POST["password"]))){
        $password_err = "<br>[insira uma senha!]<br>";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "<br>[a senha deve ter pelo menos 6 caracteres!]<br>";
    } else{
        $password = trim($_POST["password"]);
    }

    if(empty($username_err) && empty($password_err) && empty($confirm_password_err)){
        $sql = "INSERT INTO usuario (username, password) VALUES (:username, :password)";
    
        if($stmt = $pdo->prepare($sql)){
            $stmt->bindParam(":username", $param_username, PDO::PARAM_STR);
            $stmt->bindParam(":password", $param_password, PDO::PARAM_STR);
            
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            
            if($stmt->execute()){
                header("location: login.php");
            } else{
                echo "Ops! Algo deu errado. Por favor, tente novamente mais tarde.";
            }
            unset($stmt);
        }
    }
    unset($pdo);
}
?>
 
<!DOCTYPE html>
<head>
    <title>Cadastro</title>
    <link rel="stylesheet" href="estilo.css">
</head>
<body>
    <div class="corpo">
        <h2>Cadastro</h2>
        preencha este formulário para criar uma conta
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post"><br>
            
                
                Nome do usuário: <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <?php echo $username_err; ?><br>
                
                Senha: <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>"><br>
                <?php echo $password_err; ?>
                            Já tem uma conta? <a href="login.php">Entre aqui</a><br><br>
                <input type="submit" name="btcriar" value="Criar Conta"><br>

        </form>
    </div>    
</body>
</html>