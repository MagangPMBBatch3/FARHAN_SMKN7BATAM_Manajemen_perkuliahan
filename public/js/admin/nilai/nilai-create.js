let selectedMahasiswaData = null;

async function openAddModal() {
    document.getElementById('modalAdd').classList.remove('hidden');
    await loadMahasiswaOptionsForAdd();
    resetAddForm();
}

function closeAddModal() {
    document.getElementById('modalAdd').classList.add('hidden');
    resetAddForm();
}

function resetAddForm() {
    document.getElementById('addMahasiswa').value = '';
    document.getElementById('addMataKuliah').value = '';
    document.getElementById('addMataKuliah').disabled = true;
    document.getElementById('addMataKuliah').innerHTML = '<option value="">Pilih mahasiswa terlebih dahulu</option>';
    document.getElementById('addTugas').value = '';
    document.getElementById('addQuiz').value = '';
    document.getElementById('addUts').value = '';
    document.getElementById('addUas').value = '';
    document.getElementById('addNilaiAkhir').value = '';
    document.getElementById('addNilaiHuruf').value = '';
    document.getElementById('addNilaiMutu').value = '';
    document.getElementById('addStatus').value = '';
    selectedMahasiswaData = null;
}

async function loadMahasiswaOptionsForAdd() {
    const query = `
    query {
        allMahasiswa {
            id
            nim
            nama_lengkap
        }
    }`;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query })
        });

        const result = await response.json();
        const mahasiswaList = result.data.allMahasiswa || [];
        
        const selectMahasiswa = document.getElementById('addMahasiswa');
        if (selectMahasiswa) {
            selectMahasiswa.innerHTML = '<option value="">Pilih Mahasiswa</option>';
            mahasiswaList.forEach(m => {
                selectMahasiswa.innerHTML += `<option value="${m.id}">${m.nim} - ${m.nama_lengkap}</option>`;
            });
        }
    } catch (error) {
        console.error('Error loading mahasiswa:', error);
        alert('Gagal memuat data mahasiswa');
    }
}

async function onMahasiswaChangeAdd() {
    const mahasiswaId = document.getElementById('addMahasiswa').value;
    const selectMataKuliah = document.getElementById('addMataKuliah');
    
    if (!mahasiswaId) {
        selectMataKuliah.disabled = true;
        selectMataKuliah.innerHTML = '<option value="">Pilih mahasiswa terlebih dahulu</option>';
        selectedMahasiswaData = null;
        return;
    }

    // Show loading state
    selectMataKuliah.disabled = true;
    selectMataKuliah.innerHTML = '<option value="">Loading mata kuliah...</option>';

    try {
        // Query untuk mendapatkan KRS Detail mahasiswa
        const query = `
        query($mahasiswaId: Int!) {
            krsDetailByMahasiswa(mahasiswa_id: $mahasiswaId) {
                id
                krs {
                    id
                    mahasiswa {
                        id
                        nama_lengkap
                    }
                }
                mataKuliah {
                    id
                    kode_mk
                    nama_mk
                    sks
                }
                nilai {
                    id
                }
            }
        }`;

        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ 
                query,
                variables: { mahasiswaId: parseInt(mahasiswaId) }
            })
        });

        const result = await response.json();
        const krsDetailList = result.data.krsDetailByMahasiswa || [];
        
        // Filter hanya mata kuliah yang belum memiliki nilai
        const availableKrsDetail = krsDetailList.filter(kd => !kd.nilai);

        if (availableKrsDetail.length === 0) {
            selectMataKuliah.innerHTML = '<option value="">Tidak ada mata kuliah yang tersedia (sudah dinilai semua)</option>';
            selectMataKuliah.disabled = true;
            return;
        }

        // Populate mata kuliah dropdown
        selectMataKuliah.innerHTML = '<option value="">Pilih Mata Kuliah</option>';
        availableKrsDetail.forEach(kd => {
            selectMataKuliah.innerHTML += `<option value="${kd.id}">${kd.mataKuliah.kode_mk} - ${kd.mataKuliah.nama_mk} (${kd.mataKuliah.sks} SKS)</option>`;
        });
        selectMataKuliah.disabled = false;

        // Store data for validation
        selectedMahasiswaData = {
            mahasiswaId: parseInt(mahasiswaId),
            krsDetailList: availableKrsDetail
        };

    } catch (error) {
        console.error('Error loading mata kuliah:', error);
        selectMataKuliah.innerHTML = '<option value="">Error loading data</option>';
        alert('Gagal memuat data mata kuliah');
    }
}

async function createNilai() {
    const krsDetailId = document.getElementById('addMataKuliah').value;
    const tugas = document.getElementById('addTugas').value;
    const quiz = document.getElementById('addQuiz').value;
    const uts = document.getElementById('addUts').value;
    const uas = document.getElementById('addUas').value;
    const nilai_akhir = document.getElementById('addNilaiAkhir').value;
    const nilai_huruf = document.getElementById('addNilaiHuruf').value;
    const nilai_mutu = document.getElementById('addNilaiMutu').value;
    const status = document.getElementById('addStatus').value;

    // Validasi
    if (!krsDetailId) return alert("Pilih mahasiswa dan mata kuliah terlebih dahulu!");
    if (!status) return alert("Status nilai harus diisi!");

    // Validasi tambahan: pastikan krsDetailId ada di data mahasiswa yang dipilih
    if (selectedMahasiswaData) {
        const isValid = selectedMahasiswaData.krsDetailList.some(kd => kd.id == krsDetailId);
        if (!isValid) {
            alert("Error: Mata kuliah tidak valid untuk mahasiswa ini!");
            return;
        }
    }

    try {
        const mutation = `
        mutation {
            createNilai(input: {
                krs_detail_id: ${krsDetailId}
                tugas: ${tugas || 'null'}
                quiz: ${quiz || 'null'}
                uts: ${uts || 'null'}
                uas: ${uas || 'null'}
                nilai_akhir: ${nilai_akhir || 'null'}
                nilai_huruf: ${nilai_huruf ? `"${nilai_huruf}"` : 'null'}
                nilai_mutu: ${nilai_mutu || 'null'}
                status: "${status}"
            }) {
                id
            }
        }`;

        const response = await fetch('/graphql', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query: mutation })
        });

        const result = await response.json();

        if (result.errors) {
            console.error('GraphQL Errors:', result.errors);
            alert('Gagal menyimpan data: ' + (result.errors[0]?.message || 'Unknown error'));
            return;
        }

        alert('Data nilai berhasil disimpan!');
        closeAddModal();
        loadNilaiData();
    } catch (error) {
        console.error('Error creating nilai:', error);
        alert('Terjadi kesalahan saat menyimpan data');
    }
}