// Fonctionnalité de tri des colonnes
document.addEventListener('DOMContentLoaded', function() {
    const getCellValue = (row, index) => row.children[index].innerText || row.children[index].textContent;

    const comparer = (index, asc) => (a, b) => {
        const valA = getCellValue(asc ? a : b, index);
        const valB = getCellValue(asc ? b : a, index);
        
        // Gestion des heures
        if (valA.includes(':') && valB.includes(':')) {
            const timeToSeconds = time => {
                const [hours, minutes, seconds] = time.split(':').map(Number);
                return hours * 3600 + minutes * 60 + seconds;
            };
            return timeToSeconds(valA) - timeToSeconds(valB);
        }
        
        // Gestion numérique
        return !isNaN(valA) && !isNaN(valB) 
            ? valA - valB 
            : valA.toString().localeCompare(valB);
    };

    document.querySelectorAll('th').forEach(th => {
        th.addEventListener('click', function() {
            const table = this.closest('table');
            const tbody = table.querySelector('tbody');
            Array.from(tbody.querySelectorAll('tr'))
                .sort(comparer(Array.from(this.parentNode.children).indexOf(this), this.asc = !this.asc))
                .forEach(tr => tbody.appendChild(tr));
        });
    });
});