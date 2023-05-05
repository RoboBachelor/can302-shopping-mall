
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
            <li><a href="login.php?action=logout"><i class="material-icons sidebar-icon" style="">logout</i> Log out</a></li>
            <?php
        } else {
            ?>
            <li><a href="login.php"><i class="material-icons sidebar-icon" style="">login</i> Log in</a></li>
            <?php
        }
        ?>
    </ul>

    <ul class="list-unstyled components">
        <li><a href="user.php"><i class="material-icons sidebar-icon">group</i> Account</a></li>
        <li><a href="index.php"><i class="material-icons sidebar-icon">local_mall</i> Products</a></li>
        <li><a href="category.php"><i class="material-icons sidebar-icon">category</i> Categories</a></li>
        <li><a href="order.php"><i class="material-icons sidebar-icon">receipt</i> Orders</a></li>
    </ul>

    <ul class="list-unstyled components">
        <li><a href="https://github.com/RoboBachelor/can302-shopping-mall">
                <i class="material-icons sidebar-icon" style="">code</i> Source code
            </a></li>
    </ul>

    <ul class="list-unstyled CTAs">
        <li><a href="index.php" class="download">Back to HOME</a></li>
    </ul>

</nav>