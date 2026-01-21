<!-- Modal Detail Nilai Mahasiswa -->
<div id="modalDetailNilai" class="hidden">
    <div class="fixed inset-0 bg-black/50 z-40 flex items-center justify-center overflow-y-auto p-4">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-3xl max-h-[90vh] overflow-hidden flex flex-col">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <svg class="w-8 h-8 text-white mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <div>
                            <h2 class="text-xl font-semibold text-white">Detail Nilai</h2>
                            <p class="text-blue-100 text-sm">Rincian komponen penilaian</p>
                        </div>
                    </div>
                    <button type="button" onclick="closeDetailModal()" class="text-blue-100 hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="flex-1 overflow-y-auto px-6 py-6">
                <div class="space-y-5">
                    <!-- Info Mata Kuliah -->
                    <div class="bg-gradient-to-r from-indigo-50 to-blue-50 border border-indigo-200 rounded-lg p-4">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Informasi Mata Kuliah</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <p class="text-gray-600">Kode MK:</p>
                                <p id="detailKodeMK" class="font-semibold text-gray-900">-</p>
                            </div>
                            <div>
                                <p class="text-gray-600">SKS:</p>
                                <p id="detailSKS" class="font-semibold text-gray-900">-</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-gray-600">Nama Mata Kuliah:</p>
                                <p id="detailNamaMK" class="font-semibold text-gray-900">-</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Kelas:</p>
                                <p id="detailKelas" class="font-semibold text-gray-900">-</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Dosen:</p>
                                <p id="detailDosen" class="font-semibold text-gray-900">-</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Semester:</p>
                                <p id="detailSemester" class="font-semibold text-gray-900">-</p>
                            </div>
                            <div>
                                <p class="text-gray-600">Tahun Ajaran:</p>
                                <p id="detailTahunAjaran" class="font-semibold text-gray-900">-</p>
                            </div>
                        </div>
                    </div>

                    <!-- Komponen Nilai -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Komponen Penilaian</h3>
                        <div class="space-y-3">
                            <!-- Tugas -->
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Tugas</p>
                                        <p id="detailBobotTugas" class="text-xs text-gray-500">Bobot: -</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p id="detailNilaiTugas" class="text-lg font-bold text-blue-600">-</p>
                                    <p class="text-xs text-gray-500">/ 100</p>
                                </div>
                            </div>

                            <!-- Quiz -->
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Quiz</p>
                                        <p id="detailBobotQuiz" class="text-xs text-gray-500">Bobot: -</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p id="detailNilaiQuiz" class="text-lg font-bold text-purple-600">-</p>
                                    <p class="text-xs text-gray-500">/ 100</p>
                                </div>
                            </div>

                            <!-- UTS -->
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">UTS</p>
                                        <p id="detailBobotUTS" class="text-xs text-gray-500">Bobot: -</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p id="detailNilaiUTS" class="text-lg font-bold text-yellow-600">-</p>
                                    <p class="text-xs text-gray-500">/ 100</p>
                                </div>
                            </div>

                            <!-- UAS -->
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">UAS</p>
                                        <p id="detailBobotUAS" class="text-xs text-gray-500">Bobot: -</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p id="detailNilaiUAS" class="text-lg font-bold text-red-600">-</p>
                                    <p class="text-xs text-gray-500">/ 100</p>
                                </div>
                            </div>

                            <!-- Kehadiran -->
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Kehadiran</p>
                                        <p id="detailBobotKehadiran" class="text-xs text-gray-500">Bobot: -</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p id="detailNilaiKehadiran" class="text-lg font-bold text-green-600">-</p>
                                    <p class="text-xs text-gray-500">/ 100</p>
                                </div>
                            </div>

                            <!-- Praktikum -->
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-3">
                                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Praktikum</p>
                                        <p id="detailBobotPraktikum" class="text-xs text-gray-500">Bobot: -</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p id="detailNilaiPraktikum" class="text-lg font-bold text-indigo-600">-</p>
                                    <p class="text-xs text-gray-500">/ 100</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Nilai Akhir -->
                    <div class="bg-gradient-to-r from-yellow-50 to-orange-50 border-2 border-yellow-300 rounded-lg p-5">
                        <h3 class="text-sm font-semibold text-gray-700 mb-4 text-center">Hasil Akhir</h3>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="text-center">
                                <p class="text-xs text-gray-600 mb-1">Nilai Akhir</p>
                                <p id="detailNilaiAkhir" class="text-3xl font-bold text-blue-600">-</p>
                            </div>
                            <div class="text-center">
                                <p class="text-xs text-gray-600 mb-1">Grade</p>
                                <div id="detailGradeContainer" class="inline-block">
                                    <span id="detailGrade" class="text-3xl font-bold px-4 py-2 rounded-lg">-</span>
                                </div>
                            </div>
                            <div class="text-center">
                                <p class="text-xs text-gray-600 mb-1">Nilai Mutu</p>
                                <p id="detailNilaiMutu" class="text-3xl font-bold text-purple-600">-</p>
                            </div>
                        </div>
                        <div class="mt-4 text-center">
                            <span id="detailStatus" class="px-4 py-2 text-sm font-semibold rounded-full">-</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <div class="flex justify-end">
                    <button type="button" onclick="closeDetailModal()" 
                        class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function openDetailModal(nilaiData) {
    console.log('Detail nilai:', nilaiData);
    
    // Info Mata Kuliah
    document.getElementById('detailKodeMK').textContent = nilaiData.krsDetail.mataKuliah.kode_mk;
    document.getElementById('detailNamaMK').textContent = nilaiData.krsDetail.mataKuliah.nama_mk;
    document.getElementById('detailSKS').textContent = nilaiData.krsDetail.mataKuliah.sks;
    document.getElementById('detailKelas').textContent = nilaiData.krsDetail.kelas.kode_kelas + ' - ' + nilaiData.krsDetail.kelas.nama_kelas;
    document.getElementById('detailDosen').textContent = nilaiData.krsDetail.kelas.dosen.nama_lengkap;
    document.getElementById('detailSemester').textContent = nilaiData.krsDetail.krs.semester.nama_semester;
    document.getElementById('detailTahunAjaran').textContent = nilaiData.krsDetail.krs.semester.tahun_ajaran;
    
    // Komponen Nilai (akan menampilkan bobot jika tersedia dari backend)
    // Untuk sekarang, hanya tampilkan nilai
    document.getElementById('detailNilaiTugas').textContent = nilaiData.tugas || '0';
    document.getElementById('detailNilaiQuiz').textContent = nilaiData.quiz || '0';
    document.getElementById('detailNilaiUTS').textContent = nilaiData.uts || '0';
    document.getElementById('detailNilaiUAS').textContent = nilaiData.uas || '0';
    document.getElementById('detailNilaiKehadiran').textContent = nilaiData.kehadiran || '0';
    document.getElementById('detailNilaiPraktikum').textContent = nilaiData.praktikum || '0';
    
    // Bobot (jika ada di data, bisa ditambahkan query terpisah untuk mendapatkan bobot)
    // Untuk sekarang tampilkan placeholder
    document.getElementById('detailBobotTugas').textContent = 'Bobot: -';
    document.getElementById('detailBobotQuiz').textContent = 'Bobot: -';
    document.getElementById('detailBobotUTS').textContent = 'Bobot: -';
    document.getElementById('detailBobotUAS').textContent = 'Bobot: -';
    document.getElementById('detailBobotKehadiran').textContent = 'Bobot: -';
    document.getElementById('detailBobotPraktikum').textContent = 'Bobot: -';
    
    // Nilai Akhir
    document.getElementById('detailNilaiAkhir').textContent = nilaiData.nilai_akhir || '0';
    
    // Grade
    const gradeElement = document.getElementById('detailGrade');
    const grade = nilaiData.nilai_huruf || '-';
    gradeElement.textContent = grade;
    gradeElement.className = 'text-3xl font-bold px-4 py-2 rounded-lg ' + getGradeColorClass(grade);
    
    // Nilai Mutu
    document.getElementById('detailNilaiMutu').textContent = nilaiData.nilai_mutu || '0';
    
    // Status
    const statusElement = document.getElementById('detailStatus');
    statusElement.textContent = nilaiData.status;
    statusElement.className = 'px-4 py-2 text-sm font-semibold rounded-full ' + getStatusColorClass(nilaiData.status);
    
    // Show modal
    document.getElementById('modalDetailNilai').classList.remove('hidden');
}

function closeDetailModal() {
    document.getElementById('modalDetailNilai').classList.add('hidden');
}

function getGradeColorClass(grade) {
    const colors = {
        'A': 'text-green-800',
        'A+': 'text-green-800',
        'A-': 'text-green-800',
        'B': 'text-blue-800',
        'B+': 'text-blue-800',
        'B-': 'text-blue-800',
        'C': ' text-yellow-800',
        'D': 'text-orange-800',
        'E': 'text-red-800'
    };
    return colors[grade] || 'text-gray-800';
}

function getStatusColorClass(status) {
    const colors = {
        'Final': 'text-blue-800',
        'Draft': 'text-gray-800',
        'Lulus': ' text-green-800',
        'Tidak Lulus': 'text-red-800'
    };
    return colors[status] || 'text-gray-800';
}
</script>