$(document).ready(function(){
    let ctx = document.getElementById('myChart');
    let label = [];
    let data = [];
    let id = $('h1').attr('name');
    let url = '/sondage/' + id + '/graphique';
    axios.post(url).then(
        function (response) {
            let tab = response.data.message;
            for(let i = 0; i < tab.length; i++){
                label.push(tab[i]['response']);
                data.push(tab[i][1]);
            }
            let myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: label,
                    datasets: [{
                        data: data,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.5)',
                            'rgba(54, 162, 235, 0.5)',
                            'rgba(255, 206, 86, 0.5)',
                            'rgba(75, 192, 192, 0.5)',
                            'rgba(153, 102, 255, 0.5)',
                            'rgba(255, 159, 64, 0.5)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
            });
        }
    );
});
