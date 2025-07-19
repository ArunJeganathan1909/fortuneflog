<?php include("layout/header.php"); 


// Get total categories
$sqlCatCount = "SELECT COUNT(*) AS total_categories FROM tbl_categories";
$catResult = $conn->query($sqlCatCount);
$catCount = $catResult->fetch_assoc()['total_categories'] ?? 0;

$sqlProdCount = "SELECT COUNT(*) AS total_products FROM tbl_products";
$prodResult = $conn->query($sqlProdCount);
$prodCount = $prodResult->fetch_assoc()['total_products'] ?? 0;
// Get total products
$sqlProdCustomersCount = "SELECT COUNT(*) AS total_products_customers FROM tbl_products_customers";
$prodCustomersResult = $conn->query($sqlProdCustomersCount);
$prodCustomersCount = $prodCustomersResult->fetch_assoc()['total_products_customers'] ?? 0;
?>

        <!-- Start::app-content -->
        <div class="main-content app-content">
            <div class="container-fluid">

                <!-- Page Header -->
                <div class="d-md-flex d-block align-items-center justify-content-between my-4 page-header-breadcrumb">
                    <div class="">
                        <h1 class="page-title fw-semibold fs-20 mb-0">Dashboard</h1>
                        <div class="">
                            <nav>
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
                <!-- Page Header Close -->
<div class="row">
    <!-- Total Categories -->
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card border-0 shadow-sm rounded">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <span class="avatar avatar-md bg-primary text-white rounded-circle">
                        <i class="bi bi-tags"></i>
                    </span>
                </div>
                <div>
                    <h5 class="card-title mb-1">Total Categories</h5>
                    <h3 class="fw-bold mb-0"><?= $catCount ?></h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Products -->
    <div class="col-md-6 col-lg-4 mb-4">
        <div class="card border-0 shadow-sm rounded">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <span class="avatar avatar-md bg-success text-white rounded-circle">
                        <i class="bi bi-box-seam"></i>
                    </span>
                </div>
                <div>
                    <h5 class="card-title mb-1">Total Products</h5>
                    <h3 class="fw-bold mb-0"><?= $prodCount ?></h3>
                </div>
            </div>
        </div>
    </div>

        <div class="col-md-6 col-lg-4 mb-4">
        <div class="card border-0 shadow-sm rounded">
            <div class="card-body d-flex align-items-center">
                <div class="me-3">
                    <span class="avatar avatar-md bg-warning text-white rounded-circle">
                        <i class="bi bi-box-seam"></i>
                    </span>
                </div>
                <div>
                    <h5 class="card-title mb-1">Total Products to Refer</h5>
                    <h3 class="fw-bold mb-0"><?= $prodCustomersCount ?></h3>
                </div>
            </div>
        </div>
    </div>
</div>



            </div>
        </div>
        <!-- End::app-content -->

        <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModal" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">

              </div>
            </div>
        </div>
        <?php include("layout/footer.php"); ?>
