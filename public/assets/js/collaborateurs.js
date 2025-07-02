document.addEventListener('DOMContentLoaded', function() {
    // ===== RECHERCHE INSTANTANÉE =====
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('collaborateursTable');
    
    if (searchInput && table) {
        searchInput.addEventListener('input', function() {
            const searchText = this.value.toLowerCase().trim();
            const rows = table.querySelectorAll('tbody tr');
            let visibleCount = 0;
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const isVisible = text.includes(searchText);
                row.style.display = isVisible ? '' : 'none';
                if (isVisible) visibleCount++;
                
                // Animation d'apparition
                if (isVisible && !row.classList.contains('table-row')) {
                    row.classList.add('table-row');
                }
            });
            
            // Mettre à jour le compteur
            const counter = document.getElementById('visibleCount');
            if (counter) counter.textContent = visibleCount;
        });
    }

    // ===== TRI DES COLONNES =====
    const sortableHeaders = document.querySelectorAll('.sortable');
    let currentSort = { column: null, direction: 'asc' };
    
    sortableHeaders.forEach(header => {
        header.addEventListener('click', function() {
            const columnIndex = Array.from(this.parentNode.children).indexOf(this);
            const rows = Array.from(table.querySelectorAll('tbody tr'));
            
            // Toggle direction
            const isSameColumn = currentSort.column === columnIndex;
            const newDirection = isSameColumn && currentSort.direction === 'asc' ? 'desc' : 'asc';
            
            rows.sort((a, b) => {
                const aText = a.children[columnIndex].textContent.trim();
                const bText = b.children[columnIndex].textContent.trim();
                
                // Essai de comparaison numérique
                if (!isNaN(aText) && !isNaN(bText)) {
                    return newDirection === 'asc' 
                        ? parseFloat(aText) - parseFloat(bText) 
                        : parseFloat(bText) - parseFloat(aText);
                }
                
                // Comparaison de texte
                return newDirection === 'asc' 
                    ? aText.localeCompare(bText) 
                    : bText.localeCompare(aText);
            });
            
            // Mettre à jour le tableau
            const tbody = table.querySelector('tbody');
            tbody.innerHTML = '';
            rows.forEach((row, index) => {
                row.classList.add('table-row');
                row.style.animationDelay = `${index * 0.05}s`;
                tbody.appendChild(row);
            });
            
            // Mettre à jour l'UI
            sortableHeaders.forEach(h => {
                const icon = h.querySelector('i');
                if (icon) icon.className = 'fas fa-sort ms-1';
            });
            
            const icon = this.querySelector('i');
            if (icon) {
                icon.className = newDirection === 'asc' 
                    ? 'fas fa-sort-up ms-1' 
                    : 'fas fa-sort-down ms-1';
            }
            
            // Sauvegarder le tri actuel
            currentSort = { column: columnIndex, direction: newDirection };
        });
    });

    // ===== TOOLTIPS =====
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // ===== ANIMATION DES STATS =====
    const statsCards = document.querySelectorAll('.stats-card');
    statsCards.forEach((card, index) => {
        setTimeout(() => {
            card.style.transform = 'translateY(0)';
            card.style.opacity = '1';
        }, 300 + index * 150);
    });

    // ===== EXPORT CSV =====
    const exportBtn = document.getElementById('exportBtn');
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            const rows = table.querySelectorAll('tbody tr:not([style*="none"])');
            let csvContent = "data:text/csv;charset=utf-8,";
            
            // En-têtes
            const headers = Array.from(table.querySelectorAll('thead th'))
                .map(th => `"${th.textContent.trim()}"`)
                .join(',');
            
            csvContent += headers + "\n";
            
            // Données
            rows.forEach(row => {
                const cols = Array.from(row.querySelectorAll('td'))
                    .map(td => {
                        let text = td.textContent.trim();
                        // Gérer les cartes
                        if (td.querySelector('.badge-card')) {
                            text = Array.from(td.querySelectorAll('.badge-card'))
                                .map(badge => badge.textContent.trim())
                                .join('; ');
                        }
                        return `"${text}"`;
                    })
                    .join(',');
                
                csvContent += cols + "\n";
            });
            
            // Téléchargement
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "collaborateurs.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    }
});