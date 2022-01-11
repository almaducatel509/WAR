var ctx = document.querySelector('#myChart').getContext('2d');
if(ctx != null){
    const myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Etudiant', 'Utilisateur', 'Professeur', 'filiere', 'Cours'],
            datasets: [{
                label: 'nombre',
                data: [stats.etudiant, stats.utilisateur, stats.professeur, stats.filiere, stats.cours],
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}
