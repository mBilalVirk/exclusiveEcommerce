document.addEventListener("DOMContentLoaded", function () {
    const container = document.getElementById("products-grid");

    if (!container) {
        console.error("products-grid element not found");
        return;
    }

    fetch("/products", {
        headers: {
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
    })
        .then(async (res) => {
            const contentType = res.headers.get("content-type");

            if (!contentType || !contentType.includes("application/json")) {
                const text = await res.text();
                console.error("Expected JSON but got HTML:");
                throw new Error("Server returned HTML instead of JSON");
            }

            return res.json();
        })
        .then((response) => {
            if (!response.status || !response.data) {
                throw new Error("Invalid API response structure");
            }

            const products = response.data;
            let html = "";

            products.forEach((product) => {
                // Check if is_new is 1 (Database true). If so, show the badge.
                let discountBadge = "";

                // Check if a valid discount exists to calculate percentage
                if (
                    product.discount_price &&
                    product.discount_price < product.price
                ) {
                    const percentage = Math.round(
                        ((product.price - product.discount_price) /
                            product.price) *
                            100,
                    );
                    discountBadge = `<span class="absolute top-3 left-3 bg-[#DB4444] text-white text-[12px] px-3 py-1 rounded-sm z-10">-${percentage}%</span>`;
                }
                // If no discount, check if it's a "New" arrival
                else if (product.is_new == 1) {
                    discountBadge = `<span class="absolute top-3 left-3 bg-green-500 text-white text-[12px] px-3 py-1 rounded-sm z-10">New</span>`;
                }

                html += `
            <div class="group">
                <div class="relative bg-gray-100 rounded-sm h-[250px] flex items-center justify-center overflow-hidden">
                    
                    ${discountBadge}

                    <img src="${product.image}"
                         alt="${product.name}"
                         class="object-contain max-h-[180px] group-hover:scale-110 transition duration-300">

                    <button
                        class="add-to-cart absolute bottom-0 w-full bg-black text-white py-2 opacity-0 group-hover:opacity-100 transition-opacity"
                        data-id="${product.id}">
                        Add To Cart
                    </button>

                </div>

                <div class="mt-4">
                    <h3 class="font-bold text-base truncate">${product.name}</h3>

                    <div class="flex items-center gap-3 mt-2">
    <span class="text-red-500 font-bold">
        $${
            product.discount_price && product.discount_price < product.price
                ? product.discount_price
                : product.price
        }
    </span>

    ${
        product.discount_price && product.discount_price < product.price
            ? `<span class="text-gray-400 line-through font-medium">$${product.price}</span>`
            : ""
    }
</div>

                    <div class="flex items-center gap-2 mt-2">
                        <div class="flex text-yellow-400">
                            ${"★".repeat(product.stars)}${"☆".repeat(5 - product.stars)}
                        </div>
                        <span class="text-gray-400 text-sm font-bold">(${product.reviews_count})</span>
                    </div>
                </div>
            </div>
            `;
            });

            container.innerHTML = html;

            // Add to Cart event listener
            document.querySelectorAll(".add-to-cart").forEach((btn) => {
                btn.addEventListener("click", function () {
                    const productId = this.dataset.id;
                    console.log("Add to cart:", productId);
                });
            });
        })
        .catch((error) => {
            console.error("Error loading products:", error);
            container.innerHTML = `
            <div class="col-span-full text-center text-red-500 py-10">
                Failed to load products. Please check console.
            </div>
        `;
        });
});
