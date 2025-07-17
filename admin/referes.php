<?php include "layout/header.php"; ?>

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
                                        <th>Price</th>
                                        <th>Age</th>
                                        <th>Type</th>
                                        <th>Contact</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Sample product data array (normally you'd fetch this from DB)
                                    $products = [
                                        [
                                            'title' => 'Vintage Vase',
                                            'images' => ['assets/images/products/vase1.jpg', 'assets/images/products/vase2.jpeg'],
                                            'description' => 'Beautiful antique ceramic vase from the 1800s.',
                                            'price' => '$120',
                                            'age' => '145 years',
                                            'type' => 'Decorative',
                                            'contact' => [
                                                'email' => 'vintage@example.com',
                                                'phone' => '+94 77 123 4567'
                                            ]
                                        ],
                                        [
                                            'title' => 'Classic Typewriter',
                                            'images' => ['assets/images/products/typewriter1.jpg'],
                                            'description' => 'Fully functional vintage typewriter.',
                                            'price' => '$250',
                                            'age' => '85 years',
                                            'type' => 'Electronics',
                                            'contact' => [
                                                'email' => 'classic@example.com',
                                                'phone' => '+94 71 987 6543'
                                            ]
                                        ]
                                    ];

                                    // Render each row
                                    foreach ($products as $index => $product): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($product['title']) ?></td>
                                            <td>
                                                <?php foreach ($product['images'] as $img): ?>
                                                    <img src="<?= $img ?>" alt="Product Image" class="img-thumbnail m-1 preview-image" style="width: 60px; height: 60px; object-fit: cover; cursor: pointer;"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#imageModal"
                                                        data-image="<?= $img ?>"
                                                        data-id="<?= $index ?>"
                                                        data-is-main="<?= ($img === $product['images'][0]) ? '1' : '0' ?>">
                                                <?php endforeach; ?>
                                            </td>
                                            <td><?= htmlspecialchars($product['description']) ?></td>
                                            <td><?= htmlspecialchars($product['price']) ?></td>
                                            <td><?= htmlspecialchars($product['age']) ?></td>
                                            <td><?= htmlspecialchars($product['type']) ?></td>
                                            <td>
                                                <?= htmlspecialchars($product['contact']['email']) ?><br>
                                                <?= htmlspecialchars($product['contact']['phone']) ?>
                                            </td>

                                            <td>
                                                <form action="backend/delete_product.php" method="post" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                                    <input type="hidden" name="product_id" value="<?= $index ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
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
                    <button type="submit" class="btn btn-danger">Delete Image</button>
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
</script>