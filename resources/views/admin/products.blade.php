@extends('layout.admin')
@section('title', 'Products Management')
@section('page_title', 'Products Management')
@section('admin_content')
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <h2 class="text-2xl font-bold">Products</h2>
            <button id="addProductBtn" class="bg-[#DB4444] text-white px-4 py-2 rounded hover:bg-red-600 transition">
                Add New Product
            </button>
        </div>

        <!-- Filters -->
        <div class="bg-white p-4 rounded shadow-sm">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="text" id="searchInput" placeholder="Search products..." class="border rounded px-3 py-2">
                <select id="categoryFilter" class="border rounded px-3 py-2">
                    <option value="">All Categories</option>
                    <option value="gaming">Gaming</option>
                    <option value="sports">Sports</option>
                    <option value="pets">Pets</option>
                    <option value="furniture">Furniture</option>
                    <option value="electronic">Electronics</option>
                    <option value="computing">Computing</option>
                    <option value="beauty">Beauty</option>
                    <option value="apparel">Apparel</option>
                </select>
                <select id="sortBy" class="border rounded px-3 py-2">
                    <option value="created_at">Date Created</option>
                    <option value="name">Name</option>
                    <option value="price">Price</option>
                    <option value="stock">Stock</option>
                </select>
                <select id="sortOrder" class="border rounded px-3 py-2">
                    <option value="desc">Descending</option>
                    <option value="asc">Ascending</option>
                </select>
            </div>
        </div>

        <!-- Products Table -->
        <div class="bg-white rounded shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-400">
                        <tr>
                            <th class="px-6 py-4">Image</th>
                            <th class="px-6 py-4">Name</th>
                            <th class="px-6 py-4">Category</th>
                            <th class="px-6 py-4">Price</th>
                            <th class="px-6 py-4">Stock</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="productsTableBody" class="divide-y divide-gray-100">
                        <!-- Products will be loaded here -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div id="pagination" class="px-6 py-4 border-t border-gray-100">
                <!-- Pagination will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Add/Edit Product Modal -->
    <div id="productModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 id="modalTitle" class="text-xl font-bold">Add Product</h3>
                        <button id="closeModal" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <form id="productForm" enctype="multipart/form-data">
                        <input type="hidden" id="productId" name="id">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium mb-1">Name *</label>
                                <input type="text" id="name" name="name" required
                                    class="w-full border rounded px-3 py-2">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Category *</label>
                                <select id="category" name="category" required class="w-full border rounded px-3 py-2">

                                    <option value="">Select Category</option>
                                    <option value="gaming">Gaming</option>
                                    <option value="sports">Sports</option>
                                    <option value="pets">Pets</option>
                                    <option value="furniture">Furniture</option>
                                    <option value="electronics">Electronics</option>
                                    <option value="computing">Computing</option>
                                    <option value="beauty">Beauty</option>
                                    <option value="apparel">Apparel</option>

                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Price *</label>
                                <input type="number" id="price" name="price" step="0.01" required
                                    class="w-full border rounded px-3 py-2">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Discount Price</label>
                                <input type="number" id="discount_price" name="discount_price" step="0.01"
                                    class="w-full border rounded px-3 py-2">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Stock *</label>
                                <input type="number" id="stock" name="stock" required
                                    class="w-full border rounded px-3 py-2">
                            </div>

                            <div>
                                <label class="block text-sm font-medium mb-1">Product Image</label>
                                <input type="file" id="image" name="image" accept="image/*"
                                    class="w-full border rounded px-3 py-2">
                                <div id="imagePreviewContainer" class="mt-2 hidden">
                                    <p class="text-xs text-gray-500">Current Image:</p>
                                    <img id="currentImageDisplay" src="/"
                                        class="w-16 h-16 object-cover rounded border">
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <label class="block text-sm font-medium mb-1">Description *</label>
                            <textarea id="description" name="description" rows="4" required class="w-full border rounded px-3 py-2"></textarea>
                        </div>

                        <div class="mt-4 flex items-center gap-4">
                            <label class="flex items-center">
                                <input type="checkbox" id="is_new" name="is_new" class="mr-2">
                                <span class="text-sm">Mark as New</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" id="is_available" name="is_available" class="mr-2" checked>
                                <span class="text-sm">Available</span>
                            </label>
                        </div>

                        <div class="mt-6 flex justify-end gap-3">
                            <button type="button" id="cancelBtn"
                                class="px-4 py-2 border rounded hover:bg-gray-50">Cancel</button>
                            <button type="submit" class="bg-[#DB4444] text-white px-4 py-2 rounded hover:bg-red-600">Save
                                Product</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let currentPage = 1;
            let products = [];

            // Load products
            loadProducts();

            // Event listeners
            document.getElementById('searchInput').addEventListener('input', debounce(loadProducts, 300));
            document.getElementById('categoryFilter').addEventListener('change', loadProducts);
            document.getElementById('sortBy').addEventListener('change', loadProducts);
            document.getElementById('sortOrder').addEventListener('change', loadProducts);

            document.getElementById('addProductBtn').addEventListener('click', () => openModal());
            document.getElementById('closeModal').addEventListener('click', closeModal);
            document.getElementById('cancelBtn').addEventListener('click', closeModal);
            document.getElementById('productForm').addEventListener('submit', saveProduct);

            function loadProducts(page = 1) {
                currentPage = page;
                const search = document.getElementById('searchInput').value;
                const category = document.getElementById('categoryFilter').value;
                const sortBy = document.getElementById('sortBy').value;
                const sortOrder = document.getElementById('sortOrder').value;

                const params = new URLSearchParams({
                    page,
                    search,
                    category,
                    sort_by: sortBy,
                    sort_order: sortOrder,
                    per_page: 15
                });

                fetch(`/admin/api/products?${params}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status) {
                            displayProducts(data.data.data);
                            displayPagination(data.data);
                        }
                    })
                    .catch(error => console.error('Error loading products:', error));
            }

            function displayProducts(products) {
                const tbody = document.getElementById('productsTableBody');
                tbody.innerHTML = '';

                if (products.length === 0) {
                    tbody.innerHTML =
                        '<tr><td colspan="7" class="px-6 py-10 text-center text-gray-500">No products found.</td></tr>';
                    return;
                }

                products.forEach(product => {
                    const statusClass = product.is_available ? 'bg-green-100 text-green-700' :
                        'bg-red-100 text-red-700';
                    const statusText = product.is_available ? 'Available' : 'Unavailable';

                    tbody.innerHTML += `
                <tr>
                    <td class="px-6 py-4">
                        <img src="/${product.image || "/placeholder.png"}" 
     alt="${product.name}" class="w-12 h-12 object-cover rounded">
                    </td>
                    <td class="px-6 py-4 font-medium">${product.name}</td>
                    <td class="px-6 py-4">${product.category || 'N/A'}</td>
                    <td class="px-6 py-4">$${parseFloat(product.price).toFixed(2)}</td>
                    <td class="px-6 py-4">${product.stock}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded text-xs ${statusClass}">${statusText}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex gap-2">
                            <button onclick="editProduct(${product.id})" class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteProduct(${product.id})" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
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

                // Previous button
                if (data.current_page > 1) {
                    html +=
                        `<button onclick="loadProducts(${data.current_page - 1})" class="px-3 py-1 border rounded hover:bg-gray-50">Previous</button>`;
                } else {
                    html += '<span class="px-3 py-1 text-gray-400">Previous</span>';
                }

                // Page numbers
                html += '<div class="flex gap-1">';
                for (let i = Math.max(1, data.current_page - 2); i <= Math.min(data.last_page, data.current_page +
                        2); i++) {
                    const activeClass = i === data.current_page ? 'bg-[#DB4444] text-white' : 'hover:bg-gray-50';
                    html +=
                        `<button onclick="loadProducts(${i})" class="px-3 py-1 border rounded ${activeClass}">${i}</button>`;
                }
                html += '</div>';

                // Next button
                if (data.current_page < data.last_page) {
                    html +=
                        `<button onclick="loadProducts(${data.current_page + 1})" class="px-3 py-1 border rounded hover:bg-gray-50">Next</button>`;
                } else {
                    html += '<span class="px-3 py-1 text-gray-400">Next</span>';
                }

                html += '</div>';
                pagination.innerHTML = html;
            }

            function openModal(product = null) {
                const modal = document.getElementById('productModal');
                const form = document.getElementById('productForm');

                const imagePreviewContainer = document.getElementById('imagePreviewContainer');
                const currentImageDisplay = document.getElementById('currentImageDisplay');

                if (product) {
                    document.getElementById('modalTitle').textContent = 'Edit Product';
                    document.getElementById('productId').value = product.id;
                    document.getElementById('name').value = product.name;
                    document.getElementById('category').value = product.category;
                    document.getElementById('price').value = product.price;
                    document.getElementById('discount_price').value = product.discount_price || '';
                    document.getElementById('stock').value = product.stock;
                    document.getElementById('description').value = product.description;
                    document.getElementById('is_new').checked = product.is_new;
                    document.getElementById('is_available').checked = product.is_available;

                    const imageUrl = product.image ?
                        '/' + encodeURIComponent(product.image) :
                        '/placeholder.png';
                    if (imageUrl) {
                        currentImageDisplay.src = imageUrl;
                        imagePreviewContainer.classList.remove('hidden');
                    } else {
                        imagePreviewContainer.classList.add('hidden');
                    }
                } else {
                    document.getElementById('modalTitle').textContent = 'Add Product';
                    form.reset();
                    document.getElementById('productId').value = '';
                    imagePreviewContainer.classList.add('hidden');
                }

                modal.classList.remove('hidden');
            }

            function closeModal() {
                document.getElementById('productModal').classList.add('hidden');
            }

            function saveProduct(e) {
                e.preventDefault();

                const formData = new FormData(e.target);
                const productId = formData.get('id');

                formData.set('is_new', document.getElementById('is_new').checked ? 1 : 0);
                formData.set('is_available', document.getElementById('is_available').checked ? 1 : 0);

                const url = productId ? `/admin/api/products/${productId}` : '/admin/api/products';
                const method = productId ? 'POST' : 'POST';
                if (productId) {
                    formData.set('_method', 'PUT');
                }

                fetch(url, {
                        method,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                            'Accept': 'application/json'
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.status) {
                            closeModal();
                            loadProducts(currentPage);
                            alert(result.message);
                        } else {
                            alert(result.message || 'Error saving product');
                        }
                    })
                    .catch(error => {
                        console.error('Error saving product:', error);
                        alert('Error saving product!');
                    });
            }

            window.editProduct = function(id) {
                fetch(`/admin/api/products/${id}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status) {
                            openModal(data.data);
                        }
                    })
                    .catch(error => console.error('Error loading product:', error));
            };

            window.deleteProduct = function(id) {
                if (confirm('Are you sure you want to delete this product?')) {
                    fetch(`/admin/api/products/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    ?.content || '',
                                'Accept': 'application/json'
                            }
                        })
                        .then(response => response.json())
                        .then(result => {
                            if (result.status) {
                                loadProducts(currentPage);
                                alert(result.message);
                            }
                        })
                        .catch(error => console.error('Error deleting product:', error));
                }
            };

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
