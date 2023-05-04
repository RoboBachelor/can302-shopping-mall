
<nav id="sidebar">
    <div class="sidebar-header">
        <h3>CAN302 Mall</h3>
    </div>

    <ul class="list-unstyled components">
        <?php
        if (isset($_SESSION["userid"])) {
            $userSidebar = $db_handle->runQuery("SELECT * FROM user WHERE id='" . $_SESSION["userid"] . "'")[0];
            ?>
            <li>
                <a href="user.php">
                    <img class="user-image-detail" style="width: 50px;" src="<?php echo $userSidebar["image"]; ?>">
                    <?php echo '&nbsp;&nbsp;'.$userSidebar["disp_name"].''; ?>
                </a>
            </li>
            <li><a href="login.php?action=logout">Log out</a></li>
            <?php
        } else {
            ?>
            <li><a href="login.php">Log in</a></li>
            <?php
        }
        ?>
    </ul>

    <ul class="list-unstyled components">
        <li><a href="user.php">Account</a></li>
        <li><a href="index.php">Products</a></li>
        <li><a href="category.php">Categories</a></li>
        <li><a href="order.php">Orders</a></li>
    </ul>

    <ul class="list-unstyled CTAs">
        <li><a href="index.php" class="download">Back to HOME</a></li>
    </ul>

</nav>