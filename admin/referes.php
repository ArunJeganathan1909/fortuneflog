<?php include "layout/header.php";

// Fetch products and their images
$sql = "SELECT p.*, pi.image_path, c.cat_name AS category_name 
        FROM tbl_products_customers p 
        LEFT JOIN tbl_product_images pi ON p.id = pi.product_id 
        LEFT JOIN tbl_categories c ON p.p_categories = c.cat_id  
        ORDER BY p.created_at DESC";

$result = $conn->query($sql);

// Organize products and images
$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pid = $row['id'];
        if (!isset($products[$pid])) {
            $products[$pid] = [
                'id' => $pid,
                'title' => $row['title'],
                'description' => $row['description'],
                'price' => $row['price'],
                'age' => $row['age'],
                'type' => $row['type'],
                'category' => $row['category_name'] ?? 'Uncategorized',
                'contact' => [
                    'name' => $row['contact_name'],
                    'address' => $row['contact_address'],
                    'email' => $row['contact_email'],
                    'phone' => $row['contact_phone']
                ],
                'status' => $row['status'],
                'images' => []
            ];
        }
        if ($row['image_path']) {
            $products[$pid]['images'][] = $row['image_path'];
        }
    }
}


if (isset($_SESSION['message'])) {
    $msg_type = $_SESSION['msg_type'] ?? 'info';
    echo '<div class="alert alert-' . htmlspecialchars($msg_type) . '">' . htmlspecialchars($_SESSION['message']) . '</div>';
    unset($_SESSION['message'], $_SESSION['msg_type']);
}
?>

<div class="main-content app-content">
    <div class="container-fluid">


        <div class="col-lg-12">
            <div class="card custom-card">
                <div class="card-body">
                    <div class="container">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Product Title</th>
                                        <th>Images</th>
                                        <th>Description</th>
                                        <th>Category</th>
                                        <th>Price</th>
                                        <th>Age</th>
                                        <th>Type</th>
                                        <th>Contact</th>
                                        <th>Status</th>
                                        <th><div class="mb-3">
  <label for="statusFilter" class="form-label">Filter by Status:</label>
  <select id="statusFilter" class="form-select" style="max-width: 200px;">
    <option value="all" selected>All</option>
    <option value="pending">Pending</option>
    <option value="approved">Approved</option>
    <option value="rejected">Rejected</option>
  </select>
</div></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($products as $product): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($product['title']) ?></td>
                                            <td>
                                                <?php foreach ($product['images'] as $img): ?>
                                                   <img 
                                                        src="<?= htmlspecialchars($img) ?>" 
                                                        alt="Product Image" 
                                                        class="img-thumbnail m-1 enlarge-image" 
                                                        style="width: 60px; height: 60px; object-fit: cover; cursor: pointer;"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#imageModal"
                                                        data-image="<?= htmlspecialchars($img) ?>"
                                                    >
                                                <?php endforeach; ?>
                                            </td>
                                            <td><?= htmlspecialchars($product['description']) ?></td>
                                            <td><?= htmlspecialchars($product['category']) ?></td>
                                            <td>$<?= number_format($product['price'], 2) ?></td>
                                            <td><?= htmlspecialchars($product['age']) ?> years</td>
                                            <td><?= htmlspecialchars($product['type']) ?></td>
                                            <td>
                                                <?= htmlspecialchars($product['contact']['name']) ?><br>
                                                <?= htmlspecialchars($product['contact']['address']) ?><br>
                                                <?= htmlspecialchars($product['contact']['email']) ?><br>
                                                <?= htmlspecialchars($product['contact']['phone']) ?>
                                            </td>
                                            <?php
                                                $status = strtolower(trim($product['status']));
                                                $badgeClass = '';

                                                if ($status === 'approved') {
                                                    $badgeClass = 'bg-success';
                                                } elseif ($status === 'rejected') {
                                                    $badgeClass = 'bg-danger';
                                                } else {
                                                    $badgeClass = 'bg-warning';
                                                }
                                            ?>
                                            <td>
                                                <span class="badge <?= $badgeClass ?>">
                                                    <?= ucfirst($status) ?>
                                                </span>
                                            </td>

                                            <td>
                                                <form method="POST" action="backend/update_product_status.php" onsubmit="return confirm('Change status?');">
                                                    <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                                    <select name="status" class="form-select form-select-sm mb-2" required>
                                                        <option value="pending" <?= $product['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                                        <option value="approved" <?= $product['status'] === 'approved' ? 'selected' : '' ?>>Approved</option>
                                                        <option value="rejected" <?= $product['status'] === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                                                    </select>
                                                    <button type="submit" class="btn btn-primary btn-sm">Update Status</button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap Modal for Enlarging Image -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Image Preview</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <!-- Enlarged Image -->
                <img src="" alt="Preview" id="modalImage" style="max-width: 100%; max-height: 100%; border-radius: 5px;">
            </div>
            <div class="modal-footer">
                <!-- Delete Button -->
                <form action="backend/delete_image.php" method="post" id="deleteImageForm">
                    <input type="hidden" name="image_id" id="modalImageId">
                    <input type="hidden" name="image_path" id="modalImagePath">
                    <input type="hidden" name="is_main_image" id="modalIsMainImage">
                </form>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<?php include("layout/footer.php"); ?>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const previewImages = document.querySelectorAll(".preview-image");
        const modalImage = document.getElementById("modalImage");
        const modalImageId = document.getElementById("modalImageId");
        const modalImagePath = document.getElementById("modalImagePath");
        const modalIsMainImage = document.getElementById("modalIsMainImage");

        previewImages.forEach(img => {
            img.addEventListener("click", () => {
                const imagePath = img.getAttribute("data-image");
                const imageId = img.getAttribute("data-id");
                const isMain = img.getAttribute("data-is-main");

                modalImage.src = imagePath;
                modalImageId.value = imageId;
                modalImagePath.value = imagePath;
                modalIsMainImage.value = isMain;
            });
        });
    });

  document.getElementById('statusFilter').addEventListener('change', function() {
    const filter = this.value.toLowerCase();
    const table = document.querySelector('table tbody');
    const rows = table.querySelectorAll('tr');

    rows.forEach(row => {
      // Find the status cell (8th cell, zero-based index 7)
      const statusCell = row.cells[8];
      if (!statusCell) return; // safety check

      const statusText = statusCell.textContent.trim().toLowerCase();

      if (filter === 'all' || statusText === filter) {
        row.style.display = ''; // show row
      } else {
        row.style.display = 'none'; // hide row
      }
    });
  });

  document.addEventListener("DOMContentLoaded", function () {
    const imageModal = document.getElementById("imageModal");
    const modalImage = document.getElementById("modalImage");

    document.querySelectorAll(".enlarge-image").forEach(img => {
        img.addEventListener("click", function () {
            const imagePath = this.getAttribute("data-image");
            modalImage.setAttribute("src", imagePath);
        });
    });

    // Status filter code remains the same
    document.getElementById('statusFilter').addEventListener('change', function () {
        const filter = this.value.toLowerCase();
        const rows = document.querySelectorAll('table tbody tr');

        rows.forEach(row => {
            const statusCell = row.cells[8];
            const statusText = statusCell?.textContent?.trim().toLowerCase();
            row.style.display = (!statusText || filter === 'all' || statusText === filter) ? '' : 'none';
        });
    });
});
</script>