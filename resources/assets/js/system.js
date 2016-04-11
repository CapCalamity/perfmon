$(document).ready(function () {
    var definitions = getGraphDefinintions();

    var initialFetchCount = 1;

    $('.system-graph.graph-cpu').highcharts(definitions.cpu);
    $('.system-graph.graph-disk').each(function (index, item) {
        $(item).highcharts(definitions.disk)
    });
    $('.system-graph.graph-memory').highcharts(definitions.memory);
    $('.system-graph.graph-swap').highcharts(definitions.swap);
    $('.system-graph.graph-netio').each(function (index, item) {
        $(item).highcharts(definitions.netio)
    });

    updateCharts(initialFetchCount);
});

function getShiftSeries(series) {
    return series.data.length > 60;
}

function updateCharts(count) {
    if (count === undefined)
        count = 1;

    var settings = ("#system-settings");
    var endpointUrl = $(settings).data("endpoint");
    var systemId = $(settings).data('system');

    $.ajax({
        url: endpointUrl + '/system/' + systemId + '/' + count,
        success: function (response) {
            try {
                response = JSON.parse(response);
                if (response.return) {
                    var system = response.data.system;
                    var firstRecord = response.data.firstRecord;
                    var latestRecord = system.records[system.records.length - 1];
                    var recordCountTotal = response.data.recordCount;

                    updateSystemInfos(system, firstRecord, latestRecord, recordCountTotal);

                    for (var i = 0; i < system.records.length; ++i) {
                        var record = system.records[i];
                        var date = Date.parse(record.created_at);

                        if (record.cputimes) {
                            $(".system-graph.graph-cpu").each(function (index, item) {
                                updateCpuCharts(record.cputimes);
                            });
                        }

                        if (record.disks) {
                            $(".system-graph.graph-disk").each(function (index, item) {
                                updateDiskCharts(record.disks);
                            });
                        }

                        if (record.memory) {
                            $('.system-graph.graph-memory').each(function (index, item) {
                                updateMemoryCharts(record.memory);
                            });

                            $('.system-graph.graph-swap').each(function (index, item) {
                                updateSwapCharts(record.memory);
                            });
                        }

                        if (record.netio) {
                            $('.system-graph.graph-netio').each(function (index, item) {
                                updateNetioCharts(record.netio);
                            });
                        }

                        if (record.users) {
                            updateUserStats(record.users);
                        }
                    }
                }
            } catch (exception) {
                console.error(exception);
            }
        },
        complete: function (request, status) {
            setTimeout(function () {
                updateCharts();
            }, 1000);
        },
        cache: false
    });
}

function updateSystemInfos(system, firstRecord, latestRecord, totalRecordCount) {
    $('.system-hostname').html(system.hostname);
    $('.system-cpu-count').html(latestRecord.cpu_count);
    $('.system-cpu-count-physical').html(latestRecord.cpu_count_physical);
    $('.system-records-first-time').html(firstRecord.created_at);
    $('.system-records-latest-time').html(latestRecord.created_at);
    $('.system-records-latest-boot').html(latestRecord.boot_time);
    $('.system-records-total').html(totalRecordCount);
}

function updateCpuCharts(cputimes) {
    $('.system-graph.graph-cpu').each(function (index, item) {
        var chart = $(item).highcharts();
        var date = Date.parse(cputimes.created_at);

        var idle = [date, cputimes.idle_percent * 1];
        var user = [date, cputimes.user_percent * 1];
        var kernel = [date, cputimes.system_percent * 1];

        if (!(equalsLastSeriesEntry(idle, chart.series[0])
            && equalsLastSeriesEntry(user, chart.series[1])
            && equalsLastSeriesEntry(kernel, chart.series[2]))) {
            chart.series[0].addPoint(idle, true, getShiftSeries(chart.series[0]));
            chart.series[1].addPoint(user, true, getShiftSeries(chart.series[1]));
            chart.series[2].addPoint(kernel, true, getShiftSeries(chart.series[2]));
        }

        var root = $(item).closest('.root');

        $(root).find('.cpu-user-percent').html(cputimes.user_percent);
        $(root).find('.cpu-system-percent').html(cputimes.system_percent);
        $(root).find('.cpu-idle-percent').html(cputimes.idle_percent);
        $(root).find('.cpu-io-percent').html(cputimes.iowait_percent);
    });
}

