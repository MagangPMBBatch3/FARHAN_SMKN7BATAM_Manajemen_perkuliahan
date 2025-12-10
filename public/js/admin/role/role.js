// role.js - Main functionality
const API_URL = "/graphql";
let currentPageAktif = 1;
let currentPageArsip = 1;

async function loadRoleData(pageAktif = 1, pageArsip = 1) {
    currentPageAktif = pageAktif;
    currentPageArsip = pageArsip;
    
    const perPageAktif = parseInt(document.getElementById("perPage")?.value || 10);
    const perPageArsip = parseInt(document.getElementById("perPageArsip")?.value || 10);
    const searchValue = document.getElementById("search")?.value.trim() || "";

    try {
        // --- Query Data Aktif ---
        const queryAktif = `
        query($first: Int, $page: Int, $search: String) {
            allRolePaginate(first: $first, page: $page, search: $search) {
                data { id nama_role deskripsi }
                paginatorInfo { currentPage lastPage total hasMorePages perPage }
            }
        }`;
        const variablesAktif = { first: perPageAktif, page: pageAktif, search: searchValue };

        const resAktif = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query: queryAktif, variables: variablesAktif })
        });
        const dataAktif = await resAktif.json();
        
        const activeRoles = dataAktif?.data?.allRolePaginate?.data || [];
        renderRoleTable(activeRoles, 'dataRole', true);

        // --- Query Data Arsip ---
        const queryArsip = `
        query($first: Int, $page: Int, $search: String) {
            allRoleArsip(first: $first, page: $page, search: $search) {
                data { id nama_role deskripsi }
                paginatorInfo { currentPage lastPage total hasMorePages perPage }
            }
        }`;
        const variablesArsip = { first: perPageArsip, page: pageArsip, search: searchValue };

        const resArsip = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query: queryArsip, variables: variablesArsip })
        });
        const dataArsip = await resArsip.json();
        
        const archivedRoles = dataArsip?.data?.allRoleArsip?.data || [];
        renderRoleTable(archivedRoles, 'dataRoleArsip', false); 

        // --- Update info pagination untuk Data Aktif ---
        const pageInfoAktif = dataAktif?.data?.allRolePaginate?.paginatorInfo;
        if (pageInfoAktif) {
            document.getElementById("pageInfoAktif").innerText =
                `Showing ${pageInfoAktif.currentPage} of ${pageInfoAktif.lastPage} pages (Total: ${pageInfoAktif.total} roles)`;
            document.getElementById("prevBtnAktif").disabled = pageInfoAktif.currentPage <= 1;
            document.getElementById("nextBtnAktif").disabled = !pageInfoAktif.hasMorePages;
        }

        // --- Update info pagination untuk Data Arsip ---
        const pageInfoArsip = dataArsip?.data?.allRoleArsip?.paginatorInfo;
        if (pageInfoArsip) {
            document.getElementById("pageInfoArsip").innerText =
                `Showing ${pageInfoArsip.currentPage} of ${pageInfoArsip.lastPage} pages (Total: ${pageInfoArsip.total} archived roles)`;
            document.getElementById("prevBtnArsip").disabled = pageInfoArsip.currentPage <= 1;
            document.getElementById("nextBtnArsip").disabled = !pageInfoArsip.hasMorePages;
        }

    } catch (error) {
        console.error('Error loading roles:', error);
        showNotification('Error loading data: ' + error.message, 'error');
    }
}

function renderRoleTable(roles, tableId, isActive) {
    const tbody = document.getElementById(tableId);
    tbody.innerHTML = '';

    if (!roles.length) {
        const message = isActive ? 'Data role aktif tidak ditemukan' : 'Data role arsip tidak ditemukan';
        tbody.innerHTML = `
            <tr>
                <td colspan="4" class="text-center p-8 text-gray-500">
                    <i class="fas fa-inbox text-4xl mb-2"></i>
                    <p class="font-semibold">${message}</p>
                </td>
            </tr>
        `;
        return;
    }

    roles.forEach(item => {
        const deskripsi = item.deskripsi || '-';
        const truncatedDesc = deskripsi.length > 50 ? deskripsi.substring(0, 50) + '...' : deskripsi;
        
        let actions = '';
        if (isActive) {
            actions = `
                <button onclick="openEditModal(${item.id}, '${escapeHtml(item.nama_role)}', '${escapeHtml(item.deskripsi || '')}')" 
                    class="btn btn-warning px-3 py-2 text-xs" title="Edit">
                    <i class="fas fa-edit"></i>
                </button>
                <button onclick="archiveRole(${item.id})" 
                    class="btn btn-archive px-3 py-2 text-xs" title="Arsipkan">
                    <i class="fas fa-archive"></i>
                </button>
            `;
        } else {
            actions = `
                <button onclick="restoreRole(${item.id})" 
                    class="btn btn-success px-3 py-2 text-xs" title="Restore">
                    <i class="fas fa-undo"></i>
                </button>
                <button onclick="forceDeleteRole(${item.id})" 
                    class="btn btn-danger px-3 py-2 text-xs" title="Hapus Permanen">
                    <i class="fas fa-trash-alt"></i>
                </button>
            `;
        }

        tbody.innerHTML += `
            <tr class="table-row">
                <td class="px-6 py-4 text-sm font-bold text-gray-900">#${item.id}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="icon-circle bg-gradient-to-br from-purple-100 to-pink-100 text-purple-600 text-sm mr-3" style="width: 40px; height: 40px;">
                            <i class="fas fa-user-tag"></i>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">${escapeHtml(item.nama_role)}</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    <i class="fas fa-file-alt text-gray-400 mr-2"></i>
                    <span title="${escapeHtml(deskripsi)}">${escapeHtml(truncatedDesc)}</span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">
                        ${actions}
                    </div>
                </td>
            </tr>
        `;
    });
}


