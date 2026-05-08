document.addEventListener("DOMContentLoaded", function () {
    const productId = window.productId;

    if (!productId) {
        console.error("Product ID not found!");
        return;
    }

    let currentRating = 5; // Default rating

    // Fetch Product Details
    fetchProductDetails(productId);

    // Fetch Reviews
    fetchReviews(productId);

    // ====================== FETCH PRODUCT ======================
    async function fetchProductDetails(id) {
        try {
            const response = await fetch(`/products/${id}`);
            if (!response.ok)
                throw new Error(`HTTP error! status: ${response.status}`);

            const data = await response.json();

            if (data.status && data.product) {
                renderProduct(data.product);
            }
        } catch (error) {
            console.error("Error fetching product:", error);
        }
    }

    // ====================== RENDER PRODUCT ======================
    function renderProduct(product) {
        document.getElementById("category").innerText = product.category || "";
        document.getElementById("name").innerText = product.name || "";
        document.querySelector("h1").innerText = product.name || "";

        document.getElementById("description").innerText =
            product.description || "";

        // Price
        const priceElement = document.querySelector(".text-2xl.font-medium");
        if (priceElement) {
            priceElement.innerText = `$${product.discount_price ?? product.price}`;
        }

        // Main Image
        if (product.image) {
            const mainImg = document.getElementById("img");
            mainImg.src = `/${product.image}`;
            mainImg.alt = product.name || "Product Image";
        }

        // Stock Status
        const stockStatus = document.getElementById("stockStatus");
        if (stockStatus) {
            const inStock = product.stock > 0;
            stockStatus.innerText = inStock ? "In Stock" : "Out of Stock";
            stockStatus.style.color = inStock ? "#00FF66" : "#FF4444";
        }
    }

    // ====================== QUANTITY ======================
    const qtyDisplay = document.getElementById("qty-display");
    const minusBtn = document.getElementById("minus-btn");
    const plusBtn = document.getElementById("plus-btn");

    if (plusBtn) {
        plusBtn.onclick = () => {
            let current = parseInt(qtyDisplay.innerText);
            qtyDisplay.innerText = current + 1;
        };
    }

    if (minusBtn) {
        minusBtn.onclick = () => {
            let current = parseInt(qtyDisplay.innerText);
            if (current > 1) qtyDisplay.innerText = current - 1;
        };
    }

    // ====================== ADD TO CART ======================
    const addToCartBtn = document.getElementById("addToCartBtn");

    if (addToCartBtn) {
        addToCartBtn.onclick = async () => {
            const qty = parseInt(qtyDisplay.innerText);

            try {
                const response = await fetch("/cart/add", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json",
                        "X-CSRF-TOKEN": document
                            .querySelector('meta[name="csrf-token"]')
                            .getAttribute("content"),
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: qty,
                    }),
                });

                if (!response.ok) {
                    const errorText = await response.text();
                    console.error("Server Error:", errorText);
                    throw new Error(`Server returned ${response.status}`);
                }

                const result = await response.json();

                if (result.status) {
                    showToast(result.message || "Added to cart!", "success");
                    if (window.updateCartCount)
                        window.updateCartCount(result.totalCount);
                } else {
                    showToast(
                        result.message || "Failed to add to cart.",
                        "error",
                    );
                }
            } catch (error) {
                console.error("Cart Error:", error);
                showToast("Something went wrong. Please try again.", "error");
            }
        };
    }

    // ====================== REVIEWS ======================
    async function fetchReviews(id) {
        try {
            const res = await fetch(`/products/${id}/reviews`);
            if (!res.ok) throw new Error("Failed to fetch reviews");

            const data = await res.json();

            renderReviews(
                data.reviews || [],
                data.average_rating || 0,
                data.total_reviews || 0,
            );
        } catch (err) {
            console.error("Failed to load reviews:", err);
        }
    }

    function renderReviews(reviews, avg, total) {
        const container = document.getElementById("reviews-container");

        const starsHTML = Array(5)
            .fill(0)
            .map(
                (_, i) =>
                    `<i class="fa-solid fa-star ${i < Math.round(avg) ? "text-yellow-400" : "text-gray-300"}"></i>`,
            )
            .join("");

        let html = `
            <div class="flex items-center gap-4 mb-8">
                <div class="flex text-4xl">${starsHTML}</div>
                <div>
                    <span class="text-3xl font-semibold">${parseFloat(avg).toFixed(1)}</span>
                    <span class="text-gray-500"> (${total} reviews)</span>
                </div>
            </div>
        `;

        if (reviews.length === 0) {
            html += `<p class="text-gray-500 py-8">No reviews yet. Be the first to review this product!</p>`;
        } else {
            html += reviews
                .map(
                    (review) => `
                <div class="border-b pb-8 last:border-none">
                    <div class="flex justify-between items-start">
                        <div>
                            <strong>${review.user?.last_name || "Anonymous"}</strong>
                            <div class="flex text-yellow-400 text-sm mt-1">
                                ${Array(5)
                                    .fill(0)
                                    .map(
                                        (_, i) =>
                                            `<i class="fa-solid fa-star ${i < review.rating ? "" : "text-gray-300"}"></i>`,
                                    )
                                    .join("")}
                            </div>
                        </div>
                        <span class="text-xs text-gray-400">${new Date(review.created_at).toLocaleDateString()}</span>
                    </div>
                    <p class="mt-4 text-gray-600 leading-relaxed">${review.comment}</p>
                </div>
            `,
                )
                .join("");
        }

        container.innerHTML = html;
    }

    // ====================== STAR RATING (Write Review) ======================
    const reviewStars = document.querySelectorAll("#review-stars i");

    reviewStars.forEach((star) => {
        // Click to select rating
        star.addEventListener("click", () => {
            currentRating = parseInt(star.dataset.rating);
            document.getElementById("rating-input").value = currentRating;

            reviewStars.forEach((s, index) => {
                s.classList.toggle("text-yellow-400", index < currentRating);
                s.classList.toggle("text-gray-300", index >= currentRating);
            });
        });
    });

    // ====================== SUBMIT REVIEW ======================
    const reviewForm = document.getElementById("review-form");

    if (reviewForm) {
        reviewForm.addEventListener("submit", async (e) => {
            e.preventDefault();

            const formData = new FormData(reviewForm);
            formData.append("rating", currentRating);

            try {
                const res = await fetch("/reviews", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector(
                            'meta[name="csrf-token"]',
                        ).content,
                    },
                    body: formData,
                });

                const data = await res.json();

                if (data.status) {
                    showToast(
                        "Thank you! Your review has been submitted.",
                        "success",
                    );
                    reviewForm.reset();
                    currentRating = 5;
                    fetchReviews(productId); // Refresh reviews
                } else {
                    showToast(
                        data.message || "Failed to submit review",
                        "error",
                    );
                }
            } catch (err) {
                console.error(err);
                showToast("Something went wrong. Please try again.", "error");
            }
        });
    }
});