function updateDiskCharts(disks) {
    $('.system-graph.graph-disk').each(function (index, item) {
        for (var i = 0; i < disks.length; ++i) {
            var disk = disks[i];

            var root = $(".perfmon-info[data-device='" + disk.device + "']").closest('.root');

            if ($(root).find('.perfmon-info').data('device') != disk.device)
                continue;

            var chart = $(item).highcharts();
            var date = Date.parse(disk.created_at);
            var free = [date, disk.free * 1];
            var used = [date, disk.used * 1];

            if (!(equalsLastSeriesEntry(free, chart.series[0])
                && equalsLastSeriesEntry(used, chart.series[1]))) {
                chart.series[0].addPoint(free, true, getShiftSeries(chart.series[0]));
                chart.series[1].addPoint(used, true, getShiftSeries(chart.series[1]));
            }

            set($(root).find('.disk-device'), disk.device);
            set($(root).find('.disk-mountpoint'), disk.mountpoint);
            set($(root).find('.disk-fstype'), disk.fstype);
            set($(root).find('.disk-opts'), disk.opts);
            set($(root).find('.disk-total'), disk.total);
            set($(root).find('.disk-free'), disk.free);
            set($(root).find('.disk-used'), disk.used);
            set($(root).find('.disk-used-percent'), disk.used_percent);
            break;
        }
    });
}

function updateMemoryCharts(memory) {
    $('.system-graph.graph-memory').each(function (index, item) {
        var root = $(item).closest('.root');

        var chart = $(item).highcharts();
        var date = Date.parse(memory.created_at);
        var free = [date, memory.virt_free * 1];
        var used = [date, memory.virt_used * 1];

        if (!(equalsLastSeriesEntry(free, chart.series[0])
            && equalsLastSeriesEntry(used, chart.series[1]))) {
            chart.series[0].addPoint(free, true, getShiftSeries(chart.series[0]));
            chart.series[1].addPoint(used, true, getShiftSeries(chart.series[1]));
        }

        set($(root).find('.memory-total'), memory.virt_total);
        set($(root).find('.memory-used'), memory.virt_used);
        set($(root).find('.memory-free'), memory.virt_free);
        set($(root).find('.memory-inactive'), memory.virt_inactive);
        set($(root).find('.memory-cached'), memory.virt_cached);
        set($(root).find('.memory-buffers'), memory.virt_buffers);
    });
}

function updateSwapCharts(memory) {
    $('.system-graph.graph-swap').each(function (index, item) {
        var root = $(item).closest('.root');

        var chart = $(item).highcharts();
        var date = Date.parse(memory.created_at);
        var free = [date, memory.swap_free * 1];
        var used = [date, memory.swap_used * 1];

        if (!(equalsLastSeriesEntry(free, chart.series[0])
            && equalsLastSeriesEntry(used, chart.series[1]))) {
            chart.series[0].addPoint(free, true, getShiftSeries(chart.series[0]));
            chart.series[1].addPoint(used, true, getShiftSeries(chart.series[1]));
        }

        set($(root).find('.swap-total'), memory.swap_total);
        set($(root).find('.swap-used'), memory.swap_used);
        set($(root).find('.swap-free'), memory.swap_free);
        set($(root).find('.swap-in'), memory.swap_in);
        set($(root).find('.swap-out'), memory.swap_out);
    });
}

