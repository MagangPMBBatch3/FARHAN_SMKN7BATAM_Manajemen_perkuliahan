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
        border-color: #f59e0b;
        box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
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

    .modal-header-edit {
        background: linear-gradient(135deg, #f59e0b, #d97706);
        color: white;
        padding: 1.5rem;
        border-radius: 1rem 1rem 0 0;
    }
</style>

<!-- Modal Edit User -->
<div id="modalEdit" class="hidden fixed inset-0 z-50">
    <div class="modal-backdrop fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="modal-box bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden">
            
            <!-- Header -->
            <div class="modal-header-edit">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-2xl font-bold flex items-center gap-2">
                            <i class="fas fa-user-edit"></i>
                            Edit User
                        </h2>
                        <p class="text-orange-100 text-sm mt-1">Update informasi user yang dipilih</p>
                    </div>
                    <button onclick="closeEditModal()" class="text-white hover:bg-white/20 rounded-lg p-2 transition-all">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
            </div>

            <!-- Form -->
            <form id="formEditUser" onsubmit="updateUser(); return false;" class="p-6 space-y-5">
                @csrf
                <input type="hidden" id="editId" name="id">

                <!-- Username -->
                <div>
                    <label for="editUsername" class="form-label">
                        <i class="fas fa-user text-emerald-600"></i>
                        Username
                    </label>
                    <div class="relative">
                        <i class="fas fa-user icon-input"></i>
                        <input 
                            type="text" 
                            id="editUsername" 
                            name="username" 
                            class="form-input input-with-icon w-full px-4 py-3 rounded-xl" 
                            placeholder="Masukkan username"
                            required>
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="editEmail" class="form-label">
                        <i class="fas fa-envelope text-blue-600"></i>
                        Email Address
                    </label>
                    <div class="relative">
                        <i class="fas fa-envelope icon-input"></i>
                        <input 
                            type="email" 
                            id="editEmail" 
                            name="email" 
                            class="form-input input-with-icon w-full px-4 py-3 rounded-xl" 
                            placeholder="example@email.com"
                            required>
                    </div>
                </div>

                <!-- Role -->
                <div>
                    <label for="editRole" class="form-label">
                        <i class="fas fa-user-tag text-purple-600"></i>
                        Role
                    </label>
                    <div class="relative">
                        <i class="fas fa-user-tag icon-input"></i>
                        <select 
                            id="editRole" 
                            name="role_id" 
                            class="form-input input-with-icon w-full px-4 py-3 rounded-xl appearance-none bg-white cursor-pointer" 
                            required>
                            <option value="">-- Pilih Role --</option>
                            <option value="1">Admin</option>
                            <option value="2">Dosen</option>
                            <option value="3">Mahasiswa</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <label for="editStatus" class="form-label">
                        <i class="fas fa-toggle-on text-green-600"></i>
                        Status
                    </label>
                    <div class="relative">
                        <i class="fas fa-toggle-on icon-input"></i>
                        <select 
                            id="editStatus" 
                            name="status" 
                            class="form-input input-with-icon w-full px-4 py-3 rounded-xl appearance-none bg-white cursor-pointer" 
                            required>
                            <option value="">-- Pilih Status --</option>
                            <option value="aktif">✓ Aktif</option>
                            <option value="nonaktif">✗ Non-Aktif</option>
                        </select>
                        <i class="fas fa-chevron-down absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-400 pointer-events-none"></i>
                    </div>
                </div>

                <!-- Info Box -->
                <div class="bg-gradient-to-r from-orange-50 to-yellow-50 border-l-4 border-orange-500 p-4 rounded-lg">
                    <div class="flex items-start">
                        <i class="fas fa-info-circle text-orange-500 mt-0.5 mr-3"></i>
                        <div>
                            <p class="text-xs font-semibold text-orange-800 mb-1">Perhatian</p>
                            <p class="text-xs text-orange-700">Perubahan data akan langsung mempengaruhi akses user ke sistem</p>
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex gap-3 pt-4 border-t border-gray-200">
                    <button 
                        type="button" 
                        onclick="closeEditModal()" 
                        class="flex-1 bg-gradient-to-r from-gray-500 to-gray-600 text-white px-5 py-3 rounded-xl font-bold hover:from-gray-600 hover:to-gray-700 transition-all hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </button>
                    <button 
                        type="submit" 
                        class="flex-1 bg-gradient-to-r from-orange-500 to-yellow-500 text-white px-5 py-3 rounded-xl font-bold hover:from-orange-600 hover:to-yellow-600 transition-all hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0">
                        <i class="fas fa-check mr-2"></i>
                        Update
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>

<script>
function openEditModal(id, username, email, roleId, status) {
    document.getElementById('editId').value = id;
    document.getElementById('editUsername').value = username;
    document.getElementById('editEmail').value = email;
    document.getElementById('editRole').value = roleId;
    document.getElementById('editStatus').value = status;
    
    document.getElementById('modalEdit').classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeEditModal() {
    document.getElementById('modalEdit').classList.add('hidden');
    document.body.style.overflow = 'auto';
    document.getElementById('formEditUser').reset();
}

// Close modal when clicking outside
document.getElementById('modalEdit')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});
</script>