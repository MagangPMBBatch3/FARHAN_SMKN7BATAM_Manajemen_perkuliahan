<style>
    @keyframes modalFadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes modalSlideIn {
        from { 
            opacity: 0; 
            transform: translateY(-50px) scale(0.9); 
        }
        to { 
            opacity: 1; 
            transform: translateY(0) scale(1); 
        }
    }

    .modal-backdrop {
        animation: modalFadeIn 0.3s ease;
    }

    .modal-box {
        animation: modalSlideIn 0.3s ease;
    }

    .form-input {
        transition: all 0.3s ease;
        border: 2px solid #e5e7eb;
    }

    .form-input:focus {
        outline: none;
        border-color: #0ea5e9;
        box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
        transform: scale(1.01);
    }

    .form-label {
        font-weight: 600;
        color: #374151;
        font-size: 0.875rem;
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .icon-input {
        position: absolute;
        left: 12px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        pointer-events: none;
    }

    .input-with-icon {
        padding-left: 2.5rem;
    }

    .modal-header {
        background: linear-gradient(135deg, #10b981, #0ea5e9);
        color: white;
        padding: 1.5rem;
        border-radius: 1rem 1rem 0 0;
    }
</style>

<div id="modalAdd" class="hidden fixed inset-0 z-50">
    <div class="modal-backdrop fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="modal-box bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
            
            <!-- Header -->
            <div class="modal-header">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold flex items-center gap-2">
                            <i class="fas fa-user-plus"></i>
                            Tambah User Baru
                        </h2>
                        <p class="text-emerald-100 text-sm mt-1">Lengkapi form dibawah untuk menambah user</p>
                    </div>
                    <button onclick="closeAddModal()" class="text-white hover:bg-white/20 rounded-lg p-2 transition-all">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Form -->
            <form id="formAddUser" onsubmit="createUser(); return false;" class="p-6 space-y-5">
                @csrf
                
                <!-- Username -->
                <div>
                    <label for="addUsername" class="form-label">
                        <i class="fas fa-user text-emerald-600"></i>
                        Username
                    </label>
                    <div class="relative">
                        <i class="fas fa-user icon-input"></i>
                        <input 
                            type="text" 
                            id="addUsername" 
                            name="username" 
                            class="form-input input-with-icon w-full px-4 py-3 rounded-xl" 
                            placeholder="Masukkan username"
                            required>
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="addEmail" class="form-label">
                        <i class="fas fa-envelope text-blue-600"></i>
                        Email Address
                    </label>
                    <div class="relative">
                        <i class="fas fa-envelope icon-input"></i>
                        <input 
                            type="email" 
                            id="addEmail" 
                            name="email" 
                            class="form-input input-with-icon w-full px-4 py-3 rounded-xl" 
                            placeholder="example@email.com"
                            required>
                    </div>
                </div>

                <!-- Password -->
                <div>
                    <label for="addPassword" class="form-label">
                        <i class="fas fa-lock text-purple-600"></i>
                        Password
                    </label>
                    <div class="relative">
                        <i class="fas fa-lock icon-input"></i>
                        <input 
                            type="password" 
                            id="addPassword" 
                            name="password" 
                            class="form-input input-with-icon w-full px-4 py-3 rounded-xl" 
                            placeholder="Minimal 8 karakter"
                            required>
                    </div>
                </div>

                <!-- Role -->
                <div>
                    <label for="addRole" class="form-label">
                        <i class="fas fa-user-tag text-orange-600"></i>
                        Role
                    </label>
                    <div class="relative">
                        <i class="fas fa-user-tag icon-input"></i>
                        <select 
                            id="addRole" 
                            name="role_id" 
                            class="form-input input-with-icon w-full px-4 py-3 rounded-xl appearance-none bg-white cursor-pointer" 
                            required>
                            <option value="">Memuat data role...</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label for="addStatus" class="form-label">
                        <i class="fas fa-toggle-on text-green-600"></i>
                        Status
                    </label>
                    <div class="relative">
                        <i class="fas fa-toggle-on icon-input"></i>
                        <select 
                            id="addStatus" 
                            name="status" 
                            class="form-input input-with-icon w-full px-4 py-3 rounded-xl appearance-none bg-white cursor-pointer" 
                            required>
                            <option value="aktif">✓ Aktif</option>
                            <option value="nonaktif">✗ Non-Aktif</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <button 
                        type="button" 
                        onclick="closeAddModal()" 
                        class="flex-1 bg-gradient-to-r from-gray-500 to-gray-600 text-white px-5 py-3 rounded-xl font-bold hover:from-gray-600 hover:to-gray-700 transition-all hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </button>
                    <button 
                        type="submit" 
                        class="flex-1 bg-gradient-to-r from-emerald-500 to-blue-500 text-white px-5 py-3 rounded-xl font-bold hover:from-emerald-600 hover:to-blue-600 transition-all hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0">
                        <i class="fas fa-save mr-2"></i>
                        Simpan
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
function openAddModal() {
    document.getElementById('modalAdd').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Load roles
    loadRolesForAdd();
}

function closeAddModal() {
    document.getElementById('modalAdd').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('formAddUser').reset();
}

async function loadRolesForAdd() {
    const query = `
    query {
        allRole {
            id
            nama_role
        }
    }`;

    const res = await fetch("/graphql", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ query })
    });
    
    const data = await res.json();
    const roles = data.data?.allRole || [];
    
    const select = document.getElementById('addRole');
    select.innerHTML = '<option value="">-- Pilih Role --</option>';
    
    roles.forEach(role => {
        select.innerHTML += `<option value="${role.id}">${role.nama_role}</option>`;
    });
}

// Close modal when clicking outside
document.getElementById('modalAdd')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeAddModal();
    }
});

// Close modal with ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAddModal();
        closeEditModal();
    }
});
</script>