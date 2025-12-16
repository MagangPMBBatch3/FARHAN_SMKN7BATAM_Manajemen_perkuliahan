export async function loadMahasiswaOptions(API_URL) {
    const query = `
    query {
        allMahasiswa {
            id
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

        const selectEdit = document.getElementById('editMahasiswaId');
        if (selectEdit) {
            selectEdit.innerHTML = '<option value="">Pilih Mahasiswa</option>';
            mahasiswaList.forEach(m => {
                selectEdit.innerHTML += `<option value="${m.id}">${m.nama_lengkap}</option>`;
            });
        }

        const selectAdd = document.getElementById('addmahasiswaId');
        if (selectAdd) {
            selectAdd.innerHTML = '<option value="">Pilih Mahasiswa</option>';
            mahasiswaList.forEach(m => {
                selectAdd.innerHTML += `<option value="${m.id}">${m.nama_lengkap}</option>`;
            });
        }

    } catch (error) {
        console.error('Error loading mahasiswa:', error);
    }
}
