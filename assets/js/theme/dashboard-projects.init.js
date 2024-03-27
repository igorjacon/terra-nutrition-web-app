/*
Template Name: Velzon - Admin & Dashboard Template
Author: Themesbrand
Website: https://Themesbrand.com/
Contact: Themesbrand@gmail.com
File: Project Dashboard init js
*/


// get colors array from the string
function getChartColorsArray(chartId) {
    if (document.getElementById(chartId) !== null) {
        var colors = document.getElementById(chartId).getAttribute("data-colors");
        if (colors) {
            if (colors.indexOf(",")) {
                colors = colors.split(",");
            } else {
                colors = JSON.parse(colors);
            }
            return colors.map(function (value) {
                var newValue = value.replace(" ", "");
                if (newValue.indexOf(",") === -1) {
                    var color = getComputedStyle(document.documentElement).getPropertyValue(newValue);
                    if (color) return color;
                    else return newValue;
                } else {
                    var val = value.split(',');
                    if (val.length == 2) {
                        var rgbaColor = getComputedStyle(document.documentElement).getPropertyValue(val[0]);
                        rgbaColor = "rgba(" + rgbaColor + "," + val[1] + ")";
                        return rgbaColor;
                    } else {
                        return newValue;
                    }
                }
            });
        } else {
            console.warn('data-colors Attribute not found on:', chartId);
        }
    }
}

// Projects Overview
var linechartcustomerColors = getChartColorsArray("projects-overview-chart");
if (linechartcustomerColors) {
    let months = document.querySelector("#projects-overview-chart").dataset.months;
    let projects = document.querySelector("#projects-overview-chart").dataset.projects;
    let revenue = document.querySelector("#projects-overview-chart").dataset.revenue;
    if (projects.indexOf(',') !== -1) {
        projects = projects.split(",");
    }
    if (revenue.indexOf(',') !== -1) {
        revenue = revenue.split(",");
    }
    let projectsArray = [];
    let revenueArray = [];
    for (var i = 0; i < projects.length; i++) {
        projectsArray.push(parseInt(projects[i]));
    }
    for (var i = 0; i < revenue.length; i++) {
        revenueArray.push(parseInt(revenue[i]));
    }
    var options = {
        series: [{
            name: 'Number of Projects',
            type: 'bar',
            data: projectsArray
        }, {
            name: 'Revenue',
            type: 'area',
            data: revenueArray
        }],
        chart: {
            height: 374,
            type: 'line',
            toolbar: {
                show: false,
            }
        },
        stroke: {
            curve: 'smooth',
            dashArray: [0, 3, 0],
            width: [0, 1, 0],
        },
        fill: {
            opacity: [1, 0.1, 1]
        },
        markers: {
            size: [0, 4, 0],
            strokeWidth: 2,
            hover: {
                size: 4,
            }
        },
        xaxis: {
            categories: months.split(','),
            axisTicks: {
                show: false
            },
            axisBorder: {
                show: false
            }
        },
        grid: {
            show: true,
            xaxis: {
                lines: {
                    show: true,
                }
            },
            yaxis: {
                lines: {
                    show: false,
                }
            },
            padding: {
                top: 0,
                right: -2,
                bottom: 15,
                left: 10
            },
        },
        legend: {
            show: true,
            horizontalAlign: 'center',
            offsetX: 0,
            offsetY: -5,
            markers: {
                width: 9,
                height: 9,
                radius: 6,
            },
            itemMargin: {
                horizontal: 10,
                vertical: 0
            },
        },
        plotOptions: {
            bar: {
                columnWidth: '30%',
                barHeight: '70%'
            }
        },
        colors: linechartcustomerColors,
        tooltip: {
            shared: true,
            y: [{
                formatter: function (y) {
                    if (typeof y !== "undefined") {
                        return y.toFixed(0);
                    }
                    return y;

                }
            }, {
                formatter: function (y) {
                    if (typeof y !== "undefined") {
                        return "€" + y.toFixed(2);
                    }
                    return y;

                }
            }]
        }
    };
    var chart = new ApexCharts(document.querySelector("#projects-overview-chart"), options);
    chart.render();
}

//Radial chart data
var isApexSeriesData = {};
var isApexSeries = document.querySelectorAll("[data-chart-series]");
if (isApexSeries) {
    Array.from(isApexSeries).forEach(function (element) {
        var isApexSeriesVal = element.attributes;
        if (isApexSeriesVal["data-chart-series"]) {
            isApexSeriesData.series = isApexSeriesVal["data-chart-series"].value.toString();
            var radialbarhartoneColors = getChartColorsArray(isApexSeriesVal["id"].value.toString());
            var options = {
                series: [isApexSeriesData.series],

                chart: {
                    type: 'radialBar',
                    width: 36,
                    height: 36,
                    sparkline: {
                        enabled: true
                    }
                },
                dataLabels: {
                    enabled: false
                },
                plotOptions: {
                    radialBar: {
                        hollow: {
                            margin: 0,
                            size: '50%'
                        },
                        track: {
                            margin: 1
                        },
                        dataLabels: {
                            show: false
                        }
                    }
                },
                colors: radialbarhartoneColors
            };

            var chart = new ApexCharts(document.querySelector("#" + isApexSeriesVal["id"].value.toString()), options);
            chart.render();

        }
    })
}

// Project Status charts
var donutchartProjectsStatusColors = getChartColorsArray("project-status");
if (donutchartProjectsStatusColors) {
    let statusDataSet = document.querySelector("#project-status").dataset.status;
    let projectDataSet = document.querySelector("#project-status").dataset.projects;
    if (statusDataSet.indexOf(',') !== -1) {
        statusDataSet = statusDataSet.split(",");
    }
    if (projectDataSet.indexOf(',') !== -1) {
        projectDataSet = projectDataSet.split(",");
    }
    let numberArray = [];
    for (var i = 0; i < projectDataSet.length; i++) {
        numberArray.push(parseInt(projectDataSet[i]));
    }
    var options = {
        series: numberArray,
        labels: statusDataSet,
        chart: {
            type: "donut",
            height: 230,
        },
        plotOptions: {
            pie: {
                size: 100,
                offsetX: 0,
                offsetY: 0,
                donut: {
                    size: "90%",
                    labels: {
                        show: false,
                    }
                },
            },
        },
        dataLabels: {
            enabled: false,
        },
        legend: {
            show: false,
        },
        stroke: {
            lineCap: "round",
            width: 0
        },
        colors: donutchartProjectsStatusColors,
    };
    var chart = new ApexCharts(document.querySelector("#project-status"), options);
    chart.render();
}