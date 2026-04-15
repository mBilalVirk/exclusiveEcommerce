document.addEventListener("DOMContentLoaded", function () {
    const cartContainer = document.getElementById("cart-items-container");
    const subTotalSpan = document.getElementById("subtotal");
    const shippingSpan = document.getElementById("shippin_fee");
    const grandTotalSpan = document.getElementById("grandtotal");
    if (!cartContainer) return;

    fetchCart();

    function fetchCart() {
        fetch("/cartshow", {
            // Assuming this returns JSON
            credentials: "include",
            headers: {
                Accept: "application/json",
                "X-Requested-With": "XMLHttpRequest",
            },
        })
            .then((res) => res.json())
            .then((response) => {
                if (response.cartItems && response.cartItems.length > 0) {
                    renderCart(response.cartItems);
                    updateTotals(response.total);
                } else {
                    cartContainer.innerHTML = `<div class="py-10 text-center">Your cart is empty.</div>`;
                }
            })
            .catch((err) => console.error("Error fetching cart:", err));
    }

    function renderCart(cartItems) {
        let html = "";
        cartItems.forEach((item) => {
            const price = item.product.discount_price || item.product.price;
            const subtotal = price * item.qty;

            html += `
            <div class="grid grid-cols-1 md:grid-cols-4 items-center bg-white shadow-sm rounded px-8 py-6 relative group mb-4">
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <button onclick="removeFromCart(${item.id})" 
                            class="absolute -top-2 -left-2 bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-[10px] opacity-0 group-hover:opacity-100 transition">✕</button>
                        <img src="${item.product.image || "/placeholder.png"}" alt="${item.product.name}" class="w-12 h-12 object-contain">
                    </div>
                    <span class="text-sm">${item.product.name}</span>
                </div>
                <div class="text-center text-sm">$${price}</div>
                <div class="flex justify-center">
                    <div class="flex items-center border border-gray-300 rounded px-3 py-1 gap-4">
    
                        <span id="qty-${item.id}" class="text-sm">
                            ${String(item.qty).padStart(2, "0")}
                        </span>

                        <div class="flex flex-col text-[10px]">
                            <button onclick="updateQty(${item.id}, 'inc')" class="hover:text-red-500">▲</button>
                            <button onclick="updateQty(${item.id}, 'dec')" class="hover:text-red-500">▼</button>
                        </div>

                    </div>
                </div>
                <div class="text-right text-sm font-medium">$${subtotal}</div>
            </div>`;
        });
        cartContainer.innerHTML = html;
    }

    function updateTotals(total) {
        let html = "";
        const shiping_fee = 150;
        const subTotal = total + shiping_fee;
        html += `$${total}`;
        subTotalSpan.innerHTML = html;
        shippingSpan.innerHTML = `$${shiping_fee}`;
        grandTotalSpan.innerHTML = `$${subTotal}`;
    }

    window.removeFromCart = function (cartId) {
        if (!confirm("Remove this item from cart?")) return;

        fetch(`/cart/remove/${cartId}`, {
            method: "DELETE", // or POST depending on your backend
            headers: {
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]',
                ).content,
                "X-Requested-With": "XMLHttpRequest",
            },
        })
            .then((res) => res.json())
            .then((data) => {
                if (data.status === "success" || data.message) {
                    // 2. Show the message to the user (Example using a simple alert)
                    if (data.status === true) {
                        // Success Toast
                        showToast(data.message, "success");

                        // Re-fetch cart items to update UI without reload
                        if (typeof fetchCart === "function") fetchCart();
                    } else {
                        // Error Toast
                        showToast(data.message, "error");
                    }

                    // 3. Update the UI
                    // Instead of location.reload(), calling fetchCart() is smoother
                    fetchCart();
                    updateCartCount();
                } else if (data.status === "error") {
                    alert("Error: " + data.message);
                }
            })
            .catch((err) => console.error("Error removing item:", err));
    };

    window.updateQty = function (id, action) {
        fetch(`/cart/update/${id}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]',
                ).content,
                "X-Requested-With": "XMLHttpRequest",
            },
            body: JSON.stringify({ action }),
        })
            .then((res) => res.json())
            .then((data) => {
                if (!data.status) {
                    alert(data.message);

                    return;
                }
                fetchCart();
                updateCartCount();
                //console.log("Qty updated:", data);

                // ✅ update UI instantly
                document.getElementById(`qty-${id}`).innerText = data.qty;

                // optional: update totals
                if (data.total) {
                    updateTotals(data.total);
                }
            })
            .catch((err) => console.error(err));
    };
});
