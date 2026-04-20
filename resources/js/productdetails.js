document.addEventListener("DOMContentLoaded", function () {
    const id = window.productId; // Grabbed from the Blade script tag

    if (!id) return;

    fetchProductDetails(id);

    // 1. Fetch Data
    async function fetchProductDetails(productId) {
        try {
            const response = await fetch(`/products/${productId}`);
            const data = await response.json();

            if (data.status) {
                renderProduct(data.product);
            }
        } catch (error) {
            console.error("Error fetching product:", error);
        }
    }

    // 2. Update the UI
    function renderProduct(product) {
        // Update Title
        document.getElementById("category").innerText = product.category;
        document.getElementById("name").innerText = product.name;
        document.getElementById("description").innerText = product.description;
        document.querySelector("h1").innerText = product.name;

        // Update Price
        const priceElement = document.querySelector(".text-2xl.font-medium");
        priceElement.innerText = `$${product.discount_price ?? product.price}`;

        // Update Images (Main and Thumbnails)
        if (product.image) {
            const mainImg = document.getElementById("img");
            mainImg.src = `/${product.image}`;
            mainImg.alt = product.name;
        }

        // Update Stock Status
        const stockStatus = document.querySelector(".text-[#00FF66]");
        stockStatus.innerText = product.stock > 0 ? "In Stock" : "Out of Stock";
        stockStatus.style.color = product.stock > 0 ? "#00FF66" : "#FF4444";
    }

    // 3. Handle Quantity Buttons (+/-)
    const qtyContainer = document.querySelector(
        ".flex.items-center.border.border-gray-400",
    );
    const qtyDisplay = qtyContainer.querySelector("span");
    const minusBtn = qtyContainer.querySelector("button:first-child");
    const plusBtn = qtyContainer.querySelector("button:last-child");

    plusBtn.onclick = () => {
        let current = parseInt(qtyDisplay.innerText);
        qtyDisplay.innerText = current + 1;
    };

    minusBtn.onclick = () => {
        let current = parseInt(qtyDisplay.innerText);
        if (current > 1) qtyDisplay.innerText = current - 1;
    };

    // 4. Handle Add to Cart
    const buyBtn = document.querySelector("button.flex-1.bg-[#DB4444]");
    buyBtn.onclick = async () => {
        const qty = parseInt(qtyDisplay.innerText);

        const response = await fetch("/cart/add", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
            body: JSON.stringify({
                product_id: id,
                qty: qty,
            }),
        });

        const result = await response.json();
        if (result.status) {
            alert("Added to cart!");
            // Update your cart count icon if you have one
            if (window.updateCartCount)
                window.updateCartCount(result.totalCount);
        }
    };
});
