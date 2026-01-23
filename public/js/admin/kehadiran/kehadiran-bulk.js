// ==================== MODAL CONTROL ====================

function openBulkInputModal() {
    document.getElementById('modalBulkInput').classList.remove('hidden');
    loadPertemuanForBulk();
}

function closeBulkInputModal() {
    document.getElementById('modalBulkInput').classList.add('hidden');
    document.getElementById('formBulkInput').reset();
    document.getElementById('mahasiswaListContainer').innerHTML = '';
}

// ==================== DATA LOADING ====================

async function loadPertemuanForBulk() {
    const query = `query { 
        allPertemuan { 
            id 
            pertemuan_ke 
            tanggal 
            kelas { 
                id
                kode_kelas 
                nama_kelas 
                mataKuliah { 
                    nama_mk 
                } 
            } 
        } 
    }`;

    try {
        const res = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query })
        });

        const data = await res.json();

        if (data.errors) {
            console.error('GraphQL Errors:', data.errors);
            alert('Error loading pertemuan: ' + data.errors[0].message);
            return;
        }

        const pertemuanList = data?.data?.allPertemuan || [];

        const select = document.getElementById('bulkPertemuan');
        select.innerHTML = '<option value="">-- Pilih Pertemuan --</option>' +
            pertemuanList.map(p =>
                `<option value="${p.id}" data-kelas-id="${p.kelas?.id}">
                    ${p.kelas?.kode_kelas} - ${p.kelas?.mataKuliah?.nama_mk} - Pertemuan ${p.pertemuan_ke} (${formatDate(p.tanggal)})
                </option>`
            ).join('');

    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat memuat pertemuan');
    }
}

async function loadMahasiswaByPertemuan(pertemuanId) {
    if (!pertemuanId) {
        document.getElementById('mahasiswaListContainer').innerHTML = '';
        return;
    }

    // Show loading
    document.getElementById('mahasiswaListContainer').innerHTML = `
        <div class="text-center py-8">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <p class="mt-2 text-sm text-gray-600">Memuat data mahasiswa...</p>
        </div>`;

    const select = document.getElementById('bulkPertemuan');
    const kelasId = select.options[select.selectedIndex].getAttribute('data-kelas-id');

    if (!kelasId) {
        alert('Kelas ID tidak ditemukan');
        document.getElementById('mahasiswaListContainer').innerHTML = '';
        return;
    }

    try {
        // 1. Get KRS Details
        const krsQuery = `
        query KrsDetailByKelas($kelas_id: ID!) {
            krsDetailByKelas(kelas_id: $kelas_id) {
                id
                krs_id
                kelas_id
                mata_kuliah_id
                sks
                status_ambil
                krs {
                    id
                    mahasiswa_id
                    mahasiswa {
                        id
                        nim
                        nama_lengkap
                    }
                }
                kelas {
                    id
                    kode_kelas
                    nama_kelas
                }
                mataKuliah {
                    id
                    kode_mk
                    nama_mk
                }
            }
        }`;

        const krsRes = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                query: krsQuery,
                variables: { kelas_id: parseInt(kelasId) }
            })
        });

        const krsData = await krsRes.json();

        // Debug response
        console.log('KRS Response:', krsData);

        if (krsData.errors) {
            console.error('GraphQL Errors:', krsData.errors);
            alert('Error: ' + krsData.errors[0].message);
            document.getElementById('mahasiswaListContainer').innerHTML =
                '<div class="text-center py-8"><p class="text-red-500">Gagal memuat data</p></div>';
            return;
        }

        const krsDetails = krsData?.data?.krsDetailByKelas || [];

        if (krsDetails.length === 0) {
            document.getElementById('mahasiswaListContainer').innerHTML =
                '<div class="text-center py-8"><p class="text-gray-500">Tidak ada mahasiswa terdaftar di kelas ini</p></div>';
            return;
        }

        // 2. Get existing kehadiran data
        const kehadiranQuery = `
        query KehadiranByPertemuan($pertemuan_id: ID!) {
            kehadiranByPertemuan(pertemuan_id: $pertemuan_id) {
                id
                mahasiswa_id
                status_kehadiran
                keterangan
            }
        }`;

        const kehadiranRes = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                query: kehadiranQuery,
                variables: { pertemuan_id: parseInt(pertemuanId) }
            })
        });

        const kehadiranData = await kehadiranRes.json();
        const existingKehadiran = kehadiranData?.data?.kehadiranByPertemuan || [];

        // Create map untuk existing kehadiran
        const kehadiranMap = {};
        existingKehadiran.forEach(k => {
            kehadiranMap[k.mahasiswa_id] = k;
        });

        // 3. Render mahasiswa list
        renderMahasiswaList(krsDetails, kehadiranMap, pertemuanId);

    } catch (error) {
        console.error('Error loading mahasiswa:', error);
        document.getElementById('mahasiswaListContainer').innerHTML =
            '<div class="text-center py-8"><p class="text-red-500">Terjadi kesalahan: ' + error.message + '</p></div>';
    }
}

