document.addEventListener("DOMContentLoaded", function () {
    // ✅ Global Cart Count Update
    window.updateCartCount = function () {
        fetch("/cart/count")
            .then((res) => res.json())
            .then((data) => {
                const el = document.getElementById("cart-count");
                if (el) el.innerText = data.count;
            })
            .catch((err) => console.log("Cart count error:", err));
    };

    // ✅ Global Wishlist Count Update
    window.updateWishlistCount = function () {
        fetch("/wishlist/count")
            .then((res) => res.json())
            .then((data) => {
                const el = document.getElementById("wishlist-count");
                if (el) el.innerText = data.count;
            })
            .catch((err) => console.log("Wishlist count error:", err));
    };

    // Run both on page load
    window.updateCartCount();
    window.updateWishlistCount();
});