function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

// --- Archive Role ---
async function archiveRole(id) {
    if (!confirm('🗃️ Arsipkan role ini?\nRole akan dipindahkan ke arsip.')) return;

    const mutation = `
    mutation {
        deleteRole(id: ${id}) { id }
    }`;

    try {
        const res = await fetch(API_URL, { 
            method: 'POST', 
            headers: { 'Content-Type': 'application/json' }, 
            body: JSON.stringify({ query: mutation }) 
        });

        const result = await res.json();
        if (result.errors) {
            throw new Error(result.errors[0].message);
        }

        showNotification('✅ Role berhasil diarsipkan', 'success');
        loadRoleData(currentPageAktif, currentPageArsip);
    } catch (error) {
        showNotification('❌ Gagal mengarsipkan role: ' + error.message, 'error');
        console.error('Error archiving role:', error);
    }
}

// --- Restore Role ---
async function restoreRole(id) {
    if (!confirm('♻️ Restore role ini?\nRole akan dikembalikan ke data aktif.')) return;

    const mutation = `
    mutation {
        restoreRole(id: ${id}) { id }
    }`;

    try {
        const res = await fetch(API_URL, { 
            method: 'POST', 
            headers: { 'Content-Type': 'application/json' }, 
            body: JSON.stringify({ query: mutation }) 
        });

        const result = await res.json();
        if (result.errors) {
            throw new Error(result.errors[0].message);
        }

        showNotification('✅ Role berhasil di-restore', 'success');
        loadRoleData(currentPageAktif, currentPageArsip);
    } catch (error) {
        showNotification('❌ Gagal me-restore role: ' + error.message, 'error');
        console.error('Error restoring role:', error);
    }
}

// --- Force Delete Role ---
async function forceDeleteRole(id) {
    if (!confirm('🚨 PERINGATAN!\n\nHapus PERMANEN role ini?\nData tidak dapat dikembalikan!\n\nKetik "HAPUS" untuk konfirmasi.')) {
        return;
    }

    const confirmation = prompt('Ketik "HAPUS" untuk konfirmasi penghapusan permanen:');
    if (confirmation !== 'HAPUS') {
        showNotification('Penghapusan dibatalkan', 'info');
        return;
    }

    const mutation = `
    mutation {
        forceDeleteRole(id: ${id}) { id }
    }`;

    try {
        const res = await fetch(API_URL, { 
            method: 'POST', 
            headers: { 'Content-Type': 'application/json' }, 
            body: JSON.stringify({ query: mutation }) 
        });

        const result = await res.json();
        if (result.errors) {
            throw new Error(result.errors[0].message);
        }

        showNotification('✅ Role berhasil dihapus permanen', 'success');
        loadRoleData(currentPageAktif, currentPageArsip);
    } catch (error) {
        showNotification('❌ Gagal menghapus role: ' + error.message, 'error');
        console.error('Error deleting role:', error);
    }
}

// --- Search ---
async function searchRole() {
    loadRoleData(1, 1);
}

// --- Pagination untuk Data Aktif ---
function prevPageAktif() {
    if (currentPageAktif > 1) loadRoleData(currentPageAktif - 1, currentPageArsip);
}

function nextPageAktif() {
    loadRoleData(currentPageAktif + 1, currentPageArsip);
}

// --- Pagination untuk Data Arsip ---
function prevPageArsip() {
    if (currentPageArsip > 1) loadRoleData(currentPageAktif, currentPageArsip - 1);
}

function nextPageArsip() {
    loadRoleData(currentPageAktif, currentPageArsip + 1);
}

// --- Notification System ---
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 z-50 px-6 py-4 rounded-xl shadow-2xl transform transition-all duration-300 flex items-center gap-3 ${
        type === 'success' ? 'bg-gradient-to-r from-emerald-500 to-green-500' :
        type === 'error' ? 'bg-gradient-to-r from-red-500 to-rose-500' :
        type === 'warning' ? 'bg-gradient-to-r from-orange-500 to-amber-500' :
        'bg-gradient-to-r from-blue-500 to-cyan-500'
    } text-white font-semibold`;
    
    const icon = type === 'success' ? 'fa-check-circle' :
                 type === 'error' ? 'fa-times-circle' :
                 type === 'warning' ? 'fa-exclamation-triangle' :
                 'fa-info-circle';
    
    notification.innerHTML = `
        <i class="fas ${icon} text-xl"></i>
        <span>${message}</span>
    `;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 10);
    
    setTimeout(() => {
        notification.style.transform = 'translateX(400px)';
        setTimeout(() => {
            notification.remove();
        }, 300);
    }, 3000);
}

document.addEventListener("DOMContentLoaded", () => loadRoleData());