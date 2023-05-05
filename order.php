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
    header("Location: login.php");
    exit;
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

// $user = $db_handle->runQuery("SELECT * FROM user WHERE id='" . $id . "'")[0];
$allOrders = $db_handle->runQuery("SELECT * FROM orders WHERE product_owner = " .$_SESSION["userid"]. " OR customer_id = " .$_SESSION["userid"] . ";");

?>
<HTML>

<?php include("heading.php"); ?>
<BODY>

<div class="wrapper">

    <?php include("sidebar.php"); ?>

    <div id="content">
        <div id="shopping-cart">
            <div class="txt-heading">
                <h3>All orders</h3>
            </div>
            <a style="margin: 10px 0;" class="button-delete" href="index.php?action=empty">Do not click me</a>
            <table class="tbl-cart" cellpadding="10" cellspacing="1">
                <tbody>
                <tr>
                    <th style="text-align:left; width: 1%"></th>
                    <th style="text-align:left; width: 14%">Create time</th>
                    <th style="text-align:left; width: 15%">Username</th>
                    <th style="text-align:left; width: 9%">Order tel</th>
                    <th style="text-align:left; width: 12%">Order address</th>
                    <th style="text-align:left;%">Product name</th>
                    <th style="text-align:right; width: 6%">Unit price</th>
                    <th style="text-align:left; width: 5%">&nbsp;Num</th>
                    <th style="text-align:center; width: 4%">Edit</th>
                    <th style="text-align:center; width: 4%">Remove</th>
                </tr>
                <?php
                foreach ($allOrders  as $order) {
                    $user = $db_handle->runQuery("SELECT (`image`) FROM user WHERE id='" . $order["customer_id"] . "'")[0];
                    ?>
                    <tr>
                        <td></td>
                        <td><?php echo $order["time"]; ?></td>
                        <td><img src="<?php echo $user["image"]; ?>"
                                 class="cart-item-image"/><?php echo $order["customer_name"]; ?></td>
                        <td><?php echo $order["tel"]; ?></td>
                        <td><?php echo substr($order["address"], 0, 15)."..."; ?></td>
                        <td style="text-align:left;"><?php echo $order["product_name"]; ?></td>
                        <td style="text-align:right;"><?php echo $order["product_price"]; ?></td>
                        <td style="text-align:left;"><?php echo "&nbsp;×" . $order["quantity"]; ?></td>
                        <td style="text-align:center;">
                            <a href="order.php?id=<?php echo $order["id"]; ?>" class="btnRemoveAction">
                                <i class="material-icons" style="font-size: 20px;">edit</i>
                            </a>
                        </td>
                        <td style="text-align:center;">
                            <a href="order.php?action=remove&id=<?php echo $order["id"]; ?>" class="btnRemoveAction">
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