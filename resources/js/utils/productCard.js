export function createProductCard(product) {
    const hasDiscount =
        product.discount_price && product.discount_price < product.price;
    const percentage = hasDiscount
        ? Math.round(
              ((product.price - product.discount_price) / product.price) * 100,
          )
        : 0;
    const heartClass = product.is_wishlisted ? "text-red-500 fill-current" : "";
    const finalPrice = hasDiscount ? product.discount_price : product.price;

    const discountBadge = hasDiscount
        ? `<span class="absolute top-3 left-3 bg-[#DB4444] text-white text-[12px] px-3 py-1 rounded-sm z-10">-${percentage}%</span>`
        : product.is_new
          ? `<span class="absolute top-3 left-3 bg-green-500 text-white text-[12px] px-3 py-1 rounded-sm z-10">New</span>`
          : "";

    return `
        <div class="swiper-slide group">
            <div class="relative bg-gray-100 rounded-sm h-[250px] flex items-center justify-center overflow-hidden">
                ${discountBadge}
                <div class="absolute top-3 right-3 flex flex-col gap-2">
                    <button class="bg-white p-1.5 rounded-full shadow-sm hover:text-red-500" 
                            onclick="toggleWishlist(${product.id}, this)">
                        <svg class="w-5 h-5 ${heartClass}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
                            </path>
                        </svg>
                    </button>
                    <button class="bg-white p-1.5 rounded-full shadow-sm hover:text-blue-500" 
                            onclick="window.location.href='/show/${product.id}'">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                            </path>
                        </svg>
                    </button>
                </div>
                <img src="${product.image || "/placeholder.png"}" alt="${product.name}" 
                     class="object-contain max-h-[180px] group-hover:scale-110 transition duration-300">
                <button class="add-to-cart absolute bottom-0 w-full bg-black text-white py-2 opacity-0 group-hover:opacity-100 transition-opacity hover:bg-gray-800"
                        data-id="${product.id}" data-name="${product.name}">
                    Add To Cart
                </button>
            </div>
            <div class="mt-4">
                <h3 class="font-bold text-base truncate">${product.name || "Unnamed Product"}</h3>
                <div class="flex items-center gap-3 mt-2">
                    <span class="text-red-500 font-bold">$${finalPrice}</span>
                    ${hasDiscount ? `<span class="text-gray-400 line-through font-medium">$${product.price}</span>` : ""}
                </div>
                <div class="flex items-center gap-2 mt-2">
                    <div class="flex text-yellow-400">${getStars(product.stars ?? 0)}</div>
                    <span class="text-gray-400 text-sm font-bold">(${product.reviews_count})</span>
                </div>
            </div>
        </div>
    `;
}

export function getStars(rating) {
    let stars = "";
    for (let i = 1; i <= 5; i++) {
        stars += i <= rating ? "★" : "☆";
    }
    return stars;
}
