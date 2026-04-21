@extends('layout.admin')
@section('title', 'Orders Management')
@section('page_title', 'Orders Management')
@section('admin_content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h2 class="text-2xl font-bold">Orders</h2>
            <button id="addOrderBtn" class="bg-[#DB4444] text-white px-4 py-2 rounded hover:bg-red-600 transition">
                Create Order
            </button>
        </div>

        <!-- Filters -->
        <div class="bg-white p-4 rounded shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <input type="text" id="searchInput" placeholder="Search orders..." class="border rounded px-3 py-2">
                <select id="statusFilter" class="border rounded px-3 py-2">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="confirmed">Confirmed</option>
                    <option value="shipped">Shipped</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <input type="date" id="dateFrom" class="border rounded px-3 py-2">
                <input type="date" id="dateTo" class="border rounded px-3 py-2">
                <button id="filterBtn" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Filter</button>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="bg-white rounded shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-400">
                        <tr>
                            <th class="px-6 py-4">Order #</th>
                            <th class="px-6 py-4">Customer</th>
                            <th class="px-6 py-4">Total</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Payment</th>
                            <th class="px-6 py-4">Date</th>
                            <th class="px-6 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="ordersTableBody" class="divide-y divide-gray-100">
                        <!-- Orders will be loaded here -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div id="pagination" class="px-6 py-4 border-t border-gray-100">
                <!-- Pagination will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Order Details Modal -->
    <div id="orderModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 id="modalTitle" class="text-xl font-bold">Order Details</h3>
                        <button id="closeModal" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div id="orderDetails">
                        <!-- Order details will be loaded here -->
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button id="closeDetailsBtn" class="px-4 py-2 border rounded hover:bg-gray-50">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Order Modal -->
    <div id="createOrderModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold">Create New Order</h3>
                        <button id="closeCreateModal" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <form id="createOrderForm">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Customer *</label>
                                <select id="user_id" name="user_id" required class="w-full border rounded px-3 py-2">
                                    <option value="">Select Customer</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Products *</label>
                                <div id="productsContainer">
                                    <div class="product-item flex gap-2 mb-2">
                                        <select name="products[0][id]" required class="flex-1 border rounded px-3 py-2">
                                            <option value="">Select Product</option>
                                        </select>
                                        <input type="number" name="products[0][quantity]" placeholder="Qty" min="1"
                                            required class="w-20 border rounded px-3 py-2">
                                        <button type="button" class="remove-product text-red-600 hover:text-red-800 px-2">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                                <button type="button" id="addProductBtn" class="text-[#DB4444] hover:underline text-sm">+
                                    Add Product</button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium mb-1">Shipping Address *</label>
                                    <textarea id="shipping_address" name="shipping_address" rows="3" required class="w-full border rounded px-3 py-2"></textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium mb-1">Billing Address</label>
                                    <textarea id="billing_address" name="billing_address" rows="3" class="w-full border rounded px-3 py-2"></textarea>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Phone *</label>
                                <input type="text" id="phone" name="phone" required
                                    class="w-full border rounded px-3 py-2">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Notes</label>
                                <textarea id="notes" name="notes" rows="2" class="w-full border rounded px-3 py-2"></textarea>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" id="cancelCreateBtn"
                                class="px-4 py-2 border rounded hover:bg-gray-50">Cancel</button>
                            <button type="submit"
                                class="bg-[#DB4444] text-white px-4 py-2 rounded hover:bg-red-600">Create Order</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentPage = 1;
            let customers = [];
            let products = [];

            // Load initial data
            loadOrders();
            loadCustomers();
            loadProducts();

            // Event listeners
            document.getElementById('filterBtn').addEventListener('click', loadOrders);
            document.getElementById('searchInput').addEventListener('input', debounce(loadOrders, 300));
            document.getElementById('statusFilter').addEventListener('change', loadOrders);

            document.getElementById('addOrderBtn').addEventListener('click', () => openCreateModal());
            document.getElementById('closeModal').addEventListener('click', closeModal);
            document.getElementById('closeDetailsBtn').addEventListener('click', closeModal);
            document.getElementById('closeCreateModal').addEventListener('click', closeCreateModal);
            document.getElementById('cancelCreateBtn').addEventListener('click', closeCreateModal);
            document.getElementById('createOrderForm').addEventListener('submit', createOrder);

            // Add product functionality
            document.getElementById('addProductBtn').addEventListener('click', addProductField);

            function loadOrders(page = 1) {
                currentPage = page;
                const search = document.getElementById('searchInput').value;
                const status = document.getElementById('statusFilter').value;
                const dateFrom = document.getElementById('dateFrom').value;
                const dateTo = document.getElementById('dateTo').value;

                const params = new URLSearchParams({
                    page,
                    search,
                    status,
                    date_from: dateFrom,
                    date_to: dateTo,
                    per_page: 15
                });

                fetch(`/admin/api/orders?${params}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status) {
                            displayOrders(data.data.data);
                            displayPagination(data.data);
                        }
                    })
                    .catch(error => console.error('Error loading orders:', error));
            }

            function displayOrders(orders) {
                const tbody = document.getElementById('ordersTableBody');
                tbody.innerHTML = '';

                if (orders.length === 0) {
                    tbody.innerHTML =
                        '<tr><td colspan="7" class="px-6 py-10 text-center text-gray-500">No orders found.</td></tr>';
                    return;
                }

                orders.forEach(order => {
                    const statusClass = getStatusClass(order.status);
                    const paymentClass = order.payment_status === 'completed' ?
                        'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700';

                    tbody.innerHTML += `
                <tr>
                    <td class="px-6 py-4 font-medium">${order.order_number}</td>
                    <td class="px-6 py-4">
  ${
    order.user
      ? `${order.user.first_name || ''} ${order.user.last_name || ''}`.trim() || 'Guest'
      : `${order.customer_name}`
  }
</td>
                    <td class="px-6 py-4 font-medium">$${parseFloat(order.total_amount).toFixed(2)}</td>
                    <td class="px-6 py-4">
                        <select onchange="updateStatus(${order.id}, this.value)" class="border rounded px-2 py-1 text-xs ${statusClass.replace('text-', 'border-').replace('bg-', 'border-')}">
                            <option value="pending" ${order.status === 'pending' ? 'selected' : ''}>Pending</option>
                            <option value="confirmed" ${order.status === 'confirmed' ? 'selected' : ''}>Confirmed</option>
                            <option value="shipped" ${order.status === 'shipped' ? 'selected' : ''}>Shipped</option>
                            <option value="delivered" ${order.status === 'delivered' ? 'selected' : ''}>Delivered</option>
                            <option value="cancelled" ${order.status === 'cancelled' ? 'selected' : ''}>Cancelled</option>
                        </select>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs ${paymentClass}">${order.payment_status}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">${new Date(order.created_at).toLocaleDateString()}</td>
                    <td class="px-6 py-4">
                        <button onclick="viewOrder(${order.id})" class="text-blue-600 hover:text-blue-800 mr-2">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="deleteOrder(${order.id})" class="text-red-600 hover:text-red-800">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
                });
            }

            function displayPagination(data) {
                const pagination = document.getElementById('pagination');
                pagination.innerHTML = '';

                if (data.last_page <= 1) return;

                let html = '<div class="flex justify-between items-center">';

                if (data.current_page > 1) {
                    html +=
                        `<button onclick="loadOrders(${data.current_page - 1})" class="px-3 py-1 border rounded hover:bg-gray-50">Previous</button>`;
                } else {
                    html += '<span class="px-3 py-1 text-gray-400">Previous</span>';
                }

                html += '<div class="flex gap-1">';
                for (let i = Math.max(1, data.current_page - 2); i <= Math.min(data.last_page, data.current_page +
                        2); i++) {
                    const activeClass = i === data.current_page ? 'bg-[#DB4444] text-white' : 'hover:bg-gray-50';
                    html +=
                        `<button onclick="loadOrders(${i})" class="px-3 py-1 border rounded ${activeClass}">${i}</button>`;
                }
                html += '</div>';

                if (data.current_page < data.last_page) {
                    html +=
                        `<button onclick="loadOrders(${data.current_page + 1})" class="px-3 py-1 border rounded hover:bg-gray-50">Next</button>`;
                } else {
                    html += '<span class="px-3 py-1 text-gray-400">Next</span>';
                }

                html += '</div>';
                pagination.innerHTML = html;
            }

            function loadCustomers() {
                fetch('/admin/api/customers?per_page=1000')
                    .then(response => response.json())
                    .then(data => {
                        if (data.status) {
                            customers = data.data.data;
                            populateCustomerSelect();
                        }
                    })
                    .catch(error => console.error('Error loading customers:', error));
            }

            function loadProducts() {
                fetch('/admin/api/products?per_page=1000')
                    .then(response => response.json())
                    .then(data => {
                        if (data.status) {
                            products = data.data.data;
                            populateProductSelects();
                        }
                    })
                    .catch(error => console.error('Error loading products:', error));
            }

            function populateCustomerSelect() {
                const select = document.getElementById('user_id');
                select.innerHTML = '<option value="">Select Customer</option>';
                customers.forEach(customer => {
                    select.innerHTML +=
                        `<option value="${customer.id}">${customer.name} (${customer.email})</option>`;
                });
            }

            function populateProductSelects() {
                const selects = document.querySelectorAll('select[name*="products"][name*="[id]"]');
                selects.forEach(select => {
                    select.innerHTML = '<option value="">Select Product</option>';
                    products.forEach(product => {
                        select.innerHTML +=
                            `<option value="${product.id}">${product.name} - $${product.price}</option>`;
                    });
                });
            }

            function openCreateModal() {
                document.getElementById('createOrderModal').classList.remove('hidden');
            }

            function closeCreateModal() {
                document.getElementById('createOrderModal').classList.add('hidden');
                document.getElementById('createOrderForm').reset();
                // Reset products container to single item
                document.getElementById('productsContainer').innerHTML = `
            <div class="product-item flex gap-2 mb-2">
                <select name="products[0][id]" required class="flex-1 border rounded px-3 py-2">
                    <option value="">Select Product</option>
                </select>
                <input type="number" name="products[0][quantity]" placeholder="Qty" min="1" required class="w-20 border rounded px-3 py-2">
                <button type="button" class="remove-product text-red-600 hover:text-red-800 px-2">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        `;
                populateProductSelects();
            }

            function closeModal() {
                document.getElementById('orderModal').classList.add('hidden');
            }

            function addProductField() {
                const container = document.getElementById('productsContainer');
                const index = container.children.length;
                const div = document.createElement('div');
                div.className = 'product-item flex gap-2 mb-2';
                div.innerHTML = `
            <select name="products[${index}][id]" required class="flex-1 border rounded px-3 py-2">
                <option value="">Select Product</option>
            </select>
            <input type="number" name="products[${index}][quantity]" placeholder="Qty" min="1" required class="w-20 border rounded px-3 py-2">
            <button type="button" class="remove-product text-red-600 hover:text-red-800 px-2">
                <i class="fas fa-trash"></i>
            </button>
        `;
                container.appendChild(div);
                populateProductSelects();

                // Add remove functionality
                div.querySelector('.remove-product').addEventListener('click', function() {
                    div.remove();
                });
            }

            function createOrder(e) {
                e.preventDefault();

                const formData = new FormData(e.target);
                const data = {
                    user_id: formData.get('user_id'),
                    products: [],
                    shipping_address: formData.get('shipping_address'),
                    billing_address: formData.get('billing_address'),
                    phone: formData.get('phone'),
                    notes: formData.get('notes'),
                };

                // Collect products
                for (let [key, value] of formData.entries()) {
                    if (key.startsWith('products[') && key.endsWith('][id]')) {
                        const index = key.match(/\[(\d+)\]/)[1];
                        const quantity = formData.get(`products[${index}][quantity]`);
                        if (value && quantity) {
                            data.products.push({
                                id: value,
                                quantity: parseInt(quantity)
                            });
                        }
                    }
                }

                fetch('/admin/api/orders', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.status) {
                            closeCreateModal();
                            loadOrders();
                            alert(result.message);
                        } else {
                            alert('Error creating order');
                        }
                    })
                    .catch(error => {
                        console.error('Error creating order:', error);
                        alert('Error creating order');
                    });
            }

            function getStatusClass(status) {
                switch (status) {
                    case 'delivered':
                        return 'bg-green-100 text-green-700';
                    case 'shipped':
                        return 'bg-blue-100 text-blue-700';
                    case 'confirmed':
                        return 'bg-indigo-100 text-indigo-700';
                    case 'pending':
                        return 'bg-yellow-100 text-yellow-700';
                    case 'cancelled':
                        return 'bg-red-100 text-red-700';
                    default:
                        return 'bg-gray-100 text-gray-700';
                }
            }

            window.viewOrder = function(id) {
                fetch(`/admin/api/orders/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status) {
                            displayOrderDetails(data.data);
                            document.getElementById('orderModal').classList.remove('hidden');
                        }
                    })
                    .catch(error => console.error('Error loading order:', error));
            };

            window.updateStatus = function(id, status) {
                fetch(`/admin/api/orders/${id}/status`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                        },
                        body: JSON.stringify({
                            status
                        })
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.status) {
                            loadOrders(currentPage);
                        }
                    })
                    .catch(error => console.error('Error updating status:', error));
            };

            window.deleteOrder = function(id) {
                if (confirm('Are you sure you want to delete this order?')) {
                    fetch(`/admin/api/orders/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    ?.content || '',
                                'Accept': 'application/json',
                            }
                        })
                        .then(response => response.json())
                        .then(result => {
                            if (result.status) {
                                loadOrders(currentPage);
                                alert(result.message);
                            }
                        })
                        .catch(error => console.error('Error deleting order:', error));
                }
            };

            function displayOrderDetails(order) {
                const details = document.getElementById('orderDetails');
                const statusClass = getStatusClass(order.status);

                details.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-bold mb-2">Order Information</h4>
                    <p><strong>Order #:</strong> ${order.order_number}</p>
                   <p>
  <strong>Customer:</strong> 
  ${order.user ? `${order.user.first_name} ${order.user.last_name}` : `${order.customer_name}`}
</p>
                    <p><strong>Email:</strong> ${order.user?.email || `${order.customer_email}`}</p>
                    <p><strong>Phone:</strong> ${order.phone}</p>
                    <p><strong>Status:</strong> <span class="px-2 py-1 rounded text-xs ${statusClass}">${order.status}</span></p>
                    <p><strong>Payment:</strong> ${order.payment_status}</p>
                    <p><strong>Order Date:</strong> ${new Date(order.created_at).toLocaleString()}
                </div>
                <div>
                    <h4 class="font-bold mb-2">Shipping Information</h4>
                    <p><strong>Shipping Address:</strong></p>
                    <p class="whitespace-pre-line">${order.shipping_address}</p>
                    ${order.billing_address ? `<p><strong>Billing Address:</strong></p><p class="whitespace-pre-line">${order.billing_address}</p>` : ''}
                    ${order.notes ? `<p><strong>Notes:</strong></p><p class="whitespace-pre-line">${order.notes}</p>` : ''}
                </div>
            </div>

            <div class="mt-6">
                <h4 class="font-bold mb-2">Order Items</h4>
                <div class="overflow-x-auto">
                    <table class="w-full border">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left">Product</th>
                                <th class="px-4 py-2 text-left">Quantity</th>
                                <th class="px-4 py-2 text-left">Price</th>
                                <th class="px-4 py-2 text-left">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${order.items.map(item => `
                                                                                                    <tr class="border-t">
                                                                                                        <td class="px-4 py-2">${item.product?.name || 'Product'}</td>
                                                                                                        <td class="px-4 py-2">${item.quantity}</td>
                                                                                                        <td class="px-4 py-2">$${parseFloat(item.price).toFixed(2)}</td>
                                                                                                        <td class="px-4 py-2">$${(item.price * item.quantity).toFixed(2)}</td>
                                                                                                    </tr>
                                                                                                `).join('')}
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-4 py-2 font-bold text-right">Subtotal:</td>
                                <td class="px-4 py-2 font-bold">$${(order.total_amount - order.tax - order.shipping_fee).toFixed(2)}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="px-4 py-2 font-bold text-right">Tax:</td>
                                <td class="px-4 py-2 font-bold">$${parseFloat(order.tax).toFixed(2)}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="px-4 py-2 font-bold text-right">Shipping:</td>
                                <td class="px-4 py-2 font-bold">$${parseFloat(order.shipping_fee).toFixed(2)}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="px-4 py-2 font-bold text-right">Total:</td>
                                <td class="px-4 py-2 font-bold">$${parseFloat(order.total_amount).toFixed(2)}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        `;
            }

            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }
        });
    </script>
@endsection
