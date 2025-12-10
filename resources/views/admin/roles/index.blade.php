<x-layouts.dashboard title="Role Management">

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
        background: linear-gradient(90deg, #fef3c7 0%, #ddd6fe 100%);
        transform: translateX(4px);
        box-shadow: -4px 0 0 0 #a855f7;
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
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
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

    .btn-archive {
        background: linear-gradient(135deg, #ec4899, #db2777);
        color: white;
    }

    .input-search {
        transition: all 0.3s ease;
        border: 2px solid #e5e7eb;
    }

    .input-search:focus {
        outline: none;
        border-color: #8b5cf6;
        box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
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
        border-color: #8b5cf6;
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
    }

    .view-tab {
        transition: all 0.3s ease;
        border-bottom: 3px solid transparent;
    }

    .view-tab.active-view {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        color: white;
        border-bottom-color: #7c3aed;
        box-shadow: 0 4px 12px rgba(139, 92, 246, 0.3);
    }

    .view-tab.inactive-view {
        background: #f3f4f6;
        color: #6b7280;
    }

    .view-tab.inactive-view:hover {
        background: #e5e7eb;
        color: #374151;
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
    
    <!-- View Tabs -->
    <div class="flex border-b border-gray-200 bg-white px-6 pt-4">
        <button 
            id="btnActive"
            onclick="switchView('active')" 
            class="view-tab active-view px-6 py-3 font-bold text-sm rounded-t-lg flex items-center gap-2">
            <i class="fas fa-list"></i>
            <span>Data Aktif</span>
        </button>
        <button 
            id="btnArchive"
            onclick="switchView('archive')" 
            class="view-tab inactive-view px-6 py-3 font-bold text-sm rounded-t-lg flex items-center gap-2">
            <i class="fas fa-archive"></i>
            <span>Data Arsip</span>
        </button>
    </div>

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
                    placeholder="Cari berdasarkan nama role..." 
                    class="input-search pl-10 pr-4 py-3 w-full rounded-xl"
                    oninput="searchRole()">
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center gap-3">
                <button onclick="openAddModal()" class="btn btn-primary px-5 py-3" id="btnAddRole">
                    <i class="fas fa-plus text-sm"></i>
                    <span class="text-sm">Tambah Role</span>
                </button>
            </div>
        </div>
    </div>

    <!-- Table Active -->
    <div id="tableActive">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-hashtag mr-2"></i>ID
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-user-tag mr-2"></i>Nama Role
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-file-alt mr-2"></i>Deskripsi
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-cog mr-2"></i>Aksi
                        </th>
                    </tr>
                </thead>
                <tbody id="dataRole" class="divide-y divide-gray-200">
                    <!-- Data will be loaded here -->
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer Active -->
        <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-t border-gray-200">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                
                <!-- Page Info -->
                <div class="flex items-center gap-2">
                    <div class="icon-circle bg-white text-purple-600 text-sm" style="width: 36px; height: 36px;">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <span id="pageInfoAktif" class="text-sm font-semibold text-gray-700">Loading...</span>
                </div>

                <!-- Controls -->
                <div class="flex items-center gap-4">
                    <!-- Per Page -->
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-semibold text-gray-700">Show:</label>
                        <select id="perPage" 
                            class="border-2 border-gray-300 rounded-lg px-3 py-2 text-sm font-semibold focus:border-purple-500 focus:outline-none transition-all"
                            onchange="loadRoleData(1, currentPageArsip)">
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex gap-2">
                        <button id="prevBtnAktif" onclick="prevPageAktif()" 
                            class="btn btn-secondary px-4 py-2 disabled:opacity-50 disabled:cursor-not-allowed text-sm">
                            <i class="fas fa-chevron-left text-xs"></i>
                            Previous
                        </button>
                        <button id="nextBtnAktif" onclick="nextPageAktif()" 
                            class="btn btn-secondary px-4 py-2 disabled:opacity-50 disabled:cursor-not-allowed text-sm">
                            Next
                            <i class="fas fa-chevron-right text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Table Archive -->
    <div id="tableArchive" class="hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-purple-600 to-pink-600 text-white">
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-hashtag mr-2"></i>ID
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-user-tag mr-2"></i>Nama Role
                        </th>
                        <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-file-alt mr-2"></i>Deskripsi
                        </th>
                        <th class="px-6 py-4 text-center text-xs font-bold uppercase tracking-wider">
                            <i class="fas fa-cog mr-2"></i>Aksi
                        </th>
                    </tr>
                </thead>
                <tbody id="dataRoleArsip" class="divide-y divide-gray-200">
                    <!-- Data will be loaded here -->
                </tbody>
            </table>
        </div>

        <!-- Pagination Footer Archive -->
        <div class="px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-t border-gray-200">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                
                <!-- Page Info -->
                <div class="flex items-center gap-2">
                    <div class="icon-circle bg-white text-purple-600 text-sm" style="width: 36px; height: 36px;">
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <span id="pageInfoArsip" class="text-sm font-semibold text-gray-700">Loading...</span>
                </div>

                <!-- Controls -->
                <div class="flex items-center gap-4">
                    <!-- Per Page -->
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-semibold text-gray-700">Show:</label>
                        <select id="perPageArsip" 
                            class="border-2 border-gray-300 rounded-lg px-3 py-2 text-sm font-semibold focus:border-purple-500 focus:outline-none transition-all"
                            onchange="loadRoleData(currentPageAktif, 1)">
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex gap-2">
                        <button id="prevBtnArsip" onclick="prevPageArsip()" 
                            class="btn btn-secondary px-4 py-2 disabled:opacity-50 disabled:cursor-not-allowed text-sm">
                            <i class="fas fa-chevron-left text-xs"></i>
                            Previous
                        </button>
                        <button id="nextBtnArsip" onclick="nextPageArsip()" 
                            class="btn btn-secondary px-4 py-2 disabled:opacity-50 disabled:cursor-not-allowed text-sm">
                            Next
                            <i class="fas fa-chevron-right text-xs"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@include('components.modal.role.modal-add')
@include('components.modal.role.modal-edit')

<script src="{{ asset('js/admin/role/role.js') }}"></script>
<script src="{{ asset('js/admin/role/role-create.js') }}"></script>
<script src="{{ asset('js/admin/role/role-edit.js') }}"></script>

<script>
let currentView = 'active';

function switchView(view) {
    currentView = view;
    
    const activeBtn = document.getElementById('btnActive');
    const archiveBtn = document.getElementById('btnArchive');
    const tableActive = document.getElementById('tableActive');
    const tableArchive = document.getElementById('tableArchive');
    const btnAddRole = document.getElementById('btnAddRole');
    
    if (view === 'active') {
        activeBtn.classList.add('active-view');
        activeBtn.classList.remove('inactive-view');
        archiveBtn.classList.add('inactive-view');
        archiveBtn.classList.remove('active-view');
        tableActive.classList.remove('hidden');
        tableArchive.classList.add('hidden');
        btnAddRole.classList.remove('hidden');
    } else {
        archiveBtn.classList.add('active-view');
        archiveBtn.classList.remove('inactive-view');
        activeBtn.classList.add('inactive-view');
        activeBtn.classList.remove('active-view');
        tableArchive.classList.remove('hidden');
        tableActive.classList.add('hidden');
        btnAddRole.classList.add('hidden');
    }
    
    loadRoleData(1, 1);
}
</script>

</x-layouts.dashboard>