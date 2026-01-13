function openEditModal(id, grade, minScore, maxScore, gradePoint, statusKelulusan, keterangan) {
    document.getElementById('editId').value = id;
    document.getElementById('editGrade').value = grade;
    document.getElementById('editMinScore').value = minScore;
    document.getElementById('editMaxScore').value = maxScore;
    document.getElementById('editGradePoint').value = gradePoint;
    document.getElementById('editStatusKelulusan').value = statusKelulusan;
    document.getElementById('editKeterangan').value = keterangan;
    document.getElementById('modalEdit').classList.remove('hidden');
}

function closeEditModal() {
    document.getElementById('modalEdit').classList.add('hidden');
}

async function updateGradeSystem() {
    const id = document.getElementById('editId').value;
    const grade = document.getElementById('editGrade').value.trim();
    const minScore = parseFloat(document.getElementById('editMinScore').value);
    const maxScore = parseFloat(document.getElementById('editMaxScore').value);
    const gradePoint = parseFloat(document.getElementById('editGradePoint').value);
    const statusKelulusan = document.getElementById('editStatusKelulusan').value;
    const keterangan = document.getElementById('editKeterangan').value.trim();

    if (!grade) return alert("Grade tidak boleh kosong!");
    if (isNaN(minScore)) return alert("Nilai minimal tidak boleh kosong!");
    if (isNaN(maxScore)) return alert("Nilai maksimal tidak boleh kosong!");
    if (isNaN(gradePoint)) return alert("Grade point tidak boleh kosong!");
    if (minScore > maxScore) return alert("Nilai minimal tidak boleh lebih besar dari maksimal!");
    if (!statusKelulusan) return alert("Status kelulusan harus dipilih!");

    // Konversi status kelulusan ke enum GraphQL
    const statusEnum = statusKelulusan === "Lulus" ? "Lulus" : "TidakLulus";

    const mutation = `
    mutation {
        updateGradeSystem(id: ${id}, input: {
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
            alert('Gagal mengupdate data: ' + result.errors[0].message);
            return;
        }

        alert('Data berhasil diupdate!');
        closeEditModal();
        loadGradeSystemData();
    } catch (error) {
        console.error('Error:', error);
        alert('Terjadi kesalahan saat mengupdate data');
    }
}