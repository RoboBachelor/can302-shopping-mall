<?php
session_start();
date_default_timezone_set('Asia/Shanghai');
require_once("dbcontroller.php");
$db_handle = new DBController();

if (isset($_GET["id"])) {
    $id = $_GET["id"];
} else if (isset($_SESSION["userid"])) {
    $id = $_SESSION["userid"];
} else {
    $id = 0;
}

$actionResponse = "";

if (!isset($_SESSION["userid"])) {
    $actionResponse = "<b>You are not logged in! You have to <a href='login.php'><u>log in</u></a> before any operations.</b>";
    unset($_GET["action"]);
}

if (isset($_GET["action"])) {
    switch ($_GET["action"]) {
        case "remove":
            if ($id == 0) {
                break;
            }
            $removedUser = $db_handle->runQuery("SELECT * FROM user WHERE id='" . $id . "'")[0];
            $db_handle->runQuery("DELETE FROM `user` WHERE `id` = " . $id . ";");
            $actionResponse = date("H:i:s") . ": Remove user <i>" . $removedUser["name"] . "</i> (id:" . $id . ") OK!";

            // Logout if current user was removed
            if ($_SESSION["userid"] == $id) {
                $_SESSION = array();
            }
            $id = 0;
            break;
        case "save":
            if ($id == 0) {
                break;
            }
            $db_handle->runQuery("UPDATE `user` SET `disp_name`='" . $_POST["user-disp-name"] . "', `image`='" . $_POST["user-image"] . "', `title`='" . $_POST["user-title"] . "',
             `role`='" . $_POST["user-role"] . "', `address`='" . $_POST["user-address"] . "', `tel`='" . $_POST["user-tel"] . "' WHERE `id` = " . $id . ";");
            $actionResponse = date("H:i:s") . ": Save user <i>" . $_POST["user-name"] . "</i> (id:" . $id . ") OK!";
            break;
    }
}

$user = $db_handle->runQuery("SELECT * FROM user WHERE id='" . $id . "'")[0];
$allUser = $db_handle->runQuery("SELECT * FROM user WHERE id != 0");

?>
<HTML>

<?php include("heading.php"); ?>
<BODY>

<div class="wrapper">

    <?php include("sidebar.php"); ?>

    <div id="content">
        <div id="product-grid" style="overflow: hidden;">
            <div class="txt-heading">Edit user</div>
            <div class="product-detail">
                <form method="post">
                    <div class="edit-section" style="overflow: hidden;">
                        <div>
                            <div style="display: inline-block; width: 65%; box-sizing: content-box">

                                <label for="user-name">Username:</label><br>
                                <input type="text" id="user-name" name="user-name" style="width: 100%"
                                       readonly="readonly" value="<?php echo $user["name"]; ?>"><br><br>

                                <div>
                                    <div style="width: 50%; display: inline-block; box-sizing: content-box">
                                        <label for="user-title">Title (Mr. Ms. Dr. etc.):</label><br>
                                        <input type="text" id="user-title" name="user-title" style="width: 90%;"
                                               value="<?php echo $user["title"]; ?>"><br><br>
                                    </div>

                                    <div style="width: 48%; display: inline-block; box-sizing: content-box">
                                        <label for="user-disp-name">Displayed name:</label><br>
                                        <input type="text" id="user-disp-name" name="user-disp-name" style="width: 100%;"
                                               value="<?php echo $user["disp_name"]; ?>"><br><br>
                                    </div>
                                </div>
                                <div>
                                    <label for="user-role">Role:</label><br>
                                    <div style="width: 50%; display: inline-block;">
                                        <select id="user-role" name="user-role" style="width: 50%">
                                            <option value="admin">admin</option>
                                            <option value="owner">owner</option>
                                            <option value="customer">customer</option>
                                        </select><br><br>
                                    </div>
                                    <script>
                                        document.getElementById("user-role").value = "<?php echo $user["role"] ?>"
                                    </script>
                                </div>

                            </div>

                            <div style="display: inline-block; float: right">
                                <img class="user-image-detail" src="<?php echo $user["image"]; ?>">
                            </div>

                            <br>
                            <label for="user-address">Address:</label><br>
                            <textarea id="user-address" name="user-address" rows="5"
                                      style="width: 100%"><?php echo $user["address"]; ?></textarea><br><br>


                            <label for="user-tel">Telephone:</label><br>
                            <input type="text" id="user-tel" name="user-tel" style="width: 60%"
                                   value="<?php echo $user["tel"]; ?>"><br><br>

                            <label for="user-image">Image path:</label><br>
                            <input type="text" id="user-image" name="user-image" style="width: 100%"
                                   value="<?php echo $user["image"]; ?>"><br><br>

                            <div style="display: inline-block;">
                                <p><?php echo $actionResponse; ?></p>
                            </div>

                            <div class="float-right" style="display: inline-block;">
                                <input type="submit" value="Save"
                                       formaction="user.php?action=save&id=<?php echo $id; ?>" class="button-save"/>
                                <input type="submit" value="Delete"
                                       formaction="user.php?action=remove&id=<?php echo $id; ?>"
                                       class="button-delete"/>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <div id="shopping-cart">
            <div class="txt-heading">
                <h3>All users</h3>
            </div>
            <a style="margin: 10px 0;" class="button-delete" href="index.php?action=empty">Do not click me</a>
            <table class="tbl-cart" cellpadding="10" cellspacing="1">
                <tbody>
                <tr>
                    <th style="text-align:left; width: 1%"></th>
                    <th style="text-align:left;">UID</th>
                    <th style="text-align:left; width: 10%">Username</th>
                    <th style="text-align:right; width: 10%">Title&nbsp;</th>
                    <th style="text-align:left;">Displayed Name</th>
                    <th style="text-align:right; width: 10%">Role</th>
                    <th style="text-align:right; width: 16%">Telephone</th>
                    <th style="text-align:center; width: 6%">Edit</th>
                    <th style="text-align:center; width: 6%">Remove</th>
                </tr>
                <?php
                foreach ($allUser as $curUser) {
                    ?>
                    <tr>
                        <td></td>
                        <td><?php echo $curUser["id"]; ?></td>
                        <td><img src="<?php echo $curUser["image"]; ?>"
                                 class="cart-item-image"/><?php echo $curUser["name"]; ?></td>
                        <td style="text-align:right;"><?php echo $curUser["title"] . "&nbsp;"; ?></td>
                        <td><?php echo $curUser["disp_name"]; ?></td>
                        <td style="text-align:right;"><?php echo $curUser["role"]; ?></td>
                        <td style="text-align:right;"><?php echo $curUser["tel"]; ?></td>
                        <td style="text-align:center;">
                            <a href="user.php?id=<?php echo $curUser["id"]; ?>" class="btnRemoveAction">
                                <i class="material-icons" style="font-size: 20px;">edit</i>
                            </a>
                        </td>
                        <td style="text-align:center;">
                            <a href="user.php?action=remove&id=<?php echo $curUser["id"]; ?>" class="btnRemoveAction">
                                <i class="material-icons" style="color: #d9534f; font-size: 20px;">delete</i>
                            </a>
                        </td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</BODY>
</HTML>