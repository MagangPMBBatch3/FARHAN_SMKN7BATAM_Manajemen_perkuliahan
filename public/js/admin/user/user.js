const API_URL = "/graphql";
let currentPage = 1;

async function loadUser(page = 1) {
    currentPage = page;
    const perPage = document.getElementById("perPage")?.value || 10;
    const searchValue = document.getElementById("search")?.value.trim() || "";

    const query = `
    query($first: Int, $page: Int, $search: String) {
        allUserPaginate(first: $first, page: $page, search: $search) {
            data {
                id
                username
                email
                role_id
                status
                role{
                    nama_role
                }
            }
            paginatorInfo {
                currentPage
                lastPage
                total
                hasMorePages
            }
        }
    }`;

    const variables = { first: parseInt(perPage), page, search: searchValue };

    const res = await fetch(API_URL, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ query, variables })
    });
    const data = await res.json();

    const tbody = document.getElementById("dataUser");
    tbody.innerHTML = "";

    const result = data.data?.allUserPaginate;
    if (!result) {
        tbody.innerHTML = `<tr><td colspan="6" class="text-center p-8 text-gray-500">
            <i class="fas fa-exclamation-triangle text-4xl mb-2"></i>
            <p>Error loading data</p>
        </td></tr>`;
        return;
    }

    const items = result.data;
    const pageInfo = result.paginatorInfo;

    if (!items || items.length === 0) {
        tbody.innerHTML = `<tr><td colspan="6" class="text-center p-8 text-gray-500">
            <i class="fas fa-inbox text-4xl mb-2"></i>
            <p class="font-semibold">Data tidak ditemukan</p>
        </td></tr>`;
        document.getElementById("pageInfo").innerText = "Tidak ada data";
        return;
    }
    items.forEach(item => {
        const statusClass = item.status === 'aktif' ? 'badge-aktif' : 'badge-nonaktif';
        const statusIcon = item.status === 'aktif' ? 'fa-check-circle' : 'fa-times-circle';
        
        tbody.innerHTML += `
            <tr class="table-row">
                <td class="px-6 py-4 text-sm font-bold text-gray-900">#${item.id}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="icon-circle bg-gradient-to-br from-emerald-100 to-sky-100 text-emerald-600 text-sm mr-3" style="width: 40px; height: 40px;">
                            <i class="fas fa-user"></i>
                        </div>
                        <span class="text-sm font-semibold text-gray-900">${item.username}</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    <i class="fas fa-envelope text-gray-400 mr-2"></i>${item.email}
                </td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-purple-100 to-blue-100 text-purple-700">
                        <i class="fas fa-user-tag mr-1"></i>${item.role.nama_role}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <span class="badge ${statusClass}">
                        <i class="fas ${statusIcon} mr-1"></i>${item.status}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center justify-center gap-2">
                        <button onclick="openEditModal(${item.id}, '${item.username}', '${item.email}', '${item.role_id}', '${item.status}')" 
                            class="btn btn-warning px-3 py-2 text-xs" title="Edit">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="hapusUser(${item.id})" 
                            class="btn btn-danger px-3 py-2 text-xs" title="Hapus">
                            <i class="fas fa-trash-alt"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });

    document.getElementById("pageInfo").innerText = 
        `Showing ${pageInfo.currentPage} of ${pageInfo.lastPage} pages (Total: ${pageInfo.total} users)`;

    document.getElementById("prevBtn").disabled = pageInfo.currentPage <= 1;
    document.getElementById("nextBtn").disabled = !pageInfo.hasMorePages;
}


function searchUser() {
    loadUser(1);
}

function prevPage() {
    if (currentPage > 1) loadUser(currentPage - 1);
}

function nextPage() {
    loadUser(currentPage + 1);
}

async function hapusUser(id) {
    if (!confirm("⚠️ Yakin ingin menghapus user ini?\nTindakan ini tidak dapat dibatalkan!")) return;

    const mutation = `
        mutation {
            deleteUser(id: ${id}) {
                id
            }
        }`;

    await fetch(API_URL, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ query: mutation })
    });

    loadUser(currentPage);
}

document.addEventListener("DOMContentLoaded", () => loadUser());