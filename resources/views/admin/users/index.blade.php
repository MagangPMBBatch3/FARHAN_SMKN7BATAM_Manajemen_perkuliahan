<x-layouts.dashboard title="User Management">

<style>
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .card-animated {
        animation: slideUp 0.5s ease-out;
    }

    .table-row {
        transition: all 0.2s ease;
    }

    .table-row:hover {
        background: linear-gradient(90deg, #f0fdf4 0%, #ecfeff 100%);
        transform: translateX(4px);
        box-shadow: -4px 0 0 0 #10b981;
    }

    .badge {
        display: inline-flex;
        align-items: center;
        padding: 0.25rem 0.75rem;
        border-radius: 9999px;
        font-size: 0.75rem;
        font-weight: 600;
    }

    .badge-aktif {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    }

    .badge-nonaktif {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.3);
    }

    .btn {
        transition: all 0.3s ease;
        font-weight: 600;
        border-radius: 0.5rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
    }

    .btn:active {
        transform: translateY(0);
    }

    .btn-primary {
        background: linear-gradient(135deg, #0ea5e9, #0284c7);
        color: white;
    }

    .btn-success {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }

    .btn-warning {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
    }

    .btn-danger {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }

    .btn-secondary {
        background: linear-gradient(135deg, #6b7280, #4b5563);
        color: white;
    }

    .input-search {
        transition: all 0.3s ease;
        border: 2px solid #e5e7eb;
    }

    .input-search:focus {
        outline: none;
        border-color: #0ea5e9;
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
        transform: scale(1.02);
    }

    .stats-card {
        background: white;
        border-radius: 1rem;
        padding: 1.5rem;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }

    .stats-card:hover {
        border-color: #0ea5e9;
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
    }

    .modal-backdrop {
        animation: fadeIn 0.3s ease;
    }

    .modal-content {
        animation: slideUp 0.3s ease;
    }

    .icon-circle {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }
</style>
<!-- Main Content Card -->
<div class="bg-white rounded-2xl shadow-xl overflow-hidden card-animated" style="animation-delay: 0.2s;">
    
    <!-- Toolbar -->
    <div class="p-6 bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            
            <!-- Search -->
            <div class="relative flex-1 max-w-md">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input 
                    type="text" 
                    id="search" 
                    placeholder="Cari berdasarkan nama atau email..." 
                    class="input-search pl-10 pr-4 py-3 w-full rounded-xl"
                    oninput="searchUser()">
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3">   
                <button onclick="openAddModal()" class="btn btn-primary px-5 py-3">
                    <i class="fas fa-plus text-sm"></i>
                    <span class="text-sm">Tambah User</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="bg-gradient-to-r from-emerald-600 to-sky-600 text-white">
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                        <i class="fas fa-hashtag mr-2"></i>ID
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                        <i class="fas fa-user mr-2"></i>Username
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                        <i class="fas fa-envelope mr-2"></i>Email
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                        <i class="fas fa-user-tag mr-2"></i>Role
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                        <i class="fas fa-toggle-on mr-2"></i>Status
                    </th>
                    <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">
                        <i class="fas fa-cog mr-2"></i>Aksi
                    </th>
                </tr>
            </thead>
            <tbody id="dataUser" class="divide-y divide-gray-200">
                <!-- Data will be loaded here -->
            </tbody>
        </table>
    </div>

    <!-- Pagination Footer -->
    <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-t border-gray-200">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            
            <!-- Page Info -->
            <div class="flex items-center gap-2">
                <div class="icon-circle bg-white text-emerald-600 text-sm" style="width: 36px; height: 36px;">
                    <i class="fas fa-info-circle"></i>
                </div>
                <span id="pageInfo" class="text-sm font-semibold text-gray-700">Loading...</span>
            </div>

            <!-- Controls -->
            <div class="flex items-center gap-4">
                <!-- Per Page -->
                <div class="flex items-center gap-2">
                    <label class="text-sm font-semibold text-gray-700">Show:</label>
                    <select id="perPage" 
                        class="border-2 border-gray-300 rounded-lg px-3 py-2 text-sm font-semibold focus:border-emerald-500 focus:outline-none transition-all"
                        onchange="loadUser(1)">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex gap-2">
                    <button id="prevBtn" onclick="prevPage()" 
                        class="btn btn-secondary px-4 py-2 disabled:opacity-50 disabled:cursor-not-allowed text-sm">
                        <i class="fas fa-chevron-left text-xs"></i>
                        Previous
                    </button>
                    <button id="nextBtn" onclick="nextPage()" 
                        class="btn btn-secondary px-4 py-2 disabled:opacity-50 disabled:cursor-not-allowed text-sm">
                        Next
                        <i class="fas fa-chevron-right text-xs"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>

@include('components.modal.user.modal-add')
@include('components.modal.user.modal-edit')

<script src="{{ asset('js/admin/user/user.js') }}"></script>
<script src="{{ asset('js/admin/user/user-create.js') }}"></script>
<script src="{{ asset('js/admin/user/user-edit.js') }}"></script>

</x-layouts.dashboard>