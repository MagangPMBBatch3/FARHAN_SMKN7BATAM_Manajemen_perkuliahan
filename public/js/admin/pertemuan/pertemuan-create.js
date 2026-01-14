function openAddModal() {
    document.getElementById('modalAdd').classList.remove('hidden');
    toggleMetodeFields('add');
}

function closeAddModal() {
    document.getElementById('modalAdd').classList.add('hidden');
    document.getElementById('formAddPertemuan').reset();
}

function toggleMetodeFields(type) {
    const prefix = type === 'add' ? 'add' : 'edit';
    const metode = document.getElementById(`${prefix}Metode`).value;
    const ruanganField = document.getElementById(`${prefix}RuanganField`);
    const linkField = document.getElementById(`${prefix}LinkField`);
    
    if (metode === 'Tatap Muka') {
        ruanganField.classList.remove('hidden');
        linkField.classList.add('hidden');
        document.getElementById(`${prefix}Ruangan`).required = true;
        document.getElementById(`${prefix}LinkDaring`).required = false;
    } else if (metode === 'Daring') {
        ruanganField.classList.add('hidden');
        linkField.classList.remove('hidden');
        document.getElementById(`${prefix}Ruangan`).required = false;
        document.getElementById(`${prefix}LinkDaring`).required = true;
    } else if (metode === 'Hybrid') {
        ruanganField.classList.remove('hidden');
        linkField.classList.remove('hidden');
        document.getElementById(`${prefix}Ruangan`).required = true;
        document.getElementById(`${prefix}LinkDaring`).required = true;
    }
}

async function createPertemuan() {
    const kelasId = parseInt(document.getElementById('addKelas').value);
    const pertemuanKe = parseInt(document.getElementById('addPertemuanKe').value);
    const tanggal = document.getElementById('addTanggal').value;
    const waktuMulai = document.getElementById('addWaktuMulai').value;
    const waktuSelesai = document.getElementById('addWaktuSelesai').value;
    const materi = document.getElementById('addMateri').value.trim();
    const metode = document.getElementById('addMetode').value;
    const ruanganId = document.getElementById('addRuangan').value ? parseInt(document.getElementById('addRuangan').value) : null;
    const statusPertemuan = document.getElementById('addStatusPertemuan').value;
    const linkDaring = document.getElementById('addLinkDaring').value.trim();
    const catatan = document.getElementById('addCatatan').value.trim();

    if (!kelasId) return alert("Kelas harus dipilih!");
    if (!pertemuanKe) return alert("Pertemuan ke- harus diisi!");
    if (!tanggal) return alert("Tanggal harus diisi!");
    if (!waktuMulai) return alert("Waktu mulai harus diisi!");
    if (!waktuSelesai) return alert("Waktu selesai harus diisi!");
    if (!metode) return alert("Metode harus dipilih!");
    if (!statusPertemuan) return alert("Status pertemuan harus dipilih!");

    // Konversi enum
    const metodeEnum = metode.replace(/ /g, '');
    const statusEnum = statusPertemuan.replace(/ /g, '');

    const mutation = `
    mutation {
        createPertemuan(input: {
            kelas_id: ${kelasId},
            pertemuan_ke: ${pertemuanKe},
            tanggal: "${tanggal}",
            waktu_mulai: "${waktuMulai}",
            waktu_selesai: "${waktuSelesai}",
            materi: "${materi}",
            metode: ${metodeEnum},
            ${ruanganId ? `ruangan_id: ${ruanganId},` : ''}
            status_pertemuan: ${statusEnum},
            ${linkDaring ? `link_daring: "${linkDaring}",` : ''}
            catatan: "${catatan}"
        }) {
            id kelas_id pertemuan_ke tanggal
        }
    }`;

    try {
        const response = await fetch(API_URL, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ query: mutation })
        });

        const result = await response.json();
        
        if (result.errors) {
            console.error('GraphQL Error:', result.errors);
            alert('Gagal menyimpan data: ' + result.errors[0].message);
            return;
        }

        alert('Data berhasil disimpan!');
        closeAddModal();
        loadPertemuanData();
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan data');
    }
}