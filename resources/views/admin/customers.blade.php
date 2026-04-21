@extends('layout.admin')
@section('title', 'Customer Management')
@section('page_title', 'Customer Management')
@section('admin_content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h2 class="text-2xl font-bold">Customers</h2>
            <div class="flex gap-2">
                <button id="segmentationBtn" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">
                    View Segmentation
                </button>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white p-4 rounded shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="text" id="searchInput" placeholder="Search customers..." class="border rounded px-3 py-2">
                <select id="sortBy" class="border rounded px-3 py-2">
                    <option value="created_at">Registration Date</option>
                    <option value="name">Name</option>
                    <option value="email">Email</option>
                    <option value="orders_count">Order Count</option>
                </select>
                <select id="sortOrder" class="border rounded px-3 py-2">
                    <option value="desc">Descending</option>
                    <option value="asc">Ascending</option>
                </select>
                <button id="filterBtn" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Filter</button>
            </div>
        </div>

        <!-- Customers Table -->
        <div class="bg-white rounded shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-400">
                        <tr>
                            <th class="px-6 py-4">Name</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Phone</th>
                            <th class="px-6 py-4">Orders</th>
                            <th class="px-6 py-4">Total Spent</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Joined</th>
                            <th class="px-6 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="customersTableBody" class="divide-y divide-gray-100">
                        <!-- Customers will be loaded here -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div id="pagination" class="px-6 py-4 border-t border-gray-100">
                <!-- Pagination will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Customer Details Modal -->
    <div id="customerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 id="modalTitle" class="text-xl font-bold">Customer Details</h3>
                        <button id="closeModal" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div id="customerDetails">
                        <!-- Customer details will be loaded here -->
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button id="editCustomerBtn" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Edit
                            Customer</button>
                        <button id="sendMessageBtn"
                            class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Send Message</button>
                        <button id="closeDetailsBtn" class="px-4 py-2 border rounded hover:bg-gray-50">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Customer Modal -->
    <div id="editCustomerModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold">Edit Customer</h3>
                        <button id="closeEditModal" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <form id="editCustomerForm">
                        <input type="hidden" id="editCustomerId" name="id">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">First Name</label>
                                <input type="text" id="editFirstName" name="first_name"
                                    class="w-full border rounded px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Last Name</label>
                                <input type="text" id="editLastName" name="last_name"
                                    class="w-full border rounded px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Email</label>
                                <input type="email" id="editEmail" name="email" class="w-full border rounded px-3 py-2">
                            </div>
                            <div>
                                <label class="block text-sm font-medium mb-1">Phone</label>
                                <input type="text" id="editPhone" name="phone" class="w-full border rounded px-3 py-2">
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium mb-1">Address</label>
                            <textarea id="editAddress" name="address" rows="3" class="w-full border rounded px-3 py-2"></textarea>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium mb-1">City</label>
                            <input type="text" id="editCity" name="city" class="w-full border rounded px-3 py-2">
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Country</label>
                                <input type="text" id="editCountry" name="country"
                                    class="w-full border rounded px-3 py-2">
                            </div>
                            <div>
                                <label class="flex items-center">
                                    <input type="checkbox" id="editIsActive" name="is_active" class="mr-2">
                                    <span class="text-sm">Active Account</span>
                                </label>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" id="cancelEditBtn"
                                class="px-4 py-2 border rounded hover:bg-gray-50">Cancel</button>
                            <button type="submit"
                                class="bg-[#DB4444] text-white px-4 py-2 rounded hover:bg-red-600">Update Customer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Send Message Modal -->
    <div id="messageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-2xl w-full">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold">Send Message</h3>
                        <button id="closeMessageModal" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <form id="sendMessageForm">
                        <input type="hidden" id="messageCustomerId" name="customer_id">

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Subject *</label>
                            <input type="text" id="messageSubject" name="subject" required
                                class="w-full border rounded px-3 py-2">
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Message Type *</label>
                            <select id="messageType" name="type" required class="w-full border rounded px-3 py-2">
                                <option value="email">Email</option>
                                <option value="sms">SMS</option>
                                <option value="notification">In-App Notification</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Message *</label>
                            <textarea id="messageContent" name="message" rows="6" required class="w-full border rounded px-3 py-2"></textarea>
                        </div>

                        <div class="flex justify-end gap-3">
                            <button type="button" id="cancelMessageBtn"
                                class="px-4 py-2 border rounded hover:bg-gray-50">Cancel</button>
                            <button type="submit" class="bg-[#DB4444] text-white px-4 py-2 rounded hover:bg-red-600">Send
                                Message</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Segmentation Modal -->
    <div id="segmentationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold">Customer Segmentation</h3>
                        <button id="closeSegmentationModal" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div id="segmentationContent">
                        <!-- Segmentation data will be loaded here -->
                    </div>

                    <div class="mt-6 flex justify-end">
                        <button id="closeSegmentationBtn" class="px-4 py-2 border rounded hover:bg-gray-50">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentPage = 1;
            let currentCustomer = null;

            // Load customers
            loadCustomers();

            // Event listeners
            document.getElementById('searchInput').addEventListener('input', debounce(loadCustomers, 300));
            document.getElementById('sortBy').addEventListener('change', loadCustomers);
            document.getElementById('sortOrder').addEventListener('change', loadCustomers);
            document.getElementById('filterBtn').addEventListener('click', loadCustomers);
            document.getElementById('segmentationBtn').addEventListener('click', loadSegmentation);

            // Modal event listeners
            document.getElementById('closeModal').addEventListener('click', closeModal);
            document.getElementById('closeDetailsBtn').addEventListener('click', closeModal);
            document.getElementById('editCustomerBtn').addEventListener('click', openEditModal);
            document.getElementById('sendMessageBtn').addEventListener('click', openMessageModal);

            document.getElementById('closeEditModal').addEventListener('click', closeEditModal);
            document.getElementById('cancelEditBtn').addEventListener('click', closeEditModal);
            document.getElementById('editCustomerForm').addEventListener('submit', updateCustomer);

            document.getElementById('closeMessageModal').addEventListener('click', closeMessageModal);
            document.getElementById('cancelMessageBtn').addEventListener('click', closeMessageModal);
            document.getElementById('sendMessageForm').addEventListener('submit', sendMessage);

            document.getElementById('closeSegmentationModal').addEventListener('click', closeSegmentationModal);
            document.getElementById('closeSegmentationBtn').addEventListener('click', closeSegmentationModal);

            function loadCustomers(page = 1) {
                currentPage = page;
                const search = document.getElementById('searchInput').value;
                const sortBy = document.getElementById('sortBy').value;
                const sortOrder = document.getElementById('sortOrder').value;

                const params = new URLSearchParams({
                    page,
                    search,
                    sort_by: sortBy,
                    sort_order: sortOrder,
                    per_page: 15
                });

                fetch(`/admin/api/customers?${params}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status) {
                            displayCustomers(data.data.data);
                            displayPagination(data.data);
                        }
                    })
                    .catch(error => console.error('Error loading customers:', error));
            }

            function displayCustomers(customers) {
                const tbody = document.getElementById('customersTableBody');
                tbody.innerHTML = '';

                if (customers.length === 0) {
                    tbody.innerHTML =
                        '<tr><td colspan="8" class="px-6 py-10 text-center text-gray-500">No customers found.</td></tr>';
                    return;
                }

                customers.forEach(customer => {
                    const statusClass = customer.is_active ? 'bg-green-100 text-green-700' :
                        'bg-red-100 text-red-700';
                    const statusText = customer.is_active ? 'Active' : 'Inactive';

                    tbody.innerHTML += `
                <tr>
                    <td class="px-6 py-4 font-medium">${customer.first_name} ${customer.last_name}</td>
                    <td class="px-6 py-4">${customer.email}</td>
                    <td class="px-6 py-4">${customer.phone || 'N/A'}</td>
                    <td class="px-6 py-4">${customer.orders_count || 0}</td>
                    <td class="px-6 py-4">$${parseFloat(customer.orders_sum_total_amount || 0).toFixed(2)}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs ${statusClass}">${statusText}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">${new Date(customer.created_at).toLocaleDateString()}</td>
                    <td class="px-6 py-4">
                        <button onclick="viewCustomer(${customer.id})" class="text-blue-600 hover:text-blue-800">
                            <i class="fas fa-eye"></i>
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
                        `<button onclick="loadCustomers(${data.current_page - 1})" class="px-3 py-1 border rounded hover:bg-gray-50">Previous</button>`;
                } else {
                    html += '<span class="px-3 py-1 text-gray-400">Previous</span>';
                }

                html += '<div class="flex gap-1">';
                for (let i = Math.max(1, data.current_page - 2); i <= Math.min(data.last_page, data.current_page +
                        2); i++) {
                    const activeClass = i === data.current_page ? 'bg-[#DB4444] text-white' : 'hover:bg-gray-50';
                    html +=
                        `<button onclick="loadCustomers(${i})" class="px-3 py-1 border rounded ${activeClass}">${i}</button>`;
                }
                html += '</div>';

                if (data.current_page < data.last_page) {
                    html +=
                        `<button onclick="loadCustomers(${data.current_page + 1})" class="px-3 py-1 border rounded hover:bg-gray-50">Next</button>`;
                } else {
                    html += '<span class="px-3 py-1 text-gray-400">Next</span>';
                }

                html += '</div>';
                pagination.innerHTML = html;
            }

            function loadSegmentation() {
                fetch('/admin/api/customers/segmentation')
                    .then(response => response.json())
                    .then(data => {
                        if (data.status) {
                            displaySegmentation(data.data);
                            document.getElementById('segmentationModal').classList.remove('hidden');
                        }
                    })
                    .catch(error => console.error('Error loading segmentation:', error));
            }

            function displaySegmentation(data) {
                const content = document.getElementById('segmentationContent');

                content.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-green-50 p-4 rounded-lg border-l-4 border-green-500">
                    <h4 class="font-bold text-green-800">VIP Customers</h4>
                    <p class="text-2xl font-bold text-green-600">${data.vip_customers.count}</p>
                    <p class="text-sm text-green-700">Total Revenue: $${data.vip_customers.total_revenue.toFixed(2)}</p>
                </div>

                <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500">
                    <h4 class="font-bold text-blue-800">Regular Customers</h4>
                    <p class="text-2xl font-bold text-blue-600">${data.regular_customers.count}</p>
                    <p class="text-sm text-blue-700">Total Revenue: $${data.regular_customers.total_revenue.toFixed(2)}</p>
                </div>

                <div class="bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-500">
                    <h4 class="font-bold text-yellow-800">New Customers</h4>
                    <p class="text-2xl font-bold text-yellow-600">${data.new_customers.count}</p>
                    <p class="text-sm text-yellow-700">Joined in last 30 days</p>
                </div>

                <div class="bg-red-50 p-4 rounded-lg border-l-4 border-red-500">
                    <h4 class="font-bold text-red-800">Inactive Customers</h4>
                    <p class="text-2xl font-bold text-red-600">${data.inactive_customers.count}</p>
                    <p class="text-sm text-red-700">No recent activity</p>
                </div>
            </div>

            <div class="mt-8">
                <h4 class="font-bold mb-4">Segmentation Criteria</h4>
                <div class="bg-gray-50 p-4 rounded">
                    <ul class="space-y-2 text-sm">
                        <li><strong>VIP Customers:</strong> Spent more than $5,000</li>
                        <li><strong>Regular Customers:</strong> Spent between $1,000 and $5,000</li>
                        <li><strong>New Customers:</strong> Registered within the last 30 days</li>
                        <li><strong>Inactive Customers:</strong> No orders in the last 90 days</li>
                    </ul>
                </div>
            </div>
        `;
            }

            function closeModal() {
                document.getElementById('customerModal').classList.add('hidden');
            }

            function closeEditModal() {
                document.getElementById('editCustomerModal').classList.add('hidden');
            }

            function closeMessageModal() {
                document.getElementById('messageModal').classList.add('hidden');
            }

            function closeSegmentationModal() {
                document.getElementById('segmentationModal').classList.add('hidden');
            }

            function openEditModal() {
                if (!currentCustomer) return;

                document.getElementById('editCustomerId').value = currentCustomer.id;
                document.getElementById('editFirstName').value = currentCustomer.first_name || '';
                document.getElementById('editLastName').value = currentCustomer.last_name || '';
                document.getElementById('editEmail').value = currentCustomer.email;
                document.getElementById('editPhone').value = currentCustomer.phone || '';
                document.getElementById('editAddress').value = currentCustomer.address || '';
                document.getElementById('editCity').value = currentCustomer.city || '';
                document.getElementById('editCountry').value = currentCustomer.country || '';
                document.getElementById('editIsActive').checked = currentCustomer.is_active;

                document.getElementById('editCustomerModal').classList.remove('hidden');
            }

            function openMessageModal() {
                if (!currentCustomer) return;

                document.getElementById('messageCustomerId').value = currentCustomer.id;
                document.getElementById('sendMessageForm').reset();

                document.getElementById('messageModal').classList.remove('hidden');
            }

            function updateCustomer(e) {
                e.preventDefault();

                const formData = new FormData(e.target);
                const customerId = formData.get('id');
                const data = Object.fromEntries(formData);

                data.is_active = document.getElementById('editIsActive').checked;
                console.log('Updating customer with data:', data);
                fetch(`/admin/api/customers/${customerId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.status) {
                            closeEditModal();
                            loadCustomers(currentPage);
                            alert(result.message);
                        } else {
                            alert('Error updating customer');
                        }
                    })
                    .catch(error => {
                        console.error('Error updating customer:', error);
                        alert('Error updating customer');
                    });
            }

            function sendMessage(e) {
                e.preventDefault();

                const formData = new FormData(e.target);
                const data = Object.fromEntries(formData);

                fetch(`/admin/api/customers/${data.customer_id}/send-message`, {
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
                            closeMessageModal();
                            alert(result.message);
                        } else {
                            alert('Error sending message');
                        }
                    })
                    .catch(error => {
                        console.error('Error sending message:', error);
                        alert('Error sending message');
                    });
            }

            window.viewCustomer = function(id) {
                fetch(`/admin/api/customers/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status) {
                            currentCustomer = data.data.customer;
                            displayCustomerDetails(data.data);
                            document.getElementById('customerModal').classList.remove('hidden');
                        }
                    })
                    .catch(error => console.error('Error loading customer:', error));
            };

            function displayCustomerDetails(data) {
                const customer = data.customer;
                const stats = data.stats;
                const recentOrders = data.recent_orders;

                const details = document.getElementById('customerDetails');

                details.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-bold mb-4">Customer Information</h4>
                    <div class="space-y-2">
                        <p><strong>Name:</strong> ${customer.name}</p>
                        <p><strong>Email:</strong> ${customer.email}</p>
                        <p><strong>Phone:</strong> ${customer.phone || 'N/A'}</p>
                        <p><strong>Address:</strong> ${customer.address || 'N/A'}</p>
                        <p><strong>City:</strong> ${customer.city || 'N/A'}</p>
                        <p><strong>Country:</strong> ${customer.country || 'N/A'}</p>
                        <p><strong>Status:</strong> <span class="px-2 py-1 rounded text-xs ${customer.is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}">${customer.is_active ? 'Active' : 'Inactive'}</span></p>
                        <p><strong>Joined:</strong> ${new Date(customer.created_at).toLocaleDateString()}</p>
                    </div>
                </div>

                <div>
                    <h4 class="font-bold mb-4">Customer Statistics</h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-blue-50 p-3 rounded">
                            <p class="text-sm text-blue-600">Total Orders</p>
                            <p class="text-xl font-bold text-blue-800">${stats.total_orders}</p>
                        </div>
                        <div class="bg-green-50 p-3 rounded">
                            <p class="text-sm text-green-600">Total Spent</p>
                            <p class="text-xl font-bold text-green-800">$${parseFloat(stats.total_spent).toFixed(2)}</p>
                        </div>
                        <div class="bg-purple-50 p-3 rounded">
                            <p class="text-sm text-purple-600">Avg Order Value</p>
                            <p class="text-xl font-bold text-purple-800">$${parseFloat(stats.avg_order_value).toFixed(2)}</p>
                        </div>
                        <div class="bg-orange-50 p-3 rounded">
                            <p class="text-sm text-orange-600">Last Order</p>
                            <p class="text-sm font-bold text-orange-800">${stats.last_order_date ? new Date(stats.last_order_date).toLocaleDateString() : 'Never'}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <h4 class="font-bold mb-4">Recent Orders</h4>
                ${recentOrders.length > 0 ? `
                                                        <div class="overflow-x-auto">
                                                            <table class="w-full border">
                                                                <thead class="bg-gray-50">
                                                                    <tr>
                                                                        <th class="px-4 py-2 text-left">Order #</th>
                                                                        <th class="px-4 py-2 text-left">Date</th>
                                                                        <th class="px-4 py-2 text-left">Total</th>
                                                                        <th class="px-4 py-2 text-left">Status</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    ${recentOrders.map(order => `
                                    <tr class="border-t">
                                        <td class="px-4 py-2">${order.order_number}</td>
                                        <td class="px-4 py-2">${new Date(order.created_at).toLocaleDateString()}</td>
                                        <td class="px-4 py-2">$${parseFloat(order.total_amount).toFixed(2)}</td>
                                        <td class="px-4 py-2">
                                            <span class="px-2 py-1 rounded text-xs ${getStatusClass(order.status)}">${order.status}</span>
                                        </td>
                                    </tr>
                                `).join('')}
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    ` : '<p class="text-gray-500">No orders found for this customer.</p>'}
            </div>
        `;
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
