app.admin = {
  index: {
    chart1Datasets: () => {
      $.getJSON(Routing.generate("chart1Datasets"), function (response) {
        var ctx = document.getElementById("chart1");
        var myChart = new Chart(ctx, {
          type: "bar",
          data: {
            labels: response.courses,
            datasets: [
              {
                label: "Estudiantes activos por curso",
                data: response.students,
                backgroundColor: [
                  "rgba(255, 99, 132, 0.2)",
                  "rgba(54, 162, 235, 0.2)",
                  "rgba(255, 206, 86, 0.2)",
                  "rgba(75, 192, 192, 0.2)",
                  "rgba(153, 102, 255, 0.2)",
                  "rgba(255, 159, 64, 0.2)",
                ],
                borderColor: [
                  "rgba(255, 99, 132, 1)",
                  "rgba(54, 162, 235, 1)",
                  "rgba(255, 206, 86, 1)",
                  "rgba(75, 192, 192, 1)",
                  "rgba(153, 102, 255, 1)",
                  "rgba(255, 159, 64, 1)",
                ],
                borderWidth: 1,
              },
            ],
          },
          options: {
            scales: {
              y: {
                beginAtZero: true,
              },
            },
          },
        });
      });
    },
  },
};

$(() => {
    app.admin.index.chart1Datasets();
})