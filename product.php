<?php
session_start();
date_default_timezone_set('Asia/Shanghai');
require_once("dbcontroller.php");
$db_handle = new DBController();

if (isset($_GET["id"])){
    $id = $_GET["id"];
} else{
    $id = 0;
}

$actionResponse = "";

if (!isset($_SESSION["userid"])) {
    $actionResponse = "<b>You are not logged in! You have to <a href='login.php'><u>log in</u></a> before any operations.</b>";
    unset($_GET["action"]);
} else if ($_SESSION["user-role"] != "admin" && $_SESSION["user-role"] != "owner") {
    $actionResponse = "<b>You don't have permissions to manage products!</b>";
    unset($_GET["action"]);
}

if (isset($_GET["action"])){
    switch ($_GET["action"]) {
        case "add":
            $db_handle->runQuery("INSERT INTO `product`(`name`, `image`, `price`, `supply`, `cat_id`, `owner`, `description`)"
                ."VALUES ('".$_POST["prod-title"]."','".$_POST["prod-image"]."',".$_POST["prod-price"].",".$_POST["prod-quan"].",
                                        ".$_POST["prod-cat"].",".$_SESSION["userid"].",'".$_POST["prod-description"]."');");
            $id = $db_handle->getInsertedID();
            $actionResponse = date("H:i:s") . ": Add <i>" . $_POST["prod-title"] ."</i> (id:".$id.") OK!";
            break;
        case "remove":
            if ($id == 0){
                break;
            }
            $removedProduct = $db_handle->runQuery("SELECT * FROM product WHERE id='" . $id . "'")[0];
            $db_handle->runQuery("DELETE FROM `product` WHERE `id` = " . $id . ";");
            $actionResponse = date("H:i:s") . ": Remove <i>" . $removedProduct["name"] ."</i> (id:".$id.") OK!";
            $id = 0;
            break;
        case "save":
            if ($id == 0){
                break;
            }
            $db_handle->runQuery("UPDATE `product` SET `name`='".$_POST["prod-title"]."', `image`='".$_POST["prod-image"]."', `price`=".$_POST["prod-price"].",
             `supply`=".$_POST["prod-quan"].", `cat_id`=".$_POST["prod-cat"].", `owner`=".$_SESSION["userid"].", `description`='".$_POST["prod-description"]."'".
            "WHERE `id` = ". $id .";");
            $actionResponse = date("H:i:s") . ": Save <i>" . $_POST["prod-title"] ."</i> (id:".$id.") OK!";
            break;
    }
}

$productByID = $db_handle->runQuery("SELECT * FROM product WHERE id='" . $id . "'")[0];
$categories = $db_handle->runQuery("SELECT * FROM category WHERE owner='" . 1 . "'");

?>
<HTML>

<?php include ("heading.php"); ?>
<BODY>

<div class="wrapper">

    <?php include ("sidebar.php"); ?>

    <div id="content">

        <div id="product-grid">
            <div class="txt-heading">
                <h3>Edit Products</h3>
            </div>

            <form method="post">
                <div class="product-detail">
                    <div class="product-image-detail"><img src="<?php echo $productByID["image"]; ?>"></div>
                    <div class="edit-section">

                        <label for="prod-title">Product title:</label><br>
                        <input type="text" id="prod-title" name="prod-title" style="width: 100%" value="<?php echo $productByID["name"]; ?>"><br><br>

                        <div>
                            <div style="width: 50%; display: inline-block;">
                                <label for="prod-price">Unit price:</label><br>
                                <input type="text" id="prod-price" name="prod-price" style="width: 100px;" value="<?php echo $productByID["price"]; ?>"><br><br>
                            </div>

                            <div style="display: inline-block;">
                                <label for="prod-quan">Quantity:</label><br>
                                <input type="text" id="prod-quan" name="prod-quan" style="width: 100px;" value="<?php echo $productByID["supply"]; ?>"><br><br>
                            </div>
                        </div>

                        <div>
                            <label for="prod-cat">Choose a category:</label><br>
                            <div style="width: 50%; display: inline-block;">
                                <select id="prod-cat" name="prod-cat" style="width: 100%">
                                    <option value="0"></option>
                                    <?php
                                    foreach ($categories as $category) {
                                        ?>
                                        <option value="<?php echo $category["id"]; ?>" <?php echo $productByID["cat_id"] == $category["id"] ? "selected" : "" ?>><?php echo $category["name"]; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select><br><br>
                            </div>

                            <div style="display: inline-block; vertical-align:top;">
                                <a href="category.php" target="_blank"><u>[Edit categories]</u></a>
                            </div>
                        </div>

                        <label for="prod-description">Product description:</label><br>
                        <textarea id="prod-description" name="prod-description" rows="5" style="width: 100%"><?php echo $productByID["description"]; ?></textarea><br><br>

                        <label for="prod-image">Image path:</label><br>
                        <input type="text" id="prod-image" name="prod-image" style="width: 100%" value="<?php echo $productByID["image"]; ?>"><br><br>

                        <div style="display: inline-block;">
                            <p><?php echo $actionResponse; ?></p>
                        </div>

                        <div class="float-right" style="display: inline-block;">
                            <input type="submit" value="Save" formaction="product.php?action=save&id=<?php echo $id; ?>" class="button-save"/>
                            <input type="submit" value="Delete" formaction="product.php?action=remove&id=<?php echo $id; ?>" class="button-delete"/>
                            <input type="submit" value="Add & save" formaction="product.php?action=add" class="button-general"/>
                        </div>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>
</BODY>
</HTML>