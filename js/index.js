/* statistiche */
function getStats() {
    const messageId = "getStats";
    return fetch('../php/gestione_DB.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json; charset=utf-8' },
        body: JSON.stringify({
            "messageId": messageId,
            "user": getCookie("username")
        })
    })
    .then(response => {
        return response.json().then(json => {
            if (!response.ok) {
                throw new Error("Errore: " + json["esito"] + " Dettagli: " + JSON.stringify(json));
            }
            if(json["results"] == -1){ return null; }
            setStatsDetails(json["attivita"]);
            setStatsDate(json["results"][0]["data_inizio"], json["results"][0]["data_fine"]);
            return json["results"] || null; 
        });
    })
    .catch(error => {
        window.alert(error.message);
        return null;
    });
}

document.addEventListener("DOMContentLoaded", () => {
    getStats().then(stats => {
        if (stats === null) {
            console.log("stats is null");
            document.getElementById("statistiche").innerHTML = '<p>Completa almeno un allenamento per ottenere le statistiche</p>';
            document.getElementById("dettagli").remove();
            document.getElementById("settimana").remove();
            document.querySelector(".statistiche").style.display = 'flex';
            document.querySelector(".statistiche").style.justifyContent = 'center';
            return;
        }
        google.charts.load('current', { 'packages': ['corechart'] });
        google.charts.setOnLoadCallback(() => {
            drawChart(stats);
        });
    });
});

function drawChart(stats) {
    const data = new google.visualization.DataTable();
    data.addColumn('string', 'Data');
    data.addColumn('number', 'Carico Totale Giornaliero');

    stats.forEach(point => {
        data.addRow([point.data, parseFloat(point.carico_totale_giornaliero)]);
    });

    const options = {
        title: '',
        hAxis: { title: '' },
        vAxis: { title: 'Carichi Tot' },
        'backgroundColor': 'rgb(238, 240, 246)',
        legend: 'none'
    };

    const chart = new google.visualization.LineChart(document.getElementById('statistiche'));
    chart.draw(data, options);
    console.log("Grafico creato");
}

function setStatsDetails(details){
    document.getElementById("n-activities").innerText = details;
}
function setStatsDate(dataInizio, dataFine){
    document.getElementById("dataInizio").innerText = dataInizio;
    document.getElementById("dataFine").innerText = dataFine;
}







/* responsive layout */
document.addEventListener("DOMContentLoaded", () => {
    if(window.innerWidth <= 768){
        document.getElementById("content").style.marginTop = document.getElementById("sidebar-logo").clientHeight + "px";
        document.getElementById("content").style.paddingBottom = document.getElementById("sidebar-content").clientHeight + "px";
    }
});