export function showToast(message, type = "success") {
    const container = document.getElementById("toast-container");

    // Create toast element
    const toast = document.createElement("div");

    // Set colors based on type
    const bgColor = type === "success" ? "bg-green-500" : "bg-red-500";

    // Tailwind classes for styling and animation
    toast.className = `${bgColor} text-white px-6 py-3 rounded shadow-lg transform transition-all duration-300 translate-x-full opacity-0 flex items-center gap-2`;
    toast.innerHTML = `
        <span>${type === "success" ? '<i class="fa fa-info-circle"></i>' : '<i class="fa-solid fa-circle-xmark"></i>'}</span>
        <span class="text-sm font-medium">${message}</span>
    `;

    container.appendChild(toast);

    // Slide in
    setTimeout(() => {
        toast.classList.remove("translate-x-full", "opacity-0");
    }, 10);

    // Slide out and remove after 3 seconds
    setTimeout(() => {
        toast.classList.add("translate-x-full", "opacity-0");
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}
