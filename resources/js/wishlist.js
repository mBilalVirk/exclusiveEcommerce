document.addEventListener("DOMContentLoaded", function () {
    fetchWishlist();
});

function fetchWishlist() {
    fetch("/wishlists", {
        method: "GET",
        headers: {
            Accept: "application/json",
        },
    })
        .then((res) => res.json())
        .then((data) => {
            const container = document.getElementById("wishlists");
            const totalSpan = document.getElementById("totalcount");
            totalSpan.innerHTML = data.total;
            if (!container) return;

            container.innerHTML = "";

            if (!data.wishlistItems || data.wishlistItems.length === 0) {
                container.innerHTML = `
                <p class="text-center col-span-4 text-gray-500">
                    Your wishlist is empty <i class="fa-solid fa-heart-crack"></i>
                </p>
            `;
                return;
            }

            data.wishlistItems.forEach((item) => {
                const p = item.product || item;

                container.innerHTML += `
                <div class="group">
                    <div class="relative bg-gray-100 rounded-sm h-[250px] flex items-center justify-center overflow-hidden">

                        <div class="absolute top-3 right-3 flex flex-col gap-2">
                            
                            <button class="bg-white p-1.5 rounded-full shadow-sm hover:text-red-600" onclick="removeFromWishlist(${item.id}, this)">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22M14 3h-4a1 2 0 00-2 2v2h8V5a2 2 0 00-2-2z" />
                                    </svg>
                                </button>
                        </div>

                        <img src="${p.image}"
                             class="object-contain max-h-[180px] group-hover:scale-110 transition duration-300">

                        <button class="absolute bottom-0 w-full bg-black text-white py-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            Add To Cart
                        </button>
                    </div>

                    <div class="mt-4">
                        <h3 class="font-bold text-base truncate">${p.name}</h3>

                        <div class="flex items-center gap-3 mt-2">
                            <span class="text-red-500 font-bold">$${p.discount_price ?? p.price}</span>
                            <span class="text-gray-400 line-through">$${p.price}</span>
                        </div>

                        <div class="flex items-center gap-2 mt-2">
                            <div class="flex text-yellow-400">${getStars(p.stars ?? 0)}</div>
                            <span class="text-gray-400 text-sm font-bold">(${p.reviews_count})</span>
                        </div>
                    </div>
                </div>
            `;
            });
        });
}

window.removeFromWishlist = function (id, btn) {
    fetch(`/wishlist/toggle/${id}`, {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
            Accept: "application/json",
        },
    })
        .then((res) => res.json())
        .then((data) => {
            btn.closest(".group").remove();
            const totalSpan = document.getElementById("totalcount");
            if (totalSpan) {
                let currentCount = parseInt(totalSpan.innerText) || 0;
                totalSpan.innerText = Math.max(0, currentCount - 1); // Ensure it doesn't go below 0
            }
            updateWishlistCount();
            const container = document.querySelector(".grid");

            if (container.children.length === 0) {
                container.innerHTML = `
                <p class="text-center col-span-4 text-gray-500">
                    Your wishlist is empty <i class="fa-solid fa-heart-crack"></i>
                </p>
            `;
            }
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