// ==================== RENDERING ====================

function renderMahasiswaList(krsDetails, kehadiranMap, pertemuanId) {
    const container = document.getElementById('mahasiswaListContainer');

    // Filter out invalid entries
    const validKrsDetails = krsDetails.filter(krsDetail => {
        if (!krsDetail) {
            console.warn('Invalid krsDetail: null or undefined');
            return false;
        }
        if (!krsDetail.krs) {
            console.warn('KrsDetail missing krs relation:', krsDetail);
            return false;
        }
        if (!krsDetail.krs.mahasiswa) {
            console.warn('Krs missing mahasiswa relation:', krsDetail.krs);
            return false;
        }
        return true;
    });

    console.log('Valid KRS Details:', validKrsDetails.length, '/', krsDetails.length);

    if (validKrsDetails.length === 0) {
        container.innerHTML = '<div class="text-center py-8"><p class="text-gray-500">Tidak ada data mahasiswa yang valid</p></div>';
        return;
    }

    let html = `
        <div class="mb-4 p-4 bg-gray-50 rounded-lg flex justify-between items-center">
            <h3 class="text-sm font-semibold text-gray-700">
                📋 Daftar Mahasiswa: <span class="text-blue-600">${validKrsDetails.length} orang</span>
            </h3>
            <div class="flex gap-2">
                <button type="button" onclick="setAllStatus('Hadir')" 
                    class="px-3 py-1.5 text-xs font-medium bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors shadow-sm">
                    ✓ Set Semua Hadir
                </button>
                <button type="button" onclick="setAllStatus('Alpa')" 
                    class="px-3 py-1.5 text-xs font-medium bg-red-600 text-white rounded-md hover:bg-red-700 transition-colors shadow-sm">
                    ✗ Set Semua Alpa
                </button>
            </div>
        </div>
        <div class="space-y-3 max-h-96 overflow-y-auto pr-2">`;

    validKrsDetails.forEach((krsDetail, index) => {
        // Akses mahasiswa via krs.mahasiswa dengan validasi
        const mahasiswa = krsDetail.krs.mahasiswa;
        const mahasiswaId = mahasiswa.id;

        const existingData = kehadiranMap[mahasiswaId];
        const currentStatus = existingData?.status_kehadiran || 'Hadir';
        const currentKeterangan = existingData?.keterangan || '';
        const kehadiranId = existingData?.id || null;

        html += `
            <div class="border border-gray-200 rounded-lg p-4 bg-white hover:bg-gray-50 transition-all shadow-sm" 
                 data-mahasiswa-id="${mahasiswaId}">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex-1">
                        
                        <!-- Header Mahasiswa -->
                        <div class="flex items-center gap-2 mb-3">
                            <span class="px-2 py-1 text-xs font-mono font-semibold bg-gray-100 text-gray-800 rounded">
                                ${mahasiswa.nim || 'N/A'}
                            </span>
                            <span class="text-sm font-medium text-gray-900">
                                ${mahasiswa.nama_lengkap || 'Nama tidak tersedia'}
                            </span>
                            ${existingData ? '<span class="px-2 py-0.5 text-xs bg-blue-100 text-blue-800 rounded-full">Sudah diinput</span>' : ''}
                        </div>
                        
                        <!-- Hidden Inputs -->
                        <input type="hidden" name="kehadiran[${index}][krs_detail_id]" value="${krsDetail.id}">
                        <input type="hidden" name="kehadiran[${index}][mahasiswa_id]" value="${mahasiswaId}">
                        <input type="hidden" name="kehadiran[${index}][pertemuan_id]" value="${pertemuanId}">
                        ${kehadiranId ? `<input type="hidden" name="kehadiran[${index}][kehadiran_id]" value="${kehadiranId}">` : ''}
                        
                        <!-- Status Radio Buttons -->
                        <div class="grid grid-cols-4 gap-2 mb-3">
                            <label class="radio-label relative flex items-center p-2.5 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-green-400 transition-all" data-status="Hadir">
                                <input type="radio" name="kehadiran[${index}][status]" value="Hadir" 
                                    ${currentStatus === 'Hadir' ? 'checked' : ''}
                                    class="w-4 h-4 text-green-600 focus:ring-green-500"
                                    onchange="handleStatusChange(${index})">
                                <span class="ml-2 text-xs font-medium text-gray-700">✓ Hadir</span>
                            </label>
                            
                            <label class="radio-label relative flex items-center p-2.5 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-blue-400 transition-all" data-status="Izin">
                                <input type="radio" name="kehadiran[${index}][status]" value="Izin"
                                    ${currentStatus === 'Izin' ? 'checked' : ''}
                                    class="w-4 h-4 text-blue-600 focus:ring-blue-500"
                                    onchange="handleStatusChange(${index})">
                                <span class="ml-2 text-xs font-medium text-gray-700">ℹ Izin</span>
                            </label>
                            
                            <label class="radio-label relative flex items-center p-2.5 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-yellow-400 transition-all" data-status="Sakit">
                                <input type="radio" name="kehadiran[${index}][status]" value="Sakit"
                                    ${currentStatus === 'Sakit' ? 'checked' : ''}
                                    class="w-4 h-4 text-yellow-600 focus:ring-yellow-500"
                                    onchange="handleStatusChange(${index})">
                                <span class="ml-2 text-xs font-medium text-gray-700">+ Sakit</span>
                            </label>
                            
                            <label class="radio-label relative flex items-center p-2.5 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-red-400 transition-all" data-status="Alpa">
                                <input type="radio" name="kehadiran[${index}][status]" value="Alpa"
                                    ${currentStatus === 'Alpa' ? 'checked' : ''}
                                    class="w-4 h-4 text-red-600 focus:ring-red-500"
                                    onchange="handleStatusChange(${index})">
                                <span class="ml-2 text-xs font-medium text-gray-700">✗ Alpa</span>
                            </label>
                        </div>
                        
                        <!-- Keterangan Input (Hidden by default untuk Hadir) -->
                        <div id="keterangan-container-${index}" class="${currentStatus === 'Hadir' ? 'hidden' : ''}">
                            <input type="text" 
                                name="kehadiran[${index}][keterangan]" 
                                value="${currentKeterangan}"
                                placeholder="Keterangan (opsional)" 
                                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all">
                        </div>
                    </div>
                </div>
            </div>
        `;
    });

    html += '</div>';
    container.innerHTML = html;
    
    // Update border colors after rendering
    updateAllRadioBorders();
}

