document.addEventListener('DOMContentLoaded', function() {
    // Navigation par jour
    const datePicker = document.getElementById('datePicker');
    const prevDayBtn = document.getElementById('prevDay');
    const nextDayBtn = document.getElementById('nextDay');
    const useTodayBtn = document.getElementById('useToday');
    
    function navigateDays(days) {
        const currentDate = new Date(datePicker.value);
        currentDate.setDate(currentDate.getDate() + days);
        datePicker.value = currentDate.toISOString().split('T')[0];
        datePicker.closest('form').submit();
    }
    
    if (prevDayBtn) prevDayBtn.addEventListener('click', () => navigateDays(-1));
    if (nextDayBtn) nextDayBtn.addEventListener('click', () => navigateDays(1));
    
    if (useTodayBtn) {
        useTodayBtn.addEventListener('click', function() {
            datePicker.value = new Date().toISOString().split('T')[0];
            datePicker.closest('form').submit();
        });
    }
    
    // Initialisation du graphique
    const hoursChart = document.getElementById('hoursChart');
    if (hoursChart) {
        const ctx = hoursChart.getContext('2d');
        const workHours = Array.from(document.querySelectorAll('[aria-valuenow]'))
            .map(el => parseFloat(el.getAttribute('aria-valuenow')))
            .filter(hours => hours > 0);
        
        const datasets = [{
            label: 'Heures travaillées',
            data: workHours,
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }];
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                datasets: datasets
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Heures'
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return `Heures travaillées: ${context.parsed.y.toFixed(1)}h`;
                            }
                        }
                    }
                }
            }
        });
    }
    
    // Tri des colonnes
    const table = document.getElementById('logsTable');
    if (table) {
        const headers = table.querySelectorAll('thead th');
        headers.forEach((header, index) => {
            header.addEventListener('click', () => {
                const rows = Array.from(table.querySelectorAll('tbody tr'));
                const isAsc = header.classList.toggle('asc');
                header.classList.toggle('desc', !isAsc);
                
                rows.sort((a, b) => {
                    const aVal = a.children[index].textContent.trim();
                    const bVal = b.children[index].textContent.trim();
                    
                    // Gestion numérique
                    if (!isNaN(aVal) && !isNaN(bVal)) {
                        return isAsc ? aVal - bVal : bVal - aVal;
                    }
                    
                    // Gestion des dates
                    if (aVal.includes(':') && bVal.includes(':')) {
                        const timeToMinutes = time => {
                            const [hours, minutes] = time.split(':').map(Number);
                            return hours * 60 + minutes;
                        };
                        return isAsc ? timeToMinutes(aVal) - timeToMinutes(bVal) : timeToMinutes(bVal) - timeToMinutes(aVal);
                    }
                    
                    // Texte par défaut
                    return isAsc 
                        ? aVal.localeCompare(bVal) 
                        : bVal.localeCompare(aVal);
                });
                
                const tbody = table.querySelector('tbody');
                tbody.innerHTML = '';
                rows.forEach(row => tbody.appendChild(row));
            });
        });
    }
});