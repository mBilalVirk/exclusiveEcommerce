@extends('layout.admin')
@section('title', 'Admin Management')
@section('page_title', 'Admin Management')
@section('admin_content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h2 class="text-2xl font-bold">Admins</h2>
        </div>

        <!-- Filters -->
        <div class="bg-white p-4 rounded shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="text" id="searchInput" placeholder="Search admins..." class="border rounded px-3 py-2">
                <select id="sortBy" class="border rounded px-3 py-2">
                    <option value="created_at">Created Date</option>
                    <option value="first_name">First Name</option>
                    <option value="email">Email</option>
                </select>
                <select id="sortOrder" class="border rounded px-3 py-2">
                    <option value="desc">Descending</option>
                    <option value="asc">Ascending</option>
                </select>
                <button id="filterBtn" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">Filter</button>
            </div>
        </div>

        <!-- Admins Table -->
        <div class="bg-white rounded shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-400">
                        <tr>
                            <th class="px-6 py-4">Name</th>
                            <th class="px-6 py-4">Email</th>
                            <th class="px-6 py-4">Phone</th>
                            <th class="px-6 py-4">Role</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Joined</th>
                            <th class="px-6 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="adminsTableBody" class="divide-y divide-gray-100">
                        <!-- Admins will be loaded here -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div id="pagination" class="px-6 py-4 border-t border-gray-100">
                <!-- Pagination will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Admin Details Modal -->
    <div id="adminModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-4xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 id="modalTitle" class="text-xl font-bold">Admin Details</h3>
                        <button id="closeModal" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div id="adminDetails">
                        <!-- Admin details will be loaded here -->
                    </div>

                    <div class="mt-6 flex justify-end gap-3">
                        <button id="editAdminBtn" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Edit
                            Admin</button>
                        <button id="closeDetailsBtn" class="px-4 py-2 border rounded hover:bg-gray-50">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Admin Modal -->
    <div id="editAdminModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold">Edit Admin</h3>
                        <button id="closeEditModal" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <form id="editAdminForm">
                        <input type="hidden" id="editAdminId" name="id">

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
                                class="bg-[#DB4444] text-white px-4 py-2 rounded hover:bg-red-600">Update Admin</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentPage = 1;
            let currentAdmin = null;

            // Load admins
            loadAdmins();

            // Event listeners
            document.getElementById('searchInput').addEventListener('input', debounce(loadAdmins, 300));
            document.getElementById('sortBy').addEventListener('change', loadAdmins);
            document.getElementById('sortOrder').addEventListener('change', loadAdmins);
            document.getElementById('filterBtn').addEventListener('click', loadAdmins);

            // Modal event listeners
            document.getElementById('closeModal').addEventListener('click', closeModal);
            document.getElementById('closeDetailsBtn').addEventListener('click', closeModal);
            document.getElementById('editAdminBtn').addEventListener('click', openEditModal);

            document.getElementById('closeEditModal').addEventListener('click', closeEditModal);
            document.getElementById('cancelEditBtn').addEventListener('click', closeEditModal);
            document.getElementById('editAdminForm').addEventListener('submit', updateAdmin);

            function loadAdmins(page = 1) {
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

                fetch(`/admin/api/admins?${params}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status) {
                            displayAdmins(data.data.data);
                            displayPagination(data.data);
                        }
                    })
                    .catch(error => console.error('Error loading admins:', error));
            }

            function displayAdmins(admins) {
                const tbody = document.getElementById('adminsTableBody');
                tbody.innerHTML = '';

                if (admins.length === 0) {
                    tbody.innerHTML =
                        '<tr><td colspan="7" class="px-6 py-10 text-center text-gray-500">No admins found.</td></tr>';
                    return;
                }

                admins.forEach(admin => {
                    const statusClass = admin.is_active ? 'bg-green-100 text-green-700' :
                        'bg-red-100 text-red-700';
                    const statusText = admin.is_active ? 'Active' : 'Inactive';
                    const roleText = admin.role === 'super-admin' ? 'Super Admin' : 'Admin';

                    tbody.innerHTML += `
                <tr>
                    <td class="px-6 py-4 font-medium">${admin.first_name} ${admin.last_name}</td>
                    <td class="px-6 py-4">${admin.email}</td>
                    <td class="px-6 py-4">${admin.phone || 'N/A'}</td>
                    <td class="px-6 py-4">${roleText}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs ${statusClass}">${statusText}</span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">${new Date(admin.created_at).toLocaleDateString()}</td>
                    <td class="px-6 py-4">
                        <button onclick="viewAdmin(${admin.id})" class="text-blue-600 hover:text-blue-800">
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
                        `<button onclick="loadAdmins(${data.current_page - 1})" class="px-3 py-1 border rounded hover:bg-gray-50">Previous</button>`;
                } else {
                    html += '<span class="px-3 py-1 text-gray-400">Previous</span>';
                }

                html += '<div class="flex gap-1">';
                for (let i = Math.max(1, data.current_page - 2); i <= Math.min(data.last_page, data.current_page +
                        2); i++) {
                    const activeClass = i === data.current_page ? 'bg-[#DB4444] text-white' : 'hover:bg-gray-50';
                    html +=
                        `<button onclick="loadAdmins(${i})" class="px-3 py-1 border rounded ${activeClass}">${i}</button>`;
                }
                html += '</div>';

                if (data.current_page < data.last_page) {
                    html +=
                        `<button onclick="loadAdmins(${data.current_page + 1})" class="px-3 py-1 border rounded hover:bg-gray-50">Next</button>`;
                } else {
                    html += '<span class="px-3 py-1 text-gray-400">Next</span>';
                }

                html += '</div>';
                pagination.innerHTML = html;
            }

            function closeModal() {
                document.getElementById('adminModal').classList.add('hidden');
            }

            function closeEditModal() {
                document.getElementById('editAdminModal').classList.add('hidden');
            }

            function openEditModal() {
                if (!currentAdmin) return;

                document.getElementById('editAdminId').value = currentAdmin.id;
                document.getElementById('editFirstName').value = currentAdmin.first_name || '';
                document.getElementById('editLastName').value = currentAdmin.last_name || '';
                document.getElementById('editEmail').value = currentAdmin.email;
                document.getElementById('editPhone').value = currentAdmin.phone || '';
                document.getElementById('editAddress').value = currentAdmin.address || '';
                document.getElementById('editCity').value = currentAdmin.city || '';
                document.getElementById('editCountry').value = currentAdmin.country || '';
                document.getElementById('editIsActive').checked = currentAdmin.is_active;

                document.getElementById('editAdminModal').classList.remove('hidden');
            }

            function updateAdmin(e) {
                e.preventDefault();

                const formData = new FormData(e.target);
                const adminId = formData.get('id');
                const data = Object.fromEntries(formData);

                data.is_active = document.getElementById('editIsActive').checked;
                console.log('Updating admin with data:', data);
                fetch(`/admin/api/admins/${adminId}`, {
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
                            loadAdmins(currentPage);
                            alert(result.message);
                        } else {
                            alert('Error updating admin');
                        }
                    })
                    .catch(error => {
                        console.error('Error updating admin:', error);
                        alert('Error updating admin');
                    });
            }

            window.viewAdmin = function(id) {
                fetch(`/admin/api/admins/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status) {
                            currentAdmin = data.data.admin;
                            displayAdminDetails(data.data);
                            document.getElementById('adminModal').classList.remove('hidden');
                        }
                    })
                    .catch(error => console.error('Error loading admin:', error));
            };

            function displayAdminDetails(data) {
                const admin = data.admin;
                const roleText = admin.role === 'super-admin' ? 'Super Admin' : 'Admin';

                const details = document.getElementById('adminDetails');

                details.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="font-bold mb-4">Admin Information</h4>
                    <div class="space-y-2">
                        <p><strong>Name:</strong> ${admin.first_name} ${admin.last_name}</p>
                        <p><strong>Email:</strong> ${admin.email}</p>
                        <p><strong>Phone:</strong> ${admin.phone || 'N/A'}</p>
                        <p><strong>Address:</strong> ${admin.address || 'N/A'}</p>
                        <p><strong>City:</strong> ${admin.city || 'N/A'}</p>
                        <p><strong>Country:</strong> ${admin.country || 'N/A'}</p>
                        <p><strong>Role:</strong> <span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-700">${roleText}</span></p>
                        <p><strong>Status:</strong> <span class="px-2 py-1 rounded text-xs ${admin.is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}">${admin.is_active ? 'Active' : 'Inactive'}</span></p>
                        <p><strong>Joined:</strong> ${new Date(admin.created_at).toLocaleDateString()}</p>
                    </div>
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