// ==================== EVENT HANDLERS ====================

function handleStatusChange(index) {
    const statusRadios = document.getElementsByName(`kehadiran[${index}][status]`);
    let selectedStatus = '';

    statusRadios.forEach(radio => {
        if (radio.checked) selectedStatus = radio.value;
    });

    // Update border colors for this row
    updateRadioBorders(index);

    const keteranganContainer = document.getElementById(`keterangan-container-${index}`);
    const keteranganInput = document.querySelector(`input[name="kehadiran[${index}][keterangan]"]`);

    if (selectedStatus === 'Hadir') {
        keteranganContainer.classList.add('hidden');
        keteranganInput.value = '';
    } else {
        keteranganContainer.classList.remove('hidden');
        keteranganInput.value = '';
    }
}

function updateRadioBorders(index) {
    const statusRadios = document.getElementsByName(`kehadiran[${index}][status]`);
    
    statusRadios.forEach(radio => {
        const label = radio.closest('.radio-label');
        const status = label.getAttribute('data-status');
        
        // Remove all status-specific classes
        label.classList.remove(
            'border-green-500', 'bg-green-50',
            'border-blue-500', 'bg-blue-50',
            'border-yellow-500', 'bg-yellow-50',
            'border-red-500', 'bg-red-50'
        );
        
        // Add default gray border
        label.classList.add('border-gray-300');
        
        // If this radio is checked, add status-specific classes
        if (radio.checked) {
            label.classList.remove('border-gray-300');
            
            switch(status) {
                case 'Hadir':
                    label.classList.add('border-green-500', 'bg-green-50');
                    break;
                case 'Izin':
                    label.classList.add('border-blue-500', 'bg-blue-50');
                    break;
                case 'Sakit':
                    label.classList.add('border-yellow-500', 'bg-yellow-50');
                    break;
                case 'Alpa':
                    label.classList.add('border-red-500', 'bg-red-50');
                    break;
            }
        }
    });
}

