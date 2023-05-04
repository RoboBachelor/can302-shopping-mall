<?php
session_start();
date_default_timezone_set('Asia/Shanghai');
require_once("dbcontroller.php");
$db_handle = new DBController();

$actionResponse = "";

if (isset($_GET["action"])){
    switch ($_GET["action"]) {
        case "logout":
            $_SESSION = array();
            $actionResponse = date("H:i:s") . ": Logged out!";
            break;
        case "register":
            $user = $db_handle->runQuery("SELECT * FROM user WHERE name='" . $_POST["user-name"] . "'");

            if ($user) {
                $actionResponse = "User already exists!";
                break;
            }

            $db_handle->runQuery("INSERT INTO `user`(`name`, `pass`)"
                ."VALUES ('".$_POST["user-name"]."','".password_hash($_POST['user-pass'], PASSWORD_DEFAULT)."');");
            $id = $db_handle->getInsertedID();
            $actionResponse = date("H:i:s") . ": Add user <i>" . $_POST["user-name"] ."</i> (uid:".$id.") OK!";

            break;

        case "login":
            $user = $db_handle->runQuery("SELECT * FROM user WHERE name='" . $_POST["user-name"] . "'");
            if (!$user) {

                $actionResponse = "User does not exist!";
                break;
            }

            $user = $user[0];
            if (!password_verify($_POST["user-pass"], $user["pass"])) {
                $actionResponse = "Password incorrect!";
                break;
            }

            $actionResponse = date("H:i:s") . ": Welcome <i>" . $_POST["user-name"] ."</i> (uid:".$user["id"].") OK!";
            $_SESSION["userid"] = $user["id"];
            break;
    }
}

?>


<HTML>
<?php include ("heading.php"); ?>
<BODY>

<div class="wrapper">

    <?php include ("sidebar.php"); ?>

    <div id="content">

        <div id="product-grid">
            <div class="txt-heading">
                <h3>Login</h3>
            </div>

            <div class="product-detail" style="width: 500px">
                <div class="edit-section">

                    <form method="post">
                        <label for="user-name">Username:</label><br>
                        <input type="text" id="user-name" name="user-name" style="width: 100%"><br><br>

                        <label for="user-pass">Password:</label><br>
                        <input type="password" id="user-pass" name="user-pass" style="width: 100%"><br><br>

                        <div style="display: inline-block;">
                            <p><?php echo $actionResponse; ?></p>
                        </div>

                        <div class="float-right" style="display: inline-block;">
                            <input type="submit" value="Login" formaction="login.php?action=login" class="button-save"/>
                            <input type="submit" value="Register" formaction="login.php?action=register" class="button-delete"/>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</BODY>
</HTML>