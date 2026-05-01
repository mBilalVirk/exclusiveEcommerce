// resources/js/utils/search.js

export function setupLiveSearch(inputId, resultId) {
    const input = document.getElementById(inputId);
    const resultsBox = document.getElementById(resultId);

    if (!input || !resultsBox) return;

    input.addEventListener("keyup", function () {
        let query = this.value;

        if (query.length < 2) {
            resultsBox.classList.add("hidden");
            return;
        }

        fetch(`/live-search?query=${query}`)
            .then((res) => res.json())
            .then((data) => {
                resultsBox.innerHTML = "";

                if (data.length === 0) {
                    resultsBox.innerHTML =
                        '<p class="p-2 text-gray-500">No results</p>';
                } else {
                    data.forEach((product) => {
                        resultsBox.innerHTML += `
                            <a href="/show/${product.id}" class="flex items-center gap-3 p-2 hover:bg-gray-100">
                                <img src="/${product.image}" class="w-10 h-10 object-cover rounded">
                                <div>
                                    <p class="text-sm font-medium">${product.name}</p>
                                    <p class="text-xs text-gray-500">$${product.price}</p>
                                </div>
                            </a>`;
                    });
                }
                resultsBox.classList.remove("hidden");
            });
    });

    // Hide when clicking outside
    document.addEventListener("click", function (e) {
        if (!input.contains(e.target) && !resultsBox.contains(e.target)) {
            resultsBox.classList.add("hidden");
        }
    });
}
