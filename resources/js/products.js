import Swiper from "swiper";
import { Navigation } from "swiper/modules";
import "swiper/css";
import "swiper/css/navigation";
import { createProductCard } from "./utils/productCard";
import { showToast } from "./utils/toast";
// import { addToCart } from "./utils/addToCart";
document.addEventListener("DOMContentLoaded", function () {
    const container = document.getElementById("products-grid");

    if (!container) {
        console.error("bestselling-grid element not found");
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
                html += createProductCard(product);
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
// toggle wishlist function
window.toggleWishlist = function (productId, btn) {
    fetch(`/wishlist/toggle/${productId}`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
            Accept: "application/json",
        },
    })
        .then(async (res) => {
            const data = await res.json();

            if (data.status === true || data.status === "added") {
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
            updateWishlistCount();
            const icon = btn.querySelector("svg");
            if (!btn) {
                console.error("Button not found ❌");
                return;
            }
            console.log(icon);
            if (data.status === "added") {
                //alert("Added to wishlist ❤️");
                icon.classList.add("text-red-500", "fill-current");
            } else {
                //alert("Removed from wishlist 💔");
                icon.classList.remove("text-red-500", "fill-current");
            }
        })
        .catch((error) => {
            // ❌ Missing catch block entirely!
            console.error("Wishlist toggle error:", error);
            alert("Failed to update wishlist");
        });
};
function getStars(rating) {
    let stars = "";

    for (let i = 1; i <= 5; i++) {
        if (i <= rating) {
            stars += "★"; // filled star
        } else {
            stars += "☆"; // empty star
        }
    }

    return stars;
}
// ✅ SWIPER INIT
function initSwiper() {
    if (window.bestSwiper) {
        window.bestSwiper.destroy(true, true);
    }

    window.bestSwiper = new Swiper(".bestselling-swiper", {
        modules: [Navigation],
        slidesPerView: 1,
        spaceBetween: 20,

        navigation: {
            nextEl: "#best-next",
            prevEl: "#best-prev",
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
