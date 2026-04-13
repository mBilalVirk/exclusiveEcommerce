import Swiper from "swiper";
import { Navigation } from "swiper/modules";
import "swiper/css";
import "swiper/css/navigation";

document.addEventListener("DOMContentLoaded", function () {
    const container = document.getElementById("flashsales-grid");

    if (!container) {
        console.error("flashsales-grid element not found");
        return;
    }

    fetch("/products/flashsales", {
        headers: {
            Accept: "application/json",
            "X-Requested-With": "XMLHttpRequest",
        },
    })
        .then(async (res) => {
            const contentType = res.headers.get("content-type");

            if (!contentType || !contentType.includes("application/json")) {
                const text = await res.text();
                console.error("Expected JSON but got:", text);
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
                let discountBadge = "";

                const hasDiscount =
                    product.discount_price &&
                    product.discount_price > 0 &&
                    product.discount_price < product.price;

                if (hasDiscount) {
                    const percentage = Math.round(
                        ((product.price - product.discount_price) /
                            product.price) *
                            100,
                    );

                    discountBadge = `
                        <span class="absolute top-3 left-3 bg-[#DB4444] text-white text-[12px] px-3 py-1 rounded-sm z-10">
                            -${percentage}%
                        </span>`;
                }

                const finalPrice = hasDiscount
                    ? product.discount_price
                    : product.price;

                html += `
                <div class="swiper-slide group">
                    <div class="relative bg-gray-100 rounded-sm h-[250px] flex items-center justify-center overflow-hidden">
                        
                        ${discountBadge}

                        <img src="${product.image || "/placeholder.png"}" 
                             alt="${product.name || "Product"}" 
                             class="object-contain max-h-[180px] group-hover:scale-110 transition duration-300">

                        <button 
                            class="add-to-cart absolute bottom-0 w-full bg-black text-white py-2 opacity-0 group-hover:opacity-100 transition-opacity hover:bg-gray-800"
                            data-id="${product.id}"
                            data-name="${product.name || "Product"}">
                            Add To Cart
                        </button>
                    </div>

                    <div class="mt-4">
                        <h3 class="font-bold text-base truncate">${product.name || "Unnamed Product"}</h3>

                        <div class="flex items-center gap-3 mt-2">
                            <span class="text-red-500 font-bold">
                                $${finalPrice}
                            </span>

                            ${hasDiscount ? `<span class="text-gray-400 line-through font-medium">$${product.price}</span>` : ""}
                        </div>
                    </div>
                </div>`;
            });

            container.innerHTML = html;

            initSwiper();

            // ✅ Add to Cart functionality - Using Event Delegation
            container.addEventListener("click", function (e) {
                const button = e.target.closest(".add-to-cart");
                if (button) {
                    const productId = button.dataset.id;
                    const productName = button.dataset.name;
                    addToCart(productId, productName, button);
                }
            });
        })
        .catch((error) => {
            console.error("Error loading products:", error);

            container.innerHTML = `
                <div class="text-center text-red-500 py-10">
                    Failed to load products. ${error.message}
                </div>`;
        });
});

// ✅ Add to Cart Function
function addToCart(productId, productName, button) {
    // Disable button temporarily
    const originalText = button.textContent;
    button.disabled = true;
    button.textContent = "Adding...";

    // Send request to server
    fetch("/cart/add", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-Requested-With": "XMLHttpRequest",
            "X-CSRF-TOKEN":
                document.querySelector('meta[name="csrf-token"]')?.content ||
                "",
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1,
        }),
    })
        .then(async (res) => {
            const data = await res.json();

            if (!res.ok) {
                throw new Error(data.message || "Failed to add to cart");
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

// ✅ SWIPER INIT
function initSwiper() {
    if (window.flashSwiper) {
        window.flashSwiper.destroy(true, true);
    }

    window.flashSwiper = new Swiper(".flashsales-swiper", {
        modules: [Navigation],
        slidesPerView: 1,
        spaceBetween: 20,

        navigation: {
            nextEl: "#flash-next",
            prevEl: "#flash-prev",
        },

        breakpoints: {
            640: {
                slidesPerView: 2,
            },
            1024: {
                slidesPerView: 4,
            },
        },
    });
}
