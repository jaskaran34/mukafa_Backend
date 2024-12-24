import ApexCharts from 'apexcharts';

/**
 * Creates a double bar chart with an optional third data series in the tooltip.
 *
 * @param {string} elementId - The ID of the HTML element where the chart will be rendered.
 */
function createDoubleBarChart(elementId) {
    const chartElement = document.getElementById(elementId);

    if (!chartElement) {
        return;
    }

    const colors = JSON.parse(chartElement.dataset.colors);
    const labels = JSON.parse(chartElement.dataset.labels);
    const data1 = JSON.parse(chartElement.dataset.values1);
    const data2 = JSON.parse(chartElement.dataset.values2);
    const data3 = JSON.parse(chartElement.dataset.values3 || '[]');

    const options = {
        colors,
        series: [
            {
                name: chartElement.dataset.label1,
                data: data1.map((value, index) => ({ x: labels[index], y: value })),
            },
            {
                name: chartElement.dataset.label2,
                data: data2.map((value, index) => ({ x: labels[index], y: value })),
            },
        ],
        chart: {
            type: 'bar',
            height: '320px',
            fontFamily: 'Inter, sans-serif',
            toolbar: {
                show: false,
            },
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '70%',
                borderRadiusApplication: 'end',
                borderRadius: 8,
            },
        },
        tooltip: {
            shared: true,
            intersect: false,
            style: {
                fontFamily: 'Inter, sans-serif',
            },
            y: {
                formatter: function (value, { seriesIndex, dataPointIndex, w }) {
                    if (seriesIndex === 1 && data3[dataPointIndex]) {
                        return value + ' (' + data3[dataPointIndex] + ' ' + chartElement.dataset.tooltip3 + ')';
                    }
                    return value;
                }
            }
        },
        states: {
            hover: {
                filter: {
                    type: 'darken',
                    value: 1,
                },
            },
        },
        stroke: {
            show: true,
            width: 0,
            colors: ['transparent'],
        },
        grid: {
            show: false,
            strokeDashArray: 4,
            padding: {
                left: 2,
                right: 2,
                top: -14
            },
        },
        dataLabels: {
            enabled: false,
        },
        legend: {
            show: false,
        },
        xaxis: {
            floating: false,
            labels: {
                show: true,
                style: {
                    fontFamily: 'Inter, sans-serif',
                    cssClass: 'text-xs font-normal fill-gray-500 dark:fill-gray-400'
                }
            },
            axisBorder: {
                show: false,
            },
            axisTicks: {
                show: false,
            },
        },
        yaxis: {
            labels: {
                formatter: function (val) {
                    return val.toFixed(0);
                }
            },
            show: false,
        },
        fill: {
            opacity: 1,
        },
    };

    const chart = new ApexCharts(chartElement, options);
    chart.render();
}

// Create the analytics-views chart
createDoubleBarChart('analytics-views-chart');

// Create the analytics-interactions chart
createDoubleBarChart('analytics-interactions-chart');