function updateAllRadioBorders() {
    // Get all radio button groups
    const allRadios = document.querySelectorAll('input[type="radio"][name^="kehadiran"]');
    const processedIndexes = new Set();
    
    allRadios.forEach(radio => {
        const match = radio.name.match(/\[(\d+)\]/);
        if (match) {
            const index = parseInt(match[1]);
            if (!processedIndexes.has(index)) {
                updateRadioBorders(index);
                processedIndexes.add(index);
            }
        }
    });
}

function setAllStatus(status) {
    const radios = document.querySelectorAll('input[type="radio"]');
    radios.forEach(radio => {
        if (radio.value === status) {
            radio.checked = true;
            const match = radio.name.match(/\[(\d+)\]/);
            if (match) handleStatusChange(parseInt(match[1]));
        }
    });
}

// ==================== FORM SUBMISSION ====================

async function submitBulkKehadiran() {
    const pertemuanId = parseInt(document.getElementById('bulkPertemuan').value);
    if (!pertemuanId) {
        alert('Pilih pertemuan terlebih dahulu!');
        return;
    }

    const form = document.getElementById('formBulkInput');
    const formData = new FormData(form);

    const kehadiranData = [];
    const updates = [];

    for (let i = 0; ; i++) {
        const krsDetailId = formData.get(`kehadiran[${i}][krs_detail_id]`);
        if (!krsDetailId) break;

        const data = {
            pertemuan_id: pertemuanId,
            mahasiswa_id: parseInt(formData.get(`kehadiran[${i}][mahasiswa_id]`)),
            krs_detail_id: parseInt(krsDetailId),
            status_kehadiran: formData.get(`kehadiran[${i}][status]`),
            keterangan: formData.get(`kehadiran[${i}][keterangan]`) || null
        };

        const kehadiranId = formData.get(`kehadiran[${i}][kehadiran_id]`);
        if (kehadiranId) {
            updates.push({ id: parseInt(kehadiranId), ...data });
        } else {
            kehadiranData.push(data);
        }
    }

    console.log('Data to create:', kehadiranData);
    console.log('Data to update:', updates);

    try {
        // Create new kehadiran
        if (kehadiranData.length > 0) {
            for (const data of kehadiranData) {
                const mutation = `
                mutation CreateKehadiran($input: CreateKehadiranInput!) {
                    createKehadiran(input: $input) {
                        id
                        mahasiswa_id
                        status_kehadiran
                    }
                }`;

                const response = await fetch(API_URL, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        query: mutation,
                        variables: { input: data }
                    })
                });

                const result = await response.json();
                if (result.errors) {
                    console.error('Create error:', result.errors);
                    alert('Error creating kehadiran: ' + result.errors[0].message);
                    return;
                }
            }
        }

        // Update existing kehadiran
        if (updates.length > 0) {
            const mutation = `
            mutation BulkUpdateKehadiran($inputs: [BulkUpdateKehadiranInput!]!) {
                bulkUpdateKehadiran(inputs: $inputs) {
                    id
                    status_kehadiran
                }
            }`;

            const response = await fetch(API_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    query: mutation,
                    variables: {
                        inputs: updates.map(u => ({
                            id: u.id,
                            status_kehadiran: u.status_kehadiran,
                            keterangan: u.keterangan
                        }))
                    }
                })
            });

            const result = await response.json();
            if (result.errors) {
                console.error('Update error:', result.errors);
                alert('Error updating kehadiran: ' + result.errors[0].message);
                return;
            }
        }

        alert('Data berhasil disimpan!');
        closeBulkInputModal();
        loadKehadiranData(currentPageAktif, currentPageArsip);

    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan: ' + error.message);
    }
}

// ==================== HELPER FUNCTIONS ====================

function formatDate(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
}