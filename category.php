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
            $db_handle->runQuery("INSERT INTO `category`(`name`, `description`, `owner`)"
                ."VALUES ('".$_POST["cat-title"]."','".$_POST["cat-description"]."', ".$_SESSION["userid"].");");
            $id = $db_handle->getInsertedID();
            $actionResponse = date("H:i:s") . ": Add <i>" . $_POST["cat-title"] ."</i> (id:".$id.") OK!";
            break;
        case "remove":
            if ($id == 0){
                break;
            }
            $removedCat = $db_handle->runQuery("SELECT * FROM category WHERE id='" . $id . "'")[0];
            $db_handle->runQuery("DELETE FROM `category` WHERE `id` = " . $id . ";");
            $actionResponse = date("H:i:s") . ": Remove <i>" . $removedCat["name"] ."</i> (id:".$id.") OK!";
            $id = 0;
            break;
        case "save":
            if ($id == 0){
                break;
            }
            $db_handle->runQuery("UPDATE `category` SET `name`='".$_POST["cat-title"]."', `description`='".$_POST["cat-description"]."'".
            "WHERE `id` = ". $id .";");
            $actionResponse = date("H:i:s") . ": Save <i>" . $_POST["cat-title"] ."</i> (id:".$id.") OK!";
            break;
    }
}

// $categories = $db_handle->runQuery("SELECT * FROM category WHERE owner='" . $_SESSION["userid"] . "'");
$categories = $db_handle->runQuery("SELECT * FROM category WHERE 1;");

?>

<script>
    function onChangeSelection(val) {
        window.location.href="category.php?id=" + val;
    }
</script>

<HTML>
<?php include ("heading.php"); ?>
<BODY>

<div class="wrapper">
    <?php include ("sidebar.php"); ?>

    <div id="content">

        <div id="product-grid">
            <div class="txt-heading">Edit Categories</div>

            <div class="product-detail">
                <div class="edit-section">

                    <div>
                        <label for="prod-cat">Choose a category:</label><br>
                        <div style="width: 100%; display: inline-block;">
                            <select onchange=onChangeSelection(this.value) id="prod-cat" name="prod-cat" style="width: 100%">
                                <?php
                                    foreach ($categories as $category) {
                                        if ($category["id"] == $id) {
                                            $curCategory = $category;
                                        }
                                ?>
                                <option value="<?php echo $category["id"]; ?>" <?php echo $id == $category["id"] ? "selected" : "" ?>><?php echo $category["name"]; ?></option>
                                <?php
                                    }
                                ?>
                            </select><br><br>
                        </div>
                    </div>

                    <form method="post">
                        <label for="cat-title">Category name:</label><br>
                        <input type="text" id="cat-title" name="cat-title" style="width: 100%" value="<?php echo $curCategory["name"]; ?>"><br><br>

                        <label for="cat-description">Category description:</label><br>
                        <textarea id="cat-description" name="cat-description" rows="5" style="width: 100%"><?php echo $curCategory["description"]; ?></textarea><br><br>

                        <div style="display: inline-block;">
                            <p><?php echo $actionResponse; ?></p>
                        </div>

                        <div class="float-right" style="display: inline-block;">
                            <input type="submit" value="Save" formaction="category.php?action=save&id=<?php echo $id; ?>" class="button-save"/>
                            <input type="submit" value="Delete" formaction="category.php?action=remove&id=<?php echo $id; ?>" class="button-delete"/>
                            <input type="submit" value="Add & save" formaction="category.php?action=add" class="button-general"/>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</BODY>
</HTML>