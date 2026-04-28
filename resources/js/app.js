import "./bootstrap";
// 1. Find the meta tag
const userIdMeta = document.head.querySelector('meta[name="user-id"]');

// 2. Only run the code if the meta tag exists AND has a value (user is logged in)
if (userIdMeta && userIdMeta.content) {
    const userId = userIdMeta.content;

    window.Echo.private(`orders.${userId}`).listen(
        "OrderStatusUpdated",
        (data) => {
            console.log("Status changed:", data);
            alert(data.message);
        },
    );
} else {
    console.log("User is not logged in. WebSocket listener not started.");
}
