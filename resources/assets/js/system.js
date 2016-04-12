$(document).ready(function () {
    var definitions = getGraphDefinintions();

    Highcharts.setOptions({
        global: {
            useUTC: false
        }
    });

    $('.system-graph.graph-cpu').highcharts(definitions.cpu);
    $('.system-graph.graph-disk').each(function (index, item) {
        $(item).highcharts(definitions.disk)
    });
    $('.system-graph.graph-memory').highcharts(definitions.memory);
    $('.system-graph.graph-swap').highcharts(definitions.swap);

    $('.system-graph.graph-netio-packets').each(function (index, item) {
        $(item).highcharts(definitions.netio_packets);
    });
    $('.system-graph.graph-netio-bytes').each(function (index, item) {
        $(item).highcharts(definitions.netio_bytes);
    });

    $(".system-graph-switch").click(function () {
        console.log($(this));

        var root = $(this).closest('.root');
        var info = $(root).find('.perfmon-info');

        var interface = $(info).data('interface');
        var type = $(info).data('type');

        var searchedType = invertType(type);

        var otherInfo =
            $(root)
                .parent()
                .find('.perfmon-info[data-interface="' + interface + '"][data-type="' + searchedType + '"]');
        var otherRoot = $(otherInfo).closest('.root');

        $(otherRoot).toggleClass('hidden');
        $(otherRoot).find('.system-graph').highcharts().reflow();
        $(root).toggleClass('hidden');
    });

    setInterval(updateCharts, 1000);
});

function invertType(type) {
    if (type == "bytes") {
        return "packets";
    }
    if (type == "packets") {
        return "bytes";
    }
}

function getShiftSeries(series) {
    return series.data.length > 60;
}