function updateNetioCharts(netios) {
    $('.system-graph.graph-netio').each(function (index, item) {
        for (var i = 0; i < netios.length; ++i) {
            var netio = netios[i];

            var root = $(item).closest('.root');

            if ($(root).find('.perfmon-info').data('interface') != netio.interface)
                continue;

            var chart = $(item).highcharts();
            var date = Date.parse(netio.created_at);

            var bytes_recv = [date, netio.bytes_recv_sec * 1];
            var bytes_sent = [date, netio.bytes_sent_sec * 1];
            var packets_recv = [date, netio.packets_recv_sec * 1];
            var packets_sent = [date, netio.packets_sent_sec * 1];

            if (!(equalsLastSeriesEntry(bytes_recv, chart.series[0])
                && equalsLastSeriesEntry(bytes_sent, chart.series[1])
                && equalsLastSeriesEntry(packets_recv, chart.series[2])
                && equalsLastSeriesEntry(packets_sent, chart.series[3]))) {
                chart.series[0].addPoint(bytes_recv, true, getShiftSeries(chart.series[0]));
                chart.series[1].addPoint(bytes_sent, true, getShiftSeries(chart.series[1]));
                chart.series[2].addPoint(packets_recv, true, getShiftSeries(chart.series[2]));
                chart.series[3].addPoint(packets_sent, true, getShiftSeries(chart.series[3]));
            }

            set($(root).find('.netio-bytes-received-second'), netio.bytes_recv_sec);
            set($(root).find('.netio-bytes-sent-second'), netio.bytes_sent_sec);
            set($(root).find('.netio-bytes-received'), netio.bytes_recv);
            set($(root).find('.netio-bytes-sent'), netio.bytes_sent);
            set($(root).find('.netio-packets-received-second'), netio.packets_recv_sec);
            set($(root).find('.netio-packets-sent-second'), netio.packets_sent_sec);
            set($(root).find('.netio-packets-received'), netio.packets_recv);
            set($(root).find('.netio-packets-sent'), netio.packets_sent);
            set($(root).find('.netio-dropin'), netio.dropin);
            set($(root).find('.netio-dropout'), netio.dropout);
            set($(root).find('.netio-errin'), netio.errin);
            set($(root).find('.netio-errout'), netio.errout);
            break;
        }
    });
}

function updateUserStats(users) {


    $('.users-count-total').html(users.length);

    $('.users-table').each(function (index, table) {
        $(table).find('tbody').find('tr').detach();

        var baseRow = $('<tr></tr>');
        $(table).find('thead').find('th').each(function (headIndex, head) {
            var availableClasses = [
                'users-id',
                'users-username',
                'users-hostname',
                'users-uptime',
                'users-start-time',
                'users-terminal'
            ];

            var cell = $('<td></td>');
            for (var classIndex = 0; classIndex < availableClasses.length; ++classIndex) {
                if ($(head).hasClass(availableClasses[classIndex])) {
                    $(cell).addClass(availableClasses[classIndex]);
                }
            }
            $(baseRow).append($(cell));
        });

        for (var i = 0; i < users.length; ++i) {
            var user = users[i];

            var userRow = $(baseRow).clone();

            var startTime = new Date(user.start_time * 1000);

            $(userRow).find('.users-id').html(user.id);
            $(userRow).find('.users-username').html(user.name);
            $(userRow).find('.users-hostname').html(user.host);
            $(userRow).find('.users-uptime').html(diffBetweenDates(startTime, Date.now()));
            $(userRow).find('.users-start-time').html(startTime.toLocaleString());
            $(userRow).find('.users-terminal').html(user.terminal);

            $(table).find('tbody').append(userRow);
        }
    });
}

function diffBetweenDates(date1, date2) {
    var diff = new Date(date2 - date1);

    return diff.getHours() + ':' + diff.getMinutes() + ':' + diff.getSeconds();
}

function formatBytes(size) {
    var suffixIndex = 0;
    var suffixes = ['B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB'];

    while (size > 0 && suffixIndex < suffixes.length && size % 1024 !== size) {
        size = size / 1024;
        ++suffixIndex;
    }

    return (Math.round(size * 100) / 100) + ' ' + suffixes[suffixIndex];
}

function formatNumber(number) {
    if (number < 1000) {
        return number;
    }

    var suffixIndex = 0;
    var suffixes = ['', 'k', 'M', 'G', 'T', 'P', 'E'];

    while (number > 0 && suffixIndex < suffixes.length && number % 1000 !== number) {
        number = number / 1000;
        ++suffixIndex;
    }

    return (Math.round(number * 100) / 100) + ' ' + suffixes[suffixIndex];
}

function set(element, value) {
    if ($(element).hasClass('size-b')) {
        value = formatBytes(value);
    } else if ($(element).hasClass('size')) {
        value = formatNumber(value);
    }

    $(element).html(value);
}

function getLastEntryOfSeries(series) {
    return series.data[series.data.length - 1];
}

function equalsLastSeriesEntry(point, series) {
    var lastPoint = getLastEntryOfSeries(series);

    if (lastPoint == undefined)
        return false;

    return lastPoint.x === point[0]
        && lastPoint.y === point[1];
}

function getGraphDefinintions() {
    return {
        cpu: {
            chart: {
                animation: false,
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
                shared: true
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
                animation: false,
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
                shared: true
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
                animation: false,
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
                shared: true
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
                animation: false,
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
                shared: true
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
        netio: {
            title: {
                text: ''
            },
            chart: {
                animation: false,
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
            }, {
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