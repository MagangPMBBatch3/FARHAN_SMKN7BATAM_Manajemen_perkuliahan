function openEditModal(item) {
    document.getElementById('editId').value = item.id;
    document.getElementById('editKelas').value = item.kelas_id;
    document.getElementById('editPertemuanKe').value = item.pertemuan_ke;
    document.getElementById('editTanggal').value = item.tanggal;
    document.getElementById('editWaktuMulai').value = item.waktu_mulai;
    document.getElementById('editWaktuSelesai').value = item.waktu_selesai;
    document.getElementById('editMateri').value = item.materi || '';
    document.getElementById('editMetode').value = item.metode;
    if (item.metode == "TatapMuka") {
        document.getElementById("editMetode").value = "Tatap Muka"
    }
    document.getElementById('editRuangan').value = item.ruangan_id || '';
    document.getElementById('editStatusPertemuan').value = item.status_pertemuan;
    document.getElementById('editLinkDaring').value = item.link_daring || '';
    document.getElementById('editCatatan').value = item.catatan || '';
    
    toggleMetodeFields('edit');
    document.getElementById('modalEdit').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('modalEdit').classList.add('hidden');
}

async function updatePertemuan() {
    const id = document.getElementById('editId').value;
    const kelasId = parseInt(document.getElementById('editKelas').value);
    const pertemuanKe = parseInt(document.getElementById('editPertemuanKe').value);
    const tanggal = document.getElementById('editTanggal').value;
    const waktuMulai = document.getElementById('editWaktuMulai').value;
    const waktuSelesai = document.getElementById('editWaktuSelesai').value;
    const materi = document.getElementById('editMateri').value.trim();
    const metode = document.getElementById('editMetode').value;
    const ruanganId = document.getElementById('editRuangan').value ? parseInt(document.getElementById('editRuangan').value) : null;
    const statusPertemuan = document.getElementById('editStatusPertemuan').value;
    const linkDaring = document.getElementById('editLinkDaring').value.trim();
    const catatan = document.getElementById('editCatatan').value.trim();

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
        updatePertemuan(id: ${id}, input: {
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
            alert('Gagal mengupdate data: ' + result.errors[0].message);
            return;
        }

        alert('Data berhasil diupdate!');
        closeEditModal();
        loadPertemuanData();
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupdate data');
    }
}