function updateCharts(count) {
    if (count === undefined) {
        count = 1;
    }

    var settings = ("#system-settings");
    var endpointUrl = $(settings).data("endpoint");
    var systemId = $(settings).data('system');

    $.ajax({
        url: endpointUrl + '/system/' + systemId + '/' + count,
        success: function (response) {
            try {
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
                            $('.system-graph.graph-netio-bytes').each(function (index, item) {
                                updateNetioBytesCharts(record.netio);
                            });
                            $('.system-graph.graph-netio-packets').each(function (index, item) {
                                updateNetioPacketsCharts(record.netio);
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
        cache: false
    });
}

function updateSystemInfos(system, firstRecord, latestRecord, totalRecordCount) {
    $('.system-hostname').text(system.hostname);
    $('.system-cpu-count').text(latestRecord.cpu_count);
    $('.system-cpu-count-physical').text(latestRecord.cpu_count_physical);
    $('.system-records-first-time').text(firstRecord.created_at);
    $('.system-records-latest-time').text(latestRecord.created_at);
    $('.system-records-latest-boot').text(latestRecord.boot_time);
    $('.system-records-total').text(totalRecordCount);
}

function updateCpuCharts(cputimes) {
    $('.system-graph.graph-cpu').each(function (index, item) {
        var chart = $(item).highcharts();
        var date = Date.parse(cputimes.created_at);

        var idle = [date.getTime(), cputimes.idle_percent * 1];
        var user = [date.getTime(), cputimes.user_percent * 1];
        var kernel = [date.getTime(), cputimes.system_percent * 1];

        if (!(equalsLastSeriesEntry(idle, chart.series[0])
            && equalsLastSeriesEntry(user, chart.series[1])
            && equalsLastSeriesEntry(kernel, chart.series[2]))) {
            chart.series[0].addPoint(idle, false, getShiftSeries(chart.series[0]));
            chart.series[1].addPoint(user, false, getShiftSeries(chart.series[1]));
            chart.series[2].addPoint(kernel, false, getShiftSeries(chart.series[2]));
            chart.redraw();
        }

        var root = $(item).closest('.root');

        $(root).find('.cpu-user-percent').text(cputimes.user_percent);
        $(root).find('.cpu-system-percent').text(cputimes.system_percent);
        $(root).find('.cpu-idle-percent').text(cputimes.idle_percent);
        $(root).find('.cpu-io-percent').text(cputimes.iowait_percent);
    });
}

function updateDiskCharts(disks) {
    $('.system-graph.graph-disk').each(function (index, item) {
        for (var i = 0; i < disks.length; ++i) {
            var disk = disks[i];

            var root = $(".perfmon-info[data-device='" + disk.device + "']").closest('.root');

            if ($(root).find('.perfmon-info').data('device') != disk.device) {
                continue;
            }

            var chart = $(item).highcharts();
            var date = Date.parse(disk.created_at);
            var free = [date.getTime(), disk.free * 1];
            var used = [date.getTime(), disk.used * 1];

            if (!(equalsLastSeriesEntry(free, chart.series[0])
                && equalsLastSeriesEntry(used, chart.series[1]))) {
                chart.series[0].addPoint(free, false, getShiftSeries(chart.series[0]));
                chart.series[1].addPoint(used, false, getShiftSeries(chart.series[1]));
                chart.redraw();
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
        var free = [date.getTime(), memory.virt_free * 1];
        var used = [date.getTime(), memory.virt_used * 1];

        if (!(equalsLastSeriesEntry(free, chart.series[0])
            && equalsLastSeriesEntry(used, chart.series[1]))) {
            chart.series[0].addPoint(free, false, getShiftSeries(chart.series[0]));
            chart.series[1].addPoint(used, false, getShiftSeries(chart.series[1]));
            chart.redraw();
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
        var free = [date.getTime(), memory.swap_free * 1];
        var used = [date.getTime(), memory.swap_used * 1];

        if (!(equalsLastSeriesEntry(free, chart.series[0])
            && equalsLastSeriesEntry(used, chart.series[1]))) {
            chart.series[0].addPoint(free, false, getShiftSeries(chart.series[0]));
            chart.series[1].addPoint(used, false, getShiftSeries(chart.series[1]));
            chart.redraw();
        }

        set($(root).find('.swap-total'), memory.swap_total);
        set($(root).find('.swap-used'), memory.swap_used);
        set($(root).find('.swap-free'), memory.swap_free);
        set($(root).find('.swap-in'), memory.swap_in);
        set($(root).find('.swap-out'), memory.swap_out);
    });
}

function updateNetioBytesCharts(netios) {
    $('.system-graph.graph-netio-bytes').each(function (index, item) {
        for (var i = 0; i < netios.length; ++i) {
            var netio = netios[i];

            var root = $(item).closest('.root');

            if ($(root).find('.perfmon-info').data('interface') != netio.interface) {
                continue;
            }

            var chart = $(item).highcharts();
            var date = Date.parse(netio.created_at);

            var bytes_recv = [date.getTime(), netio.bytes_recv_sec * 1];
            var bytes_sent = [date.getTime(), netio.bytes_sent_sec * 1];

            if (!(equalsLastSeriesEntry(bytes_recv, chart.series[0])
                && equalsLastSeriesEntry(bytes_sent, chart.series[1]))) {
                chart.series[0].addPoint(bytes_recv, false, getShiftSeries(chart.series[0]));
                chart.series[1].addPoint(bytes_sent, false, getShiftSeries(chart.series[1]));
                chart.redraw();
            }

            set($(root).find('.netio-bytes-received-second'), netio.bytes_recv_sec);
            set($(root).find('.netio-bytes-sent-second'), netio.bytes_sent_sec);
            set($(root).find('.netio-bytes-received'), netio.bytes_recv);
            set($(root).find('.netio-bytes-sent'), netio.bytes_sent);
            set($(root).find('.netio-dropin'), netio.dropin);
            set($(root).find('.netio-dropout'), netio.dropout);
            set($(root).find('.netio-errin'), netio.errin);
            set($(root).find('.netio-errout'), netio.errout);
            break;
        }
    });
}

function updateNetioPacketsCharts(netios) {
    $('.system-graph.graph-netio-packets').each(function (index, item) {
        for (var i = 0; i < netios.length; ++i) {
            var netio = netios[i];

            var root = $(item).closest('.root');

            if ($(root).find('.perfmon-info').data('interface') != netio.interface) {
                continue;
            }

            var chart = $(item).highcharts();
            var date = Date.parse(netio.created_at);

            var packets_recv = [date.getTime(), netio.packets_recv_sec * 1];
            var packets_sent = [date.getTime(), netio.packets_sent_sec * 1];

            if (!(equalsLastSeriesEntry(packets_recv, chart.series[0])
                && equalsLastSeriesEntry(packets_sent, chart.series[1]))) {
                chart.series[0].addPoint(packets_recv, false, getShiftSeries(chart.series[0]));
                chart.series[1].addPoint(packets_sent, false, getShiftSeries(chart.series[1]));
                chart.redraw();
            }

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

    $('.users-count-total').text(users.length);

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

            $(userRow).find('.users-id').text(user.id);
            $(userRow).find('.users-username').text(user.name);
            $(userRow).find('.users-hostname').text(user.host);
            $(userRow).find('.users-uptime').text(diffBetweenDates(startTime, Date.now()));
            $(userRow).find('.users-start-time').text(startTime.toLocaleString());
            $(userRow).find('.users-terminal').text(user.terminal);

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

    $(element).text(value);
}

function getLastEntryOfSeries(series) {
    return series.data[series.data.length - 1];
}

function equalsLastSeriesEntry(point, series) {
    var lastPoint = getLastEntryOfSeries(series);

    if (lastPoint == undefined) {
        return false;
    }

    return lastPoint.x === point[0]
        && lastPoint.y === point[1];
}

function formatDate(date) {
    return Highcharts.dateFormat('%A, %b %e, %Y', date);
}

function percentageFormatter() {
    var tt = '<i>' + formatDate(this.x) + '</i><br/>';

    $(this.points).each(function () {
        tt += '<br/><strong>' + this.series.name + '</strong>: ' + this.y + '%';
    });

    return tt;
}

function sizeAbsAndPercFormatter() {
    var tt = '<i>' + formatDate(this.x) + '</i><br/>';

    $(this.points).each(function () {
        var name = this.series.name;
        var value = formatBytes(this.y);
        var percentage = Math.round(this.percentage * 10) / 10;

        tt += '<br/><strong>' + name + '</strong>: ' + value + ' (' + percentage + '%)';
    });

    return tt;
}

function byteAbsFormatter() {
    var tt = '<i>' + formatDate(this.x) + '</i><br/>';

    $(this.points).each(function () {
        var name = this.series.name;
        var value = formatBytes(this.y);

        tt += '<br/><strong>' + name + '</strong>: ' + value;
    });

    return tt;
}

function sizeAbsFormatter() {
    var tt = '<i>' + formatDate(this.x) + '</i><br/>';

    $(this.points).each(function () {
        var name = this.series.name;
        var value = formatNumber(this.y);

        tt += '<br/><strong>' + name + '</strong>: ' + value;
    });

    return tt;
}

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