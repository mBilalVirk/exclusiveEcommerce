// ✅ Add to Cart Function
export function addToCart(productId, productName, button) {
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
