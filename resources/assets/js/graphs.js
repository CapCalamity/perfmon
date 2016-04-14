function getGraphDefinintions() {
    return {
        cpu: {
            chart: {
                renderTo: 'container',
                type: 'area'
            },
            title: {
                text: ''
            },
            xAxis: {
                tickPlacement: 'on',
                title: {
                    text: 'Date'
                },
                type: 'datetime'
            },
            yAxis: {
                title: {
                    text: 'CPU Usage (%)'
                },
                min: 0,
                max: 100
            },
            tooltip: {
                shared: true,
                formatter: percentageFormatter
            },
            plotOptions: {
                area: {
                    stacking: 'percent',
                    lineColor: '#ffffff',
                    lineWidth: 1,
                    marker: {
                        enabled: false
                    }
                }
            },
            series: [{
                name: 'Idle',
                data: []
            }, {
                name: 'User',
                data: []
            }, {
                name: 'Kernel',
                data: []
            }]
        },
        disk: {
            chart: {
                renderTo: 'container',
                type: 'area'
            },
            title: {
                text: ''
            },
            xAxis: {
                tickPlacement: 'on',
                title: {
                    text: 'Date'
                },
                type: 'datetime'
            },
            yAxis: {
                title: {
                    text: 'Disk Usage (%)'
                },
                min: 0,
                max: 100
            },
            tooltip: {
                shared: true,
                formatter: sizeAbsAndPercFormatter
            },
            plotOptions: {
                area: {
                    stacking: 'percent',
                    lineColor: '#ffffff',
                    lineWidth: 1,
                    marker: {
                        enabled: false
                    }
                }
            },
            series: [{
                name: 'Free',
                data: []
            }, {
                name: 'Used',
                data: []
            }]
        },
        memory: {
            chart: {
                renderTo: 'container',
                type: 'area'
            },
            title: {
                text: ''
            },
            xAxis: {
                tickPlacement: 'on',
                title: {
                    text: 'Date'
                },
                type: 'datetime'
            },
            yAxis: {
                title: {
                    text: 'Memory Usage (%)'
                },
                min: 0,
                max: 100
            },
            tooltip: {
                shared: true,
                formatter: sizeAbsAndPercFormatter
            },
            plotOptions: {
                area: {
                    stacking: 'percent',
                    lineColor: '#ffffff',
                    lineWidth: 1,
                    marker: {
                        enabled: false
                    }
                }
            },
            series: [{
                name: 'Free',
                data: []
            }, {
                name: 'Used',
                data: []
            }]
        },
        swap: {
            chart: {
                renderTo: 'container',
                type: 'area'
            },
            title: {
                text: ''
            },
            xAxis: {
                tickPlacement: 'on',
                title: {
                    text: 'Date'
                },
                type: 'datetime'
            },
            yAxis: {
                title: {
                    text: 'Swap Usage (%)'
                },
                min: 0,
                max: 100
            },
            tooltip: {
                shared: true,
                formatter: sizeAbsAndPercFormatter
            },
            plotOptions: {
                area: {
                    stacking: 'percent',
                    lineColor: '#ffffff',
                    lineWidth: 1,
                    marker: {
                        enabled: false
                    }
                }
            },
            series: [{
                name: 'Free',
                data: []
            }, {
                name: 'Used',
                data: []
            }]
        },
        netio_bytes: {
            title: {
                text: ''
            },
            chart: {
                renderTo: 'container',
                type: 'spline'
            },
            xAxis: {
                type: 'datetime'
            },
            yAxis: {
                plotLines: [{
                    value: 0,
                    width: 1
                }]
            },
            tooltip: {
                shared: true,
                formatter: byteAbsFormatter
            },
            plotOptions: {
                spline: {
                    marker: {
                        enabled: false
                    }
                }
            },
            series: [{
                name: 'Bytes Received',
                data: []
            }, {
                name: 'Bytes Sent',
                data: []
            }]
        },
        netio_packets: {
            title: {
                text: ''
            },
            chart: {
                renderTo: 'container',
                type: 'spline'
            },
            xAxis: {
                type: 'datetime'
            },
            yAxis: {
                plotLines: [{
                    value: 0,
                    width: 1
                }]
            },
            tooltip: {
                shared: true,
                formatter: sizeAbsFormatter
            },
            plotOptions: {
                spline: {
                    marker: {
                        enabled: false
                    }
                }
            },
            series: [{
                name: 'Packets Received',
                data: []
            }, {
                name: 'Packets Sent',
                data: []
            }]
        },
        user: {}
    };
}
