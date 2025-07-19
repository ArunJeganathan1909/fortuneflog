<?php include 'admin/backend/conn.php'; ?>
<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<link rel="stylesheet" href="assets/css/custom-header.css">

<div class="axil-mainmenu">
    <div class="container-fluid">
        <div class="header-navbar">
            <div class="header-brand">
                <a href="index.php" class="logo logo-dark">
                    <img src="assets/images/logo/logo.png" style="height:80px;" alt="Site Logo">
                </a>
                <a href="index.php" class="logo logo-light">
                    <img src="assets/images/logo/logo-light.png" alt="Site Logo">
                </a>
            </div>
            <div class="header-main-nav">
                <nav class="mainmenu-nav">
                    <button class="mobile-close-btn mobile-nav-toggler"><i class="fas fa-times"></i></button>
                    <div class="mobile-nav-brand">
                        <a href="index.php" class="logo">
                            <img src="assets/images/logo/logo.png" alt="Site Logo">
                        </a>
                    </div>
                    <ul class="mainmenu">
                        <li class="dropdown">
                            <a class="dropdown-toggle" href="#" role="button" id="dropdown-header-menu" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="far fa-th-large"></i> Categories
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdown-header-menu">
                                <?php
                                $sqlCat = "SELECT * FROM tbl_categories";
                                $rsCat = $conn->query($sqlCat);

                                if ($rsCat->num_rows > 0) {
                                    while ($rowsCat = $rsCat->fetch_assoc()) {
                                ?>

                                        <li><a class="dropdown-item" href="shop.php?cat_id=<?= $rowsCat['cat_id'] ?>"><?= $rowsCat['cat_name'] ?></a></li>
                                <?php
                                    }
                                }
                                ?>
                            </ul>
                        </li>
                        <li class="<?= $currentPage == 'shop.php' ? 'active' : '' ?>">
                            <a href="shop.php"><i class="far fa-bags-shopping"></i> Shop</a>
                        </li>
                        <li class="<?= $currentPage == 'about-us.php' ? 'active' : '' ?>">
                            <a href="about-us.php"><i class="far fa-info-circle"></i> About</a>
                        </li>
                        <li class="<?= $currentPage == 'contact.php' ? 'active' : '' ?>">
                            <a href="contact.php"><i class="far fa-headset"></i> Support</a>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="header-action">
                <ul class="action-list">
                    <li class="axil-search d-none-laptop">
                        <input type="search" class="placeholder product-search-input" name="search2" id="search2" value="" maxlength="128" placeholder="Search" autocomplete="off">
                        <button type="submit" class="icon wooc-btn-search">
                            <i class="far fa-search"></i>
                        </button>
                    </li>
                    <!-- <li class="axil-search d-none-desktop">
                        <a href="javascript:void(0)" class="header-search-icon" title="Search">
                            <i class="far fa-search"></i>
                        </a>
                    </li> -->
                    <!-- <li class="shopping-cart">
                        <a href="#" class="cart-dropdown-btn">
                            <span class="cart-count">2</span>
                            <i class="far fa-shopping-cart"></i>
                        </a>
                    </li> -->
                    <li class="my-account">
                        <a href="javascript:void(0)">
                            <i class="far fa-user"></i>
                        </a>
                        <div class="my-account-dropdown">
<?php if (isset($_SESSION['user_email'])): ?>
    
                            <ul>
                                <li>
                                    <a href="my-account.php">My Account</a>
                                </li>
                             
</ul>
    <div class="text-center">
        <p>Welcome, <?= htmlspecialchars($_SESSION['user_fullname']); ?></p>
        <a href="admin/logout.php" class="axil-btn btn-bg-primary">Logout</a>
    </div>
<?php else: ?>
    <div class="login-btn">
        <a href="sign-in.php" class="axil-btn btn-bg-primary">Login</a>
    </div>
    <div class="reg-footer text-center">No account yet? <a href="register.php" class="btn-link">REGISTER HERE.</a></div>
<?php endif; ?>
                            
                        </div>
                    </li>
                    <li class="axil-mobile-toggle">
                        <button class="menu-btn mobile-nav-toggler">
                            <i class="flaticon-menu-2"></i>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>