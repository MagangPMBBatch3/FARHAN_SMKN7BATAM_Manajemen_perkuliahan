const API_URL = "/graphql";
let currentMahasiswaId = null;
let currentMahasiswaData = null;

// Ambil ID dari URL
function getMahasiswaIdFromUrl() {
    const path = window.location.pathname;
    const segments = path.split('/');
    return segments[segments.length - 1]; // Ambil segment terakhir (id)
}

async function loadMahasiswaDetail() {
    currentMahasiswaId = getMahasiswaIdFromUrl();
    
    const query = `
    query($id: Int!) {
        mahasiswa(id: $id) {
            id
            user_id
            nim
            nama_lengkap
            jurusan {
                id
                nama_jurusan
            }
            angkatan
            jenis_kelamin
            tempat_lahir
            tanggal_lahir
            alamat
            no_hp
            email_pribadi
            nama_ayah
            nama_ibu
            no_hp_ortu
            status
            semester_saat_ini
            ipk
            total_sks
            created_at
            updated_at
        }
    }`;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query: query, 
                variables: { id: parseInt(currentMahasiswaId) } 
            })
        });

        const result = await response.json();
        
        if (result.errors) {
            console.error('GraphQL Errors:', result.errors);
            alert('Gagal memuat data mahasiswa');
            return;
        }

        currentMahasiswaData = result.data.mahasiswa;
        renderMahasiswaDetail(currentMahasiswaData);
        
        // Hide loading, show content
        document.getElementById('loading').classList.add('hidden');
        document.getElementById('content').classList.remove('hidden');
        
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memuat data');
    }
}

function renderMahasiswaDetail(data) {
    // Header Section
    const initial = data.nama_lengkap.charAt(0).toUpperCase();
    document.getElementById('initial').textContent = initial;
    document.getElementById('nama').textContent = data.nama_lengkap;
    document.getElementById('nim').textContent = data.nim;
    document.getElementById('statusHeader').textContent = data.status || '-';

    // Tab Biodata
    document.getElementById('namaLengkap').textContent = data.nama_lengkap;
    document.getElementById('jenisKelamin').textContent = data.jenis_kelamin || '-';
    document.getElementById('tempatLahir').textContent = data.tempat_lahir || '-';
    document.getElementById('tanggalLahir').textContent = formatDate(data.tanggal_lahir);
    document.getElementById('alamat').textContent = data.alamat || '-';

    // Tab Akademik
    document.getElementById('nimAkademik').textContent = data.nim;
    document.getElementById('jurusan').textContent = data.jurusan?.nama_jurusan || '-';
    document.getElementById('angkatan').textContent = data.angkatan;
    
    // Status dengan innerHTML untuk badge
    const statusElement = document.getElementById('status');
    statusElement.innerHTML = getStatusBadge(data.status);
    
    document.getElementById('semester').textContent = data.semester_saat_ini || '-';
    document.getElementById('totalSks').textContent = data.total_sks || '0';
    document.getElementById('ipkBesar').textContent = data.ipk ? data.ipk.toFixed(2) : '0.00';
    document.getElementById('totalSksBesar').textContent = data.total_sks || '0';

    // Tab Kontak
    document.getElementById('noHp').textContent = data.no_hp || '-';
    document.getElementById('emailPribadi').textContent = data.email_pribadi || '-';
    document.getElementById('namaAyah').textContent = data.nama_ayah || '-';
    document.getElementById('namaIbu').textContent = data.nama_ibu || '-';
    document.getElementById('noHpOrtu').textContent = data.no_hp_ortu || '-';

    // Metadata
    document.getElementById('userId').textContent = data.user_id;
    document.getElementById('createdAt').textContent = formatDateTime(data.created_at);
    document.getElementById('updatedAt').textContent = formatDateTime(data.updated_at);
}

function getStatusBadge(status) {
    const badges = {
        'AKTIF': '<span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-semibold">Aktif</span>',
        'CUTI': '<span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold">Cuti</span>',
        'LULUS': '<span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">Lulus</span>',
        'DO': '<span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm font-semibold">DO</span>'
    };
    return badges[status?.toUpperCase()] || `<span class="bg-gray-100 text-gray-800 px-3 py-1 rounded-full text-sm font-semibold">${status || '-'}</span>`;
}

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    const options = { year: 'numeric', month: 'long', day: 'numeric' };
    return date.toLocaleDateString('id-ID', options);
}

function formatDateTime(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    const options = { 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    };
    return date.toLocaleDateString('id-ID', options);
}

// Tab Navigation
function showTab(tabName) {
    // Update tab buttons
    const tabs = ['biodata', 'akademik', 'kontak'];
    tabs.forEach(tab => {
        const tabBtn = document.getElementById(`tab${tab.charAt(0).toUpperCase() + tab.slice(1)}`);
        const content = document.getElementById(`content${tab.charAt(0).toUpperCase() + tab.slice(1)}`);
        
        if (tab === tabName) {
            tabBtn.classList.add('border-b-2', 'border-blue-500', 'text-blue-600', 'font-semibold');
            tabBtn.classList.remove('text-gray-600');
            content.classList.remove('hidden');
        } else {
            tabBtn.classList.remove('border-b-2', 'border-blue-500', 'text-blue-600', 'font-semibold');
            tabBtn.classList.add('text-gray-600');
            content.classList.add('hidden');
        }
    });
}
// Delete/Archive
async function confirmDelete() {
    if (!confirm(`Arsipkan mahasiswa ${currentMahasiswaData.nama_lengkap}?`)) return;
    
    const mutation = `
    mutation {
        deleteMahasiswa(id: ${currentMahasiswaId}) { id }
    }`;

    try {
        await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query: mutation })
        });
        
        alert('Mahasiswa berhasil diarsipkan');
        window.location.href = '/mahasiswa'; // Redirect ke list
        
    } catch (error) {
        console.error('Error:', error);
        alert('Gagal mengarsipkan mahasiswa');
    }
}

// Load data on page load
document.addEventListener('DOMContentLoaded', () => {
    loadMahasiswaDetail();
});