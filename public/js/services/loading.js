// ==================== LOADING TEMPLATE COMPONENT ====================
// File: loading-template.js

/**
 * Template loading untuk tabel
 * @param {number} rows - Jumlah baris skeleton
 * @param {number} cols - Jumlah kolom
 * @returns {string} HTML string untuk skeleton loading
 */
function getTableSkeletonLoading(rows = 5, cols = 9) {
    let skeletonRows = '';
    
    for (let i = 0; i < rows; i++) {
        let skeletonCols = '';
        for (let j = 0; j < cols; j++) {
            skeletonCols += `
                <td class="px-6 py-4">
                    <div class="animate-pulse">
                        <div class="h-4 bg-gray-200 rounded w-${j === 0 ? 'full' : '3/4'}"></div>
                        ${j === 0 || j === 1 ? '<div class="h-3 bg-gray-200 rounded w-1/2 mt-2"></div>' : ''}
                    </div>
                </td>
            `;
        }
        
        skeletonRows += `
            <tr class="border-b border-gray-200">
                ${skeletonCols}
            </tr>
        `;
    }
    
    return skeletonRows;
}

/**
 * Template loading untuk dropdown/select
 * @returns {string} HTML string untuk loading option
 */
function getSelectLoadingOption() {
    return '<option value="">Loading...</option>';
}

/**
 * Template loading untuk card/container
 * @param {number} items - Jumlah item skeleton
 * @returns {string} HTML string untuk skeleton cards
 */
function getCardSkeletonLoading(items = 3) {
    let skeletons = '';
    
    for (let i = 0; i < items; i++) {
        skeletons += `
            <div class="animate-pulse bg-white p-6 rounded-lg shadow">
                <div class="h-4 bg-gray-200 rounded w-3/4 mb-4"></div>
                <div class="h-4 bg-gray-200 rounded w-1/2 mb-4"></div>
                <div class="h-4 bg-gray-200 rounded w-full"></div>
            </div>
        `;
    }
    
    return skeletons;
}

/**
 * Tampilkan loading di tabel
 * @param {string} tableId - ID dari tbody element
 * @param {number} rows - Jumlah baris skeleton
 * @param {number} cols - Jumlah kolom
 */
function showTableLoading(tableId, rows = 5, cols = 9) {
    const tbody = document.getElementById(tableId);
    if (tbody) {
        tbody.innerHTML = getTableSkeletonLoading(rows, cols);
    }
}

/**
 * Template untuk empty state
 * @param {string} message - Pesan yang ditampilkan
 * @param {number} colspan - Jumlah colspan
 * @returns {string} HTML string untuk empty state
 */
function getEmptyState(message = 'Tidak ada data', colspan = 9) {
    return `
        <tr>
            <td colspan="${colspan}" class="text-center py-8">
                <div class="flex flex-col items-center justify-center">
                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-gray-500 text-lg">${message}</p>
                </div>
            </td>
        </tr>
    `;
}

/**
 * Template untuk error state
 * @param {string} message - Pesan error
 * @param {number} colspan - Jumlah colspan
 * @returns {string} HTML string untuk error state
 */
function getErrorState(message = 'Terjadi kesalahan', colspan = 9) {
    return `
        <tr>
            <td colspan="${colspan}" class="text-center py-8">
                <div class="flex flex-col items-center justify-center">
                    <svg class="w-16 h-16 text-red-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-red-500 text-lg">${message}</p>
                </div>
            </td>
        </tr>
    `;
}

/**
 * Spinner loading inline
 * @returns {string} HTML string untuk spinner
 */
function getInlineSpinner() {
    return `
        <div class="inline-flex items-center">
            <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="ml-2">Loading...</span>
        </div>
    `;
}

// Export functions jika menggunakan module
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        getTableSkeletonLoading,
        getSelectLoadingOption,
        getCardSkeletonLoading,
        showTableLoading,
        getEmptyState,
        getErrorState,
        getInlineSpinner
    };
}