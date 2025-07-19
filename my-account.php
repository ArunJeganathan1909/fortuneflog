<?php
include 'layouts/header.php';

if (!isset($_SESSION['user_email'])) {
     header("Location: /login.php");
    exit;
}

$user_email = $_SESSION['user_email'];

$stmt = $conn->prepare("SELECT * FROM tbl_customers WHERE email = ?");
$stmt->bind_param("s", $user_email);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

if (!empty($_SESSION['wrong_password'])) {
    echo '<div class="alert alert-danger">' . $_SESSION['wrong_password'] . '</div>';
    unset($_SESSION['wrong_password']);
}

if (!empty($_SESSION['success_message'])) {
    echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
    unset($_SESSION['success_message']);
}

if (isset($_SESSION['message'])) {
    $msg_type = $_SESSION['msg_type'] ?? 'info';
    echo '<div class="alert alert-' . htmlspecialchars($msg_type) . '" role="alert">'
        . htmlspecialchars($_SESSION['message']) . '</div>';

    unset($_SESSION['message']);
    unset($_SESSION['msg_type']);
}
?>

<link rel="stylesheet" href="assets/css/add-product.css">


<main class="main-wrapper">
    <!-- Start Breadcrumb Area  -->

    <!-- End Breadcrumb Area  -->

    <!-- Start My Account Area  -->
    <div class="axil-dashboard-area axil-section-gap">
        <div class="container">
            <div class="axil-dashboard-warp">
                <div class="axil-dashboard-author">
                    <div class="media">

                        <div class="media-body">
                            <h5 class="title mb-0">Hello Annie</h5>
                            <!-- <span class="joining-date">eTrade Member Since Sep 2020</span> -->
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xl-3 col-md-4">
                        <aside class="axil-dashboard-aside">
                            <nav class="axil-dashboard-nav">
                                <div class="nav nav-tabs" role="tablist">
                                    <a class="nav-item nav-link active" data-bs-toggle="tab" href="#nav-account" role="tab" aria-selected="true"><i class="fas fa-th-large"></i>Dashboard</a>
                                    <a class="nav-item nav-link" data-bs-toggle="tab" href="#nav-add-product" role="tab" aria-selected="false"><i class="fas fa-file-download"></i>Add Product</a>
                                   
                                    <a class="nav-item nav-link" href="admin/logout.php"><i class="fal fa-sign-out"></i>Logout</a>
                                </div>
                            </nav>
                        </aside>
                    </div>
                    <div class="col-xl-9 col-md-8">
                        <div class="tab-content">
                            
                            
                            <div class="tab-pane fade" id="nav-add-product" role="tabpanel">
                                <div class="axil-dashboard-order">
                                    <div class="container card shadow-lg p-4 mt-4">
                                        <div class="text-center mb-4">
                                            <h4 class="modal-title fw-bold ">Add Product</h4>
                                            <p class="text-muted">Fill in the form below to add a new product</p>
                                        </div>

                                        <form enctype="multipart/form-data" method="POST" action="admin/add_product.php">
                                            <!-- Product Title -->
                                            <div class="form-group">
                                                <label for="pTitle" class="form-label fw-semibold">Product Title</label>
                                                <input type="text" name="pTitle" id="pTitle" class="form-control shadow-sm " placeholder="Enter product title" required>
                                            </div>

                                            <div class="form-group">
                                                <label for="pCatogory" class="form-label fw-semibold">Product Category</label>
                                                <select name="pCatogory" id="pCatogory" class="form-control shadow-sm" required>
                                    <?php
                

                                        $sqlCat = "SELECT * FROM tbl_categories";
                                        $rsCat = $conn->query($sqlCat);

                                        if ($rsCat->num_rows > 0) {
                                            while ($rowsCat = $rsCat->fetch_assoc()) {
                                                ?>
                                                <option value="<?= $rowsCat['cat_id'] ?>"><?= htmlspecialchars($rowsCat['cat_name']) ?></option>
                                                <?php
                                            }
                                        }
                                        ?>
                                                </select>
                                            </div>

                                            <!-- Product Images -->
                                            <div class="form-group">
                                                <label class="form-label fw-semibold">Product Images</label>

                                                <!-- Initial Image Input -->
                                                <div id="image-inputs">
                                                    <div class="input-group mb-2">
                                                        <input type="file" name="pImages[]" class="form-control shadow-sm " accept="image/*" required>
                                                    </div>
                                                </div>

                                                <!-- Plus Icon Button -->
                                                <button type="button" id="addMoreImages" class="btn btn-sm">
                                                    <i class="fas fa-plus"></i> Add More Images
                                                </button>

                                                <small class="text-muted d-block mt-1">You can upload multiple images</small>
                                            </div>


                                            <!-- Description -->
                                            <div class="form-group">
                                                <label for="pDescription" class="form-label fw-semibold">Product Description</label>
                                                <textarea name="pDescription" id="pDescription" class="form-control shadow-sm" rows="3" placeholder="Enter product description" required></textarea>
                                            </div>

                                            <!-- Price -->
                                            <div class="form-group">
                                                <label for="pPrice" class="form-label fw-semibold">Product Price ($)</label>
                                                <input type="number" name="pPrice" id="pPrice" class="form-control shadow-sm" placeholder="Enter product price" step="0.01" required>
                                            </div>

                                            <!-- Age -->
                                            <div class="form-group">
                                                <label for="pAge" class="form-label fw-semibold">Product Age (years)</label>
                                                <input type="number" name="pAge" id="pAge" class="form-control shadow-sm" placeholder="Enter product age" required>
                                            </div>

                                            <!-- Type -->
                                            <div class="form-group">
                                                <label for="pType" class="form-label fw-semibold">Product Type</label>
                                                <select name="pType" id="pType" class="form-control shadow-sm" required>
                                                    <option value="Antique">Antique</option>
                                                    <option value="Retro">Retro</option>
                                                </select>
                                            </div>

                                            <!-- Contact Name -->
                                            <div class="form-group">
                                                <label for="pContactName" class="form-label fw-semibold">Contact Name</label>
                                                <input type="text" name="pContactName" id="pContactName" class="form-control shadow-sm" placeholder="Enter contact name" required>
                                            </div>

                                            <!-- Contact Address -->
                                            <div class="form-group">
                                                <label for="pContactAddress" class="form-label fw-semibold">Contact Address</label>
                                                <input type="text" name="pContactAddress" id="pContactAddress" class="form-control shadow-sm" placeholder="Enter contact address" required>
                                            </div>

                                            <!-- Contact Email -->
                                            <div class="form-group">
                                                <label for="pEmail" class="form-label fw-semibold">Contact Email</label>
                                                <input type="email" name="pEmail" id="pEmail" class="form-control shadow-sm" placeholder="example@domain.com" required>
                                            </div>

                                            <!-- Contact Phone -->
                                            <div class="form-group mb-4">
                                                <label for="pPhone" class="form-label fw-semibold">Contact Phone</label>
                                                <input type="text" name="pPhone" id="pPhone" class="form-control shadow-sm" placeholder="+94 77 123 4567" required>
                                            </div>

                                            <!-- Submit -->
                                            <div class="d-grid">
                                                <button type="submit" class="btn btn-primary btn-lg shadow-sm">Add Product</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                       
                            <div class="tab-pane fade show active" id="nav-account" role="tabpanel">
                                <div class="col-lg-9">
                                    <div class="axil-dashboard-account">
                                        <form class="account-details-form" method="POST" action="admin/update_account.php">
                                            <div class="row">
                                                
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label>Full Name</label>
                                                        <input type="text" name="last_name" class="form-control" value="<?= htmlspecialchars($user['fullname'] ?? '') ?>">
                                                    </div>
                                                </div>

                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label>Email</label>
                                                        <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email'] ?? '') ?>">
                                                    </div>
                                                </div> 

                                                <div class="col-12">
                                                    <h5 class="title">Password Change</h5>
                                                    <div class="form-group">
                                                        <label>Current Password</label>
                                                        <input type="password" name="current_password" class="form-control">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>New Password</label>
                                                        <input type="password" name="new_password" class="form-control">
                                                    </div>
                                                    <div class="form-group">
                                                        <label>Confirm New Password</label>
                                                        <input type="password" name="confirm_password" class="form-control">
                                                    </div>
                                                    <div class="form-group mb--0">
                                                        <input type="submit" class="axil-btn" value="Save Changes">
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End My Account Area  -->

    <!-- Start Axil Newsletter Area  -->
    <div class="axil-newsletter-area axil-section-gap pt--0">
        <div class="container">
            <div class="etrade-newsletter-wrapper bg_image bg_image--5">
                <div class="newsletter-content">
                    <span class="title-highlighter highlighter-primary2"><i class="fas fa-envelope-open"></i>Newsletter</span>
                    <h2 class="title mb--40 mb_sm--30">Get weekly update</h2>
                    <div class="input-group newsletter-form">
                        <div class="position-relative newsletter-inner mb--15">
                            <input placeholder="example@gmail.com" type="text">
                        </div>
                        <button type="submit" class="axil-btn mb--15">Subscribe</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End .container -->
    </div>
    <!-- End Axil Newsletter Area  -->
