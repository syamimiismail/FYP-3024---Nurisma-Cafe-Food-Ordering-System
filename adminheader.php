<header class="header">
    <div class="flex">
        <a href="ownermainpage.php"><img src="logo.png" alt=""></a>
        <nav class="navbar">
            <a href="ownermainpage.php">Main</a>
            <a href="admin.php">Menu</a>
            <a href="adminorder.php">Order</a>
            <a href="adminreport.php">Report</a>
            <a href="adminmessage.php">Message</a>
        </nav>
        <div class="icons">
            <!-- <input type="text" id="search" placeholder="Search">
            <label for="search"><i class="fas fa-search"></i></label> -->
            <!-- <i class="fa-solid fa-user" id="user-btn"></i> -->
            <form action="" method="post">
                <button type="submit" Name="logout" class="logout-btn">Log Out</button>  
            </form>
        </div>
        <!-- <div class="profile-detail">
            <?php
            $select_profile = $conn->prepare("SELECT * FROM `user_form` WHERE id = ?");
            $select_profile->execute([$id]);

            if($select_profile->rowCount() > 0){
                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
                ?>
                <div class="profile">
                    <i class="fa-solid fa-user"></i>
                    <i class="fa-solid fa-bars" id="menu-btn" style="fonts-size: 2rem;"></i>
                    <p><?= $fetch_profile['name'];?></p>
                </div>
                <div class="flex-btn">
                    <a href="ownerprofile.php">Profile</a>
                    <a href="logout.php" onclick="return confirm(Confirm to logout?)" class="btn">Logout</a>
                </div>
                <?php
            }
            ?>
        </div> -->
    </div>
</header>