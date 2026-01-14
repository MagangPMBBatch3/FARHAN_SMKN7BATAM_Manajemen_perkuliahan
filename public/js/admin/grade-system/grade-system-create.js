function openAddModal() {
    document.getElementById('modalAdd').classList.remove('hidden');
}

function closeAddModal() {
    document.getElementById('modalAdd').classList.add('hidden');
    document.getElementById('formAddGradeSystem').reset();
}

async function createGradeSystem() {
    const grade = document.getElementById('addGrade').value.trim();
    const minScore = parseFloat(document.getElementById('addMinScore').value);
    const maxScore = parseFloat(document.getElementById('addMaxScore').value);
    const gradePoint = parseFloat(document.getElementById('addGradePoint').value);
    const statusKelulusan = document.getElementById('addStatusKelulusan').value;
    const keterangan = document.getElementById('addKeterangan').value.trim();

    if (!grade) return alert("Grade harus diisi!");
    if (isNaN(minScore)) return alert("Nilai minimal harus diisi!");
    if (isNaN(maxScore)) return alert("Nilai maksimal harus diisi!");
    if (isNaN(gradePoint)) return alert("Grade point harus diisi!");
    if (!statusKelulusan) return alert("Status kelulusan harus dipilih!");
    if (minScore > maxScore) return alert("Nilai minimal tidak boleh lebih besar dari maksimal!");
    
    // Konversi status kelulusan ke enum GraphQL
    const statusEnum = statusKelulusan === "Lulus" ? "Lulus" : "TidakLulus";
    
    const mutation = `
    mutation {
        createGradeSystem(input: {
            grade: "${grade}", 
            min_score: ${minScore}, 
            max_score: ${maxScore}, 
            grade_point: ${gradePoint},
            status_kelulusan: ${statusEnum},
            keterangan: "${keterangan}"
        }) {
            id grade min_score max_score grade_point status_kelulusan keterangan
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
        loadGradeSystemData();
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat menyimpan data');
    }
}