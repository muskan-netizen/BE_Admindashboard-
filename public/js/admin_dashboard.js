$(document).ready(function () {
    const $flatpickr = $("#range-datepicker").flatpickr({
        mode: "range",
        // locale: {
        //     firstDayOfWeek: 1,
        //     weekdays: {
        //         shorthand: ["Dom", "Seg", "Ter", "Qua", "Qui", "Sex", "Sáb"],
        //         longhand: ["Domingo","Segunda-feira","Terça-feira","Quarta-feira","Quinta-feira","Sexta-feira","Sábado"]
        //     },
        //     months: {
        //         shorthand: ["Jan","Fev","Mar","Abr","Mai","Jun","Jul","Ago","Set","Out","Nov","Dez"],
        //         longhand: ["Janeiro","Fevereiro","Março","Abril","Maio","Junho","Julho","Agosto","Setembro","Outubro","Novembro","Dezembro"]
        //     },
        // },
        onClose: function (selectedDates, dateStr, instance) {
            getDashboardData(dashboard_filter_url);
        }
    });
    $(".refresh_cataegoryinfo").click(function () {
        $flatpickr.clear();
        getDashboardData(dashboard_filter_url);
    });
    $("#dashboard_refresh_btn").click(function () {
        // $flatpickr.clear();
        getDashboardData(dashboard_filter_url);
    });
    getDashboardData(dashboard_filter_url);
    $(".yearSales").click(function () {
        $flatpickr.clear();
        getDashboardData(dashboard_filter_url, 'yearly');
        $('.weeklySales, .monthlySales').removeClass('btn-light').removeClass('btn-secondary').addClass('btn-light');
        $(this).removeClass('btn-light').removeClass('btn-secondary').addClass('btn-secondary');
    });
    $(".monthlySales").click(function () {
        $flatpickr.clear();
        getDashboardData(dashboard_filter_url, 'monthly');
        $('.yearSales, .weeklySales').removeClass('btn-light').removeClass('btn-secondary').addClass('btn-light');
        $(this).removeClass('btn-light').removeClass('btn-secondary').addClass('btn-secondary');
    });
    $(".weeklySales").click(function () {
        $flatpickr.clear();
        getDashboardData(dashboard_filter_url, 'weekly');
        $('.yearSales, .monthlySales').removeClass('btn-light').removeClass('btn-secondary').addClass('btn-light');
        $(this).removeClass('btn-light').removeClass('btn-secondary').addClass('btn-secondary');
    });
    function getDashboardData(dashboard_filter_url, type = 'yearly') {
        var date_filter = $('#range-datepicker').val();
        $.getJSON(dashboard_filter_url, { type: type, date_filter: date_filter }, function (response) {
            if (response.status == 'Success') {
                $('#apexchartsfwg700r2').html('');
                $('#total_brands').html(response.data.total_brands);
                $('#total_vendor').html(response.data.total_vendor);
                $('#total_banners').html(response.data.total_banners);
                $('#total_products').html('+ ' + response.data.total_products);
                $('#total_categories').html(response.data.total_categories);
                // orderTopcatgory(response.data.labels, response.data.series);
                $('#total_pending_order').html(response.data.total_pending_order);
                $('#total_active_order').html(response.data.total_active_order);
                $('#total_rejected_order').html(response.data.total_rejected_order);
                $('#total_delivered_order').html(response.data.total_delivered_order);
                $('#total_revenue').html('$' + response.data.total_revenue);
                $('#total_customers').html(response.data.total_customers);
                $('#total_orders').html(response.data.total_orders);
                $('#revenueCurrentWeek').html('$' + response.data.revenueCurrentWeek);
                $('#revenueLastWeek').html('$' + response.data.revenueLastWeek);
                if (response.data.customers_increase != '') {
                    $('#customers_change').html('');
                    $('#customers_change').append('<span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i>' + response.data.customers_increase + '%</span><span class="text-nowrap">Since last month</span>');
                }
                if (response.data.customers_decrease != '') {
                    $('#customers_change').html("");
                    $('#customers_change').append('<span class="text-danger me-2"><i class="mdi mdi-arrow-down-bold"></i>' + response.data.customers_decrease + '%</span><span class="text-nowrap">Since last month</span>');
                }
                if (response.data.orders_increase != '') {
                    $('#orders_change').html('');
                    $('#orders_change').append('<span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i>' + response.data.orders_increase + '%</span><span class="text-nowrap">Since last month</span>');
                }
                if (response.data.orders_decrease != '') {
                    $('#orders_change').html('');
                    $('#orders_change').append('<span class="text-danger me-2"><i class="mdi mdi-arrow-down-bold"></i>' + response.data.orders_decrease + '%</span><span class="text-nowrap">Since last month</span>');
                }
                if (response.data.revenue_increase != '') {
                    $('#revenue_change').html('');
                    $('#revenue_change').append('<span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i>' + response.data.revenue_increase + '%</span><span class="text-nowrap">Since last month</span>');
                }
                if (response.data.revenue_decrease != '') {
                    $('#revenue_change').html('');
                    $('#revenue_change').append('<span class="text-danger me-2"><i class="mdi mdi-arrow-down-bold"></i>' + response.data.revenue_decrease + '%</span><span class="text-nowrap">Since last month</span>');
                }
                if (response.data.products_increase != '') {
                    $('#products_change').html('');
                    $('#products_change').append('<span class="text-success me-2"><i class="mdi mdi-arrow-up-bold"></i>' + response.data.products_increase + '%</span><span class="text-nowrap">Since last month</span>');
                }
                if (response.data.products_decrease != '') {
                    $('#products_change').html('');
                    $('#products_change').append('<span class="text-danger me-2"><i class="mdi mdi-arrow-down-bold"></i>' + response.data.products_decrease + '%</span><span class="text-nowrap">Since last month</span>');
                }
                Worldmap(response.data.markers);
                // if (type == 'yearly') {
                //     updateSales(response.data.revenue, response.data.sales, response.data.dates, "category");
                // } else {
                //     updateSales(response.data.revenue, response.data.sales, response.data.dates, "datetime")
                // }
                updateRevenue(response.data.monthwise_revenue);
                updateRevenueLineChart(response.data.currentweek_revenue_daywise, response.data.previousweek_revenue_daywise);
                if (response.data.locationwise_revenue != '') {
                    $('#revenue_locations').html('');
                    response.data.locationwise_revenue.forEach(el => {
                        var sum = Math.round(el.sum);
                        var orderCount = response.data.currentyear_ordercount;
                        var percent = Math.round((el.addressCount / orderCount)*100);
                        $('#revenue_locations').append('<h5 class="mb-1 mt-0 fw-normal">' + el.address.city + '</h5><div class="progress-w-percent"><span class="progress-value fw-bold">$'
                            + sum + "</span><div class='progress progress-sm'><div class='progress-bar' role='progressbar' style='width:"+ percent +"%;' aria-valuenow='72' aria-valuemin='0' aria-valuemax='100'></div></div></div>");
                    });
                }
            }
        });
    }
    function Worldmap(markers) {
        $('#world-map').html("");
        var a = ["#6658dd"],
            e = $("#world-map").data("colors");
        $("#world-map").vectorMap({
            hoverColor: !1,
            hoverOpacity: 0.7,
            shape: 'square',
            map: "world_mill_en",
            backgroundColor: "transparent",
            normalizeFunction: "polynomial",
            regionStyle: { initial: { fill: "#ced4da" } },
            markerStyle: { initial: { r: 9, fill: a[0], "fill-opacity": 0.9, stroke: "#fff", "stroke-width": 7, "stroke-opacity": 0.4 }, hover: { stroke: "#fff", "fill-opacity": 1, "stroke-width": 1.5 } },
            markers: markers,
        });
    }
    // function updateSales(revenue, sales, dates, type_xaxis) {
    //     $('#sales-analytics').html("");
    //     var colors = ['#1abc9c', '#4a81d4'];
    //     var dataColors = $("#sales-analytics").data('colors');
    //     if (dataColors) {
    //         colors = dataColors.split(",");
    //     }
    //     var options = {
    //         series: [{
    //             name: Revenue_lng,
    //             type: 'column',
    //             data: revenue
    //         }, {
    //             name: Sales_lng,
    //             type: '',
    //             data: sales
    //         }],
    //         chart: {
    //             height: 378,
    //             type: 'line',
    //             offsetY: 10
    //         },
    //         stroke: {
    //             width: [2, 3]
    //         },
    //         plotOptions: {
    //             bar: {
    //                 columnWidth: '50%'
    //             }
    //         },
    //         colors: colors,
    //         dataLabels: {
    //             enabled: true,
    //             enabledOnSeries: [1]
    //         },
    //         labels: dates,
    //         xaxis: {
    //             type: type_xaxis
    //         },
    //         legend: {
    //             offsetY: 7,
    //         },
    //         grid: {
    //             padding: {
    //                 bottom: 20
    //             }
    //         },
    //         fill: {
    //             type: 'gradient',
    //             gradient: {
    //                 shade: 'light',
    //                 type: "horizontal",
    //                 shadeIntensity: 0.25,
    //                 gradientToColors: undefined,
    //                 inverseColors: true,
    //                 opacityFrom: 0.75,
    //                 opacityTo: 0.75,
    //                 stops: [0, 0, 0]
    //             },
    //         },
    //         yaxis: [{
    //             title: {
    //                 text: Net_Revenue_lng,
    //             },
    //         }, {
    //             opposite: true,
    //             title: {
    //                 text: Number_of_Sales_lng
    //             }
    //         }]
    //     };
    //     var chart = new ApexCharts(document.querySelector("#sales-analytics"), options);
    //     chart.render();
    // }
    // function orderTopcatgory(labels, series) {
    //     if (labels.length > 0 && series.length > 0) {
    //         $('#cardCollpase4').show();
    //         $('#empty_card_collpase4').hide();
    //         var options = {
    //             series: series,
    //             labels: labels,
    //             chart: {
    //                 width: 320,
    //                 type: 'donut',
    //                 offsetX: -130,
    //             },
    //             dataLabels: {
    //                 enabled: false
    //             },
    //             responsive: [{
    //                 breakpoint: 480,
    //                 options: {
    //                     chart: {
    //                         width: 300,
    //                         offsetX: -70,
    //                         offsetY: -70,

    //                     },
    //                     legend: {
    //                         position: 'bottom',
    //                         height: 150,
    //                         width: 240
    //                     }
    //                 }
    //             }],
    //             legend: {
    //                 position: 'bottom',
    //                 height: 80,
    //                 width: 306
    //             },
    //             noData: {
    //                 text: "No Data Found",
    //                 align: 'center',
    //                 verticalAlign: 'top',
    //                 offsetX: 0,
    //                 offsetY: 0,
    //                 style: {
    //                     color: "#000000",
    //                     fontSize: '14px',
    //                     fontFamily: "Helvetica"
    //                 }
    //             }
    //         };
    //         var chart1 = new ApexCharts(document.querySelector("#apexchartsfwg700r2"), options);
    //         chart1.render();
    //     } else {
    //         $('#cardCollpase4').hide();
    //         $('#empty_card_collpase4').show();
    //     }
    // }
    function getUpdateSales() {
        $.getJSON(url, function (response) {
            updateSales(response.revenue, response.sales, response.dates, "datetime")
        });
    }

    // New revenue bar chart monthly data show
    function updateRevenue(newrevenue) {
        $('#revenue-bar-chart').html("");
        var colors = ['#727cf5', '#e3eaef'];
        var dataColors = $("#revenue-bar-chart").data('colors');
        if (dataColors) {
            colors = dataColors.split(",");
        }
        var options = {
            chart: {
                height: 257,
                type: 'bar',
                stacked: !0
            },
            plotOptions: {
                bar: {
                    horizontal: !1, columnWidth: "20%"
                }
            },
            dataLabels: { enabled: !1 },
            stroke: { show: !0, width: 2, colors: ["transparent"] },
            series: [{ name: "Revenue", data: newrevenue }],
            zoom: { enabled: !1 },
            legend: { show: !1 },
            colors: colors,
            xaxis: { categories: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"], axisBorder: { show: !1 } },
            yaxis: { labels: { formatter: function (e) { return "$" + e }, offsetX: -15 } },
            fill: { opacity: 1 },
            tooltip: { y: { formatter: function (e) { return "$" + e } } },
        };
        var chart = new ApexCharts(document.querySelector("#revenue-bar-chart"), options);
        chart.render();
    }

    // New line chart current and previous week data show
    function updateRevenueLineChart(current_data, previous_data) {
        $('#revenue-line-chart').html("");
        var colors = ["#727cf5", "#0acf97", "#fa5c7c", "#ffbc00"];
        var dataColors = $("#revenue-line-chart").data('colors');
        if (dataColors) {
            colors = dataColors.split(",");
        }
        var options = {
            chart: { height: 364, type: "line", dropShadow: { enabled: !0, opacity: .2, blur: 7, left: -7, top: 7 } },
            dataLabels: { enabled: !1 },
            stroke: { curve: "smooth", width: 4 },
            series: [{ name: "Current Week", data: current_data }, { name: "Previous Week", data: previous_data }],
            colors: colors,
            zoom: { enabled: !1 },
            legend: { show: !1 },
            xaxis: {
                type: "string", categories: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat", "Sun"],
                tooltip: { enabled: !1 },
                axisBorder: { show: !1 }
            },
            yaxis: { labels: { formatter: function (e) { return "$" + e }, offsetX: -15 } },
        };
        var chart1 = new ApexCharts(document.querySelector("#revenue-line-chart"), options);
        chart1.render();
    }
});
