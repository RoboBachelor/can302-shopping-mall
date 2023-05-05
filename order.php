<?php
session_start();
date_default_timezone_set('Asia/Shanghai');
require_once("dbcontroller.php");
$db_handle = new DBController();

if (isset($_GET["id"])) {
    $id = $_GET["id"];
}

$actionResponse = "";

if (!isset($_SESSION["userid"])) {
    header("Location: login.php");
    exit;
}

if (isset($_GET["action"])) {
    switch ($_GET["action"]) {
        case "remove":
            $removedOrder = $db_handle->runQuery("SELECT * FROM orders WHERE id='" . $id . "'")[0];
            $db_handle->runQuery("DELETE FROM `orders` WHERE `id` = " . $id . ";");
            $actionResponse = date("H:i:s") . ": Remove order for product <i>" . $removedOrder["product_name"] . "</i> (id:" . $id . ") OK!";
            unset($id);
            break;
        case "save":
            $db_handle->runQuery("UPDATE `orders` SET `product_price`=" . $_POST["product-price"] . ", `quantity`=" . $_POST["quantity"] . ", `customer_name`='" . $_POST["customer-name"] . "',
             `address`='" . $_POST["address"] . "', `tel`='" . $_POST["tel"] . "' WHERE `id` = " . $id . ";");
            $actionResponse = date("H:i:s") . ": Save order for product <i>" . $_POST["product-name"] . "</i> (id:" . $id . ") OK!";
            break;
    }
}

if (isset($id)) {
    $curOrder = $db_handle->runQuery("SELECT * FROM orders WHERE id = " .$id . ";")[0];
    $curCustomer = $db_handle->runQuery("SELECT (`image`) FROM user WHERE id='" . $curOrder["customer_id"] . "'")[0];
}
$allOrders = $db_handle->runQuery("SELECT * FROM orders WHERE product_owner = " .$_SESSION["userid"]. " OR customer_id = " .$_SESSION["userid"] . ";");

?>
<HTML>

<?php include("heading.php"); ?>
<BODY>

<div class="wrapper">

    <?php include("sidebar.php"); ?>

    <div id="content">

        <?php
        if (isset($id)) {
        ?>

        <div id="product-grid" style="overflow: hidden;">
                <div class="txt-heading">Edit order</div>
                <div class="product-detail">
                    <form method="post">
                        <div class="edit-section" style="overflow: hidden;">
                            <div>
                                <div style="display: inline-block; width: 65%; box-sizing: content-box">

                                    <label for="product-name">Product name:</label><br>
                                    <input type="text" id="product-name" name="product-name" style="width: 100%"
                                           readonly="readonly" value="<?php echo $curOrder["product_name"]; ?>"><br><br>

                                    <div>
                                        <div style="width: 50%; display: inline-block; box-sizing: content-box">
                                            <label for="product-price">Price</label><br>
                                            <input type="text" id="product-price" name="product-price" style="width: 90%;"
                                                   value="<?php echo $curOrder["product_price"]; ?>"><br><br>
                                        </div>

                                        <div style="width: 48%; display: inline-block; box-sizing: content-box">
                                            <label for="quantity">Quantity:</label><br>
                                            <input type="text" id="quantity" name="quantity" style="width: 100%;"
                                                   value="<?php echo $curOrder["quantity"]; ?>"><br><br>
                                        </div>
                                    </div>

                                    <div>
                                        <div style="width: 50%; display: inline-block; box-sizing: content-box">
                                            <label for="customer-name">Customer:</label><br>
                                            <input type="text" id="customer-name" name="customer-name" style="width: 90%;"
                                                   value="<?php echo $curOrder["customer_name"]; ?>"><br><br>
                                        </div>

                                        <div style="width: 48%; display: inline-block; box-sizing: content-box">
                                            <label for="tel">Telephone:</label><br>
                                            <input type="text" id="tel" name="tel" style="width: 100%;"
                                                   value="<?php echo $curOrder["tel"]; ?>"><br><br>
                                        </div>
                                    </div>

                                    <div>

                                        <div style="width: 50%; display: inline-block; box-sizing: content-box">
                                            <label for="order-status">Order status:</label><br>
                                            <select id="order-status" name="order-status" style="width: 90%">
                                                <option value="not-implemented">Order status not implemented</option>
                                            </select><br><br>
                                            <script>
                                                document.getElementById("user-role").value = "<?php echo "not-implemented" ?>"
                                            </script>
                                        </div>

                                        <div style="width: 48%; display: inline-block; box-sizing: content-box">
                                            <label for="user-tel">Create time:</label><br>
                                            <input type="text" id="user-tel" name="user-tel" style="width: 100%"
                                                   readonly="readonly" value="<?php echo $curOrder["time"]; ?>"><br><br>
                                        </div>

                                    </div>
                                </div>

                                <div style="display: inline-block; float: right">
                                    <img class="user-image-detail" src="<?php echo $curCustomer["image"]; ?>">
                                </div>

                                <br>
                                <label for="address">Address:</label><br>
                                <textarea id="address" name="address" rows="5"
                                          style="width: 100%"><?php echo $curOrder["address"]; ?></textarea><br><br>


                                <div style="display: inline-block;">
                                    <p><?php echo $actionResponse; ?></p>
                                </div>

                                <div class="float-right" style="display: inline-block;">
                                    <input type="submit" value="Save"
                                           formaction="order.php?action=save&id=<?php echo $id; ?>" class="button-save"/>
                                    <input type="submit" value="Delete"
                                           formaction="order.php?action=remove&id=<?php echo $id; ?>"
                                           class="button-delete"/>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <?php
        }
        ?>

        <div id="shopping-cart">
            <div class="txt-heading">
                <h3>All orders</h3>
            </div>
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