document.addEventListener("DOMContentLoaded", function () {
    const grid = document.getElementById("product-grid");
    const countEl = document.getElementById("product-count");

    const searchInput = document.getElementById("search-input");
    const categoryFilter = document.getElementById("category-filter");
    const priceFilter = document.getElementById("price-filter");

    // -------------------------
    // READ URL
    // -------------------------
    const params = new URLSearchParams(window.location.search);

    categoryFilter.value = params.get("category") || "";
    searchInput.value = params.get("search") || "";
    priceFilter.value = params.get("price") || "";

    // -------------------------
    // UPDATE URL
    // -------------------------
    function updateURL() {
        const url = new URL(window.location);

        const category = categoryFilter.value;
        const search = searchInput.value;
        const price = priceFilter.value;

        category
            ? url.searchParams.set("category", category)
            : url.searchParams.delete("category");
        search
            ? url.searchParams.set("search", search)
            : url.searchParams.delete("search");
        price
            ? url.searchParams.set("price", price)
            : url.searchParams.delete("price");

        history.pushState({}, "", url);
    }

    // -------------------------
    // FETCH PRODUCTS
    // -------------------------
    function fetchProducts() {
        const category = categoryFilter.value;
        const search = searchInput.value;
        const price = priceFilter.value;

        updateURL();

        fetch(`/shop?category=${category}&search=${search}&price=${price}`, {
            headers: {
                "X-Requested-With": "XMLHttpRequest",
                Accept: "application/json",
            },
        })
            .then((res) => res.json())
            .then((response) => {
                if (!response.status) return;

                countEl.textContent = response.count;
                grid.innerHTML = "";

                const products = response.products;

                if (!products.length) {
                    grid.innerHTML = `
                        <p class="col-span-3 text-center py-10 text-gray-400">
                            No products found
                        </p>`;
                    return;
                }

                products.forEach((product) => {
                    const hasDiscount =
                        product.discount_price &&
                        Number(product.discount_price) < Number(product.price);

                    const finalPrice = hasDiscount
                        ? product.discount_price
                        : product.price;

                    const discountBadge = hasDiscount
                        ? `<span class="absolute top-3 left-3 bg-[#DB4444] text-white text-[12px] px-3 py-1 rounded-sm">
                            -${Math.round(((product.price - product.discount_price) / product.price) * 100)}%
                        </span>`
                        : "";

                    grid.innerHTML += `
                        <div class="group">
                            <div class="relative bg-gray-100 rounded-sm h-[250px] flex items-center justify-center overflow-hidden">

                                ${discountBadge}
                                 <div class="absolute top-3 right-3 flex flex-col gap-2">
                        <button class="bg-white p-1.5 rounded-full shadow-sm hover:text-red-500"><svg class="w-5 h-5"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                                </path>
                            </svg></button>
                        <button class="bg-white p-1.5 rounded-full shadow-sm hover:text-blue-500"><svg class="w-5 h-5"
                                fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                </path>
                            </svg></button>
                    </div>

                                <img src="/${product.image}" 
                                     class="object-contain max-h-[180px] group-hover:scale-110 transition duration-300">

                                <button 
                            class="add-to-cart absolute bottom-0 w-full bg-black text-white py-2 opacity-0 group-hover:opacity-100 transition-opacity hover:bg-gray-800"
                            data-id="${product.id}"
                            data-name="${product.name || "Product"}">
                            Add To Cart
                        </button>
                            </div>

                            <div class="mt-4">
                                <h3 class="font-bold truncate">${product.name}</h3>

                                <div class="flex items-center gap-3 mt-2">
                                    <span class="text-red-500 font-bold">$${finalPrice}</span>

                                    ${
                                        hasDiscount
                                            ? `<span class="text-gray-400 line-through">$${product.price}</span>`
                                            : ""
                                    }
                                </div>
                            </div>
                        </div>
                    `;
                });
                addEventListener("click", function (e) {
                    const button = e.target.closest(".add-to-cart");
                    if (button) {
                        const productId = button.dataset.id;
                        // const productName = button.dataset.name;productName
                        addToCart(productId, button);
                    }
                });
            })
            .catch((err) => {
                console.error("Fetch error:", err);
                grid.innerHTML = `<p class="text-red-500 text-center">Something went wrong</p>`;
            });
    }

    // -------------------------
    // EVENTS
    // -------------------------
    categoryFilter.addEventListener("change", fetchProducts);
    searchInput.addEventListener("input", fetchProducts);
    priceFilter.addEventListener("change", fetchProducts);

    // BACK BUTTON SUPPORT
    window.addEventListener("popstate", function () {
        const params = new URLSearchParams(window.location.search);

        categoryFilter.value = params.get("category") || "";
        searchInput.value = params.get("search") || "";
        priceFilter.value = params.get("price") || "";

        fetchProducts();
    });
    // ✅ Add to Cart Function
    function addToCart(productId, button) {
        // Disable button temporarily
        const originalText = button.textContent;
        button.disabled = true;
        button.textContent = "Adding...";
        console.log("add to cart!!");

        // Send request to server
        fetch("/cart/add", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-Requested-With": "XMLHttpRequest",
                "X-CSRF-TOKEN":
                    document.querySelector('meta[name="csrf-token"]')
                        ?.content || "",
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: 1,
            }),
        })
            .then(async (res) => {
                const data = await res.json();

                if (data.status === true) {
                    // Success Toast
                    showToast(data.message, "success");

                    // Re-fetch cart items to update UI without reload
                    if (typeof fetchCart === "function") fetchCart();
                } else {
                    // Error Toast
                    showToast(data.message, "error");
                }

                return data;
            })
            .then((data) => {
                // Success feedback
                button.textContent = "Added ✓";
                button.classList.add("bg-green-600");
                button.classList.remove("bg-black", "hover:bg-gray-800");
                updateCartCount();
                // Reset button after 2 seconds
                setTimeout(() => {
                    button.textContent = originalText;
                    button.classList.remove("bg-green-600");
                    button.classList.add("bg-black", "hover:bg-gray-800");
                    button.disabled = false;
                }, 2000);
            })
            .catch((error) => {
                console.error("Error adding to cart:", error);

                // Error feedback
                button.textContent = "Failed ✗";
                button.classList.add("bg-red-600");
                button.classList.remove("bg-black", "hover:bg-gray-800");

                // Reset button after 2 seconds
                setTimeout(() => {
                    button.textContent = originalText;
                    button.classList.remove("bg-red-600");
                    button.classList.add("bg-black", "hover:bg-gray-800");
                    button.disabled = false;
                }, 2000);
            });
    }
    // INIT
    fetchProducts();
});
