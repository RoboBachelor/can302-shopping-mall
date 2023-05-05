<?php
session_start();
require_once("dbcontroller.php");
$db_handle = new DBController();

$actionResponse = "";

if (!empty($_GET["action"])) {
    switch ($_GET["action"]) {
        case "add":
            if (!empty($_POST["quantity"])) {
                $productByID = $db_handle->runQuery("SELECT * FROM product WHERE id='" . $_GET["id"] . "'")[0];
                $id = $productByID["id"];

                if (isset($_SESSION["cart_item"][$id])) {
                    $_SESSION["cart_item"][$id]["quantity"] += $_POST["quantity"];
                } else {
                    $_SESSION["cart_item"][$id] = $productByID;
                    $_SESSION["cart_item"][$id]["quantity"] = $_POST["quantity"];
                }
            }
            break;
        case "remove":
            if(isset($_SESSION["cart_item"][$_GET["id"]])){
                unset($_SESSION["cart_item"][$_GET["id"]]);
            }
            break;
        case "empty":
            unset($_SESSION["cart_item"]);
            break;
        case "submit":
            if (!isset($_SESSION["userid"])) {
                $actionResponse = "<b>You have to <a href='login.php'><u>log in</u></a> before submitting your order..</b>";
                break;
            }
            $user = $db_handle->runQuery("SELECT * FROM user WHERE id='" . $_SESSION["userid"] . "'")[0];
            $successCnt = 0;
            foreach ($_SESSION["cart_item"] as $item_id => $item) {
                $db_handle->runQuery("INSERT INTO `orders`(`time`, `product_id`, `product_name`, `product_price`, `product_owner`, `quantity`, `customer_id`, `customer_name`, `address`, `tel`)"
                    ."VALUES (now(),".$item_id.",'".$item["name"]."',".$item["price"].",".$item["owner"].",".$item["quantity"].",".$user["id"].",'".$user["title"] . " " . $user["disp_name"]."','".$user["address"]."','".$user["tel"]."');");
                ++$successCnt;
            }
            $actionResponse = "<b>".$successCnt." order(s) submitted! Your products are on the way!</b>";
            $_SESSION["cart_item"] = array();
            break;
    }
}
?>
<HTML>
<HEAD>
    <?php include ("heading.php"); ?>
</HEAD>
<BODY>

<div class="wrapper">
    <?php include ("sidebar.php"); ?>
    <div id="content">

        <div id="shopping-cart" style="overflow: hidden;">
            <div class="txt-heading">Shopping Cart</div>
            <?php
            if (isset($_SESSION["cart_item"])) {
                $total_quantity = 0;
                $total_price = 0;
                ?>
                <table class="tbl-cart" cellpadding="10" cellspacing="1">
                    <tbody>
                    <tr>
                        <th style="text-align:left;">Name</th>
                        <th style="text-align:left;">ID</th>
                        <th style="text-align:right;" width="5%">Quantity</th>
                        <th style="text-align:right;" width="10%">Unit Price</th>
                        <th style="text-align:right;" width="10%">Price</th>
                        <th style="text-align:center;" width="10%">Remove</th>
                    </tr>
                    <?php
                    foreach ($_SESSION["cart_item"] as $item_id => $item) {
                        $item_price = $item["quantity"] * $item["price"];
                        ?>
                        <tr>
                            <td><img src="<?php echo $item["image"]; ?>"
                                     class="cart-item-image"/><?php echo $item["name"]; ?></td>
                            <td><?php echo $item_id; ?></td>
                            <td style="text-align:right;"><?php echo $item["quantity"]; ?></td>
                            <td style="text-align:right;"><?php echo "$ " . $item["price"]; ?></td>
                            <td style="text-align:right;"><?php echo "$ " . number_format($item_price, 2); ?></td>
                            <td style="text-align:center;">
                                <a href="index.php?action=remove&id=<?php echo $item_id; ?>" class="btnRemoveAction">
                                    <i class="material-icons" style="color: #d9534f; font-size: 20px;">delete</i>
                                </a>
                            </td>
                        </tr>
                        <?php
                        $total_quantity += $item["quantity"];
                        $total_price += ($item["price"] * $item["quantity"]);
                    }
                    ?>

                    <tr>
                        <td colspan="2" align="right">Total:</td>
                        <td align="right"><?php echo $total_quantity; ?></td>
                        <td align="right" colspan="2">
                            <strong><?php echo "$ " . number_format($total_price, 2); ?></strong></td>
                        <td></td>
                    </tr>
                    </tbody>
                </table>
                <?php
            } else {
                ?>
                <div class="no-records">Your Cart is Empty</div>
                <?php
            }
            ?>

            <div style="margin: 10px 0; overflow: hidden;">
                <p><?php echo $actionResponse; ?></p>
                <a class="button-save" href="index.php?action=submit">Check Out</a>
                <a class="button-delete" href="index.php?action=empty">Empty Cart</a>
            </div>
        </div>

        <div id="product-grid">
            <div class="txt-heading">Products</div>
            <?php
            $product_array = $db_handle->runQuery("SELECT * FROM product ORDER BY id ASC");
            if (!empty($product_array)) {
                foreach ($product_array as $product) {
                    if ($product["id"] == 0) {
                        continue;
                    }
                    ?>
                    <div class="product-item">
                        <div class="product-image"><img src="<?php echo $product["image"]; ?>"></div>
                        <div class="product-tile-footer">
                            <div class="float-right">
                                <a href="product.php?id=<?php echo $product["id"]; ?>">
                                    <i class="material-icons" style="color: #CA6F1E; font-size: 30px;">edit_note</i>
                                </a>
                            </div>
                            <div class="product-title"><?php echo $product["name"]; ?></div>

                            <div style="height: 46px; vertical-align: bottom">
                                <div class="product-price"><?php echo "$" . $product["price"]; ?></div>
                                <div class="float-right">
                                    <form name="addCartForm<?php echo $product["id"]; ?>" method="post" action="index.php?action=add&id=<?php echo $product["id"]; ?>">
                                        <input type="text" class="product-quantity"
                                               name="quantity" value="1" size="2"/>
<!--                                        <input type="submit" value="Add to Cart" class="button-general"/>-->
                                        <a href="javascript:document.addCartForm<?php echo $product["id"]; ?>.submit();">
                                            <i class="material-icons" style="color: #404A84; font-size: 24px; vertical-align: bottom;">add_shopping_cart</i>
                                        </a>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</div>
</BODY>
</HTML>