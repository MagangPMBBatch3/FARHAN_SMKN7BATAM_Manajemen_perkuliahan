function openEditModal(id, minIpk, maxIpk, maxSks, keterangan) {
    document.getElementById('editId').value = id;
    document.getElementById('editMinIpk').value = minIpk;
    document.getElementById('editMaxIpk').value = maxIpk;
    document.getElementById('editMaxSks').value = maxSks;
    document.getElementById('editKeterangan').value = keterangan || '';
    document.getElementById('modalEdit').classList.remove('hidden');
}

/**
 * Tutup modal edit
 */
function closeEditModal() {
    document.getElementById('modalEdit').classList.add('hidden');
}

/**
 * Update SKS Limit
 */
async function updateSksLimit() {
    const id = document.getElementById('editId').value;
    const minIpk = parseFloat(document.getElementById('editMinIpk').value);
    const maxIpk = parseFloat(document.getElementById('editMaxIpk').value);
    const maxSks = parseInt(document.getElementById('editMaxSks').value);
    const keterangan = document.getElementById('editKeterangan').value;

    // Validasi
    if (isNaN(minIpk)) return alert("IPK Minimal tidak boleh kosong");
    if (isNaN(maxIpk)) return alert("IPK Maksimal tidak boleh kosong");
    if (isNaN(maxSks)) return alert("SKS Maksimal tidak boleh kosong");
    if (minIpk > maxIpk) return alert("IPK Minimal tidak boleh lebih besar dari IPK Maksimal!");

    const mutation = `
    mutation {
        updateSksLimit(id: ${id}, input: {
            min_ipk: ${minIpk}, 
            max_ipk: ${maxIpk}, 
            max_sks: ${maxSks}, 
            keterangan: "${keterangan}"
        }) {
            id
            min_ipk
            max_ipk
            max_sks
            keterangan
        }
    }`;

    await fetch('/graphql', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ query: mutation })
    });

    closeEditModal();
    loadSksLimitData();
}