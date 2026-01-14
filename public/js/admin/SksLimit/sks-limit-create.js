function openAddModal() {
    document.getElementById('modalAdd').classList.remove('hidden');
}

/**
 * Tutup modal tambah
 */
function closeAddModal() {
    document.getElementById('modalAdd').classList.add('hidden');
    document.getElementById('addMinIpk').value = '';
    document.getElementById('addMaxIpk').value = '';
    document.getElementById('addMaxSks').value = '';
    document.getElementById('addKeterangan').value = '';
}

/**
 * Create SKS Limit
 */
async function createSksLimit() {
    const minIpk = parseFloat(document.getElementById('addMinIpk').value);
    const maxIpk = parseFloat(document.getElementById('addMaxIpk').value);
    const maxSks = parseInt(document.getElementById('addMaxSks').value);
    const keterangan = document.getElementById('addKeterangan').value;

    // Validasi
    if (isNaN(minIpk)) return alert("IPK Minimal harus diisi!");
    if (isNaN(maxIpk)) return alert("IPK Maksimal harus diisi!");
    if (isNaN(maxSks)) return alert("SKS Maksimal harus diisi!");
    if (minIpk > maxIpk) return alert("IPK Minimal tidak boleh lebih besar dari IPK Maksimal!");

    const mutation = `
    mutation {
        createSksLimit(input: {
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

    closeAddModal();
    loadSksLimitData();
}