</main>


<div class="service-area">
    <div class="container">
        <div class="row row-cols-xl-4 row-cols-sm-2 row-cols-1 row--20">
            <div class="col">
                <div class="service-box service-style-2">
                    <div class="icon">
                        <img src="./assets/images/icons/service1.png" alt="Service">
                    </div>
                    <div class="content">
                        <h6 class="title">Fast &amp; Secure Delivery</h6>
                        <p>Tell about your service.</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="service-box service-style-2">
                    <div class="icon">
                        <img src="./assets/images/icons/service2.png" alt="Service">
                    </div>
                    <div class="content">
                        <h6 class="title">Money Back Guarantee</h6>
                        <p>Within 10 days.</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="service-box service-style-2">
                    <div class="icon">
                        <img src="./assets/images/icons/service3.png" alt="Service">
                    </div>
                    <div class="content">
                        <h6 class="title">24 Hour Return Policy</h6>
                        <p>No question ask.</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="service-box service-style-2">
                    <div class="icon">
                        <img src="./assets/images/icons/service4.png" alt="Service">
                    </div>
                    <div class="content">
                        <h6 class="title">Pro Quality Support</h6>
                        <p>24/7 Live support.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include 'layouts/footer.php';
?>

<script>
    document.getElementById('addMoreImages').addEventListener('click', function() {
        const container = document.getElementById('image-inputs');

        const wrapper = document.createElement('div');
        wrapper.classList.add('input-group', 'mb-2');

        const input = document.createElement('input');
        input.type = 'file';
        input.name = 'pImages[]';
        input.accept = 'image/*';
        input.classList.add('form-control', 'shadow-sm');
        input.required = true;

        wrapper.appendChild(input);
        container.appendChild(wrapper);
    });
</script>