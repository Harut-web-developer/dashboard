$(document).ready(function (){
    $('.store').change(function (){
        let store = $(this).val();
        let category = $('.category').val();
        let start = $('.start_date').val();
        let end = $('.end_date').val();
        let pay = $('.pay').val();
        workChart(store,category,start,end,pay);
    })
    $('.category').change(function (){
        let store = $('.store').val();
        let category = $(this).val();
        let start = $('.start_date').val();
        let end = $('.end_date').val();
        let pay = $('.pay').val();
        workChart(store,category,start,end,pay);
    })
    $('.start_date').change(function (){
        let store = $('.store').val();
        let category = $('.category').val();
        let start = $(this).val();
        let end = $('.end_date').val();
        let pay = $('.pay').val();
        workChart(store,category,start,end,pay);
    })
    $('.end_date').change(function (){
        let store = $('.store').val();
        let category = $('.category').val();
        let start = $('.start_date').val();
        let end = $(this).val();
        let pay = $('.pay').val();
        workChart(store,category,start,end,pay);
    })
    $('.pay').change(function (){
        let store = $('.store').val();
        let category = $('.category').val();
        let start = $('.start_date').val();
        let end = $('.end_date').val();
        let pay = $(this).val();
        workChart(store,category,start,end,pay);
    })
        $(window).on('load', function (){
            let start = $('.start_date').val();
            let end = $('.end_date').val();

            workChart(0,0,start,end,0);
        })

    function  workChart(store,category,start,end,pay){
        var title = [];
        var revenue = [];
        var paymentPrice = [];
        var target_price = [];
        var sellsPrice = [];
        var datatable2 = [];
        console.log(datatable2)
        $.ajax({
            url:'/chart/get-data',
            method:'post',
            datatype:'json',
            data:{
                start:start,
                end:end,
                store:store,
                category:category,
                pay:pay
            },
            async: false,
        }).done(function(data){
            let parse = JSON.parse(data, true);
            // console.log(data)
                if(parse.msg === 'danger'){
                    alert('There are no dates with such dates');



                    $('.productName').text(parse.maxPrice.maxPrice);
                    $('.productPrice').text(parse.maxPrice.name);
                    if (parse.maxPrice.img === null){
                        $('.productName').text('0');
                        $('.prod').html('');
                    }else {
                        $('.prod').html('<img class="productImg myimg" src="/uploads/' + parse.maxPrice.img + '">');
                    }
                    $('.maxCountProductName').text(parse.maxCount.maxCount);
                    $('.productMaxCount').text(parse.maxCount.name);
                    if (parse.maxCount.img === null){
                        $('.maxCountProductName').text('0');
                        $('.prodCount').html('');
                    }else{
                        $('.prodCount').html('<img class="productCountImg myimg" src="/uploads/' + parse.maxCount.img + '">');
                    }
                    $('.orderProcent').text(parse.overageProcent + '%');
                    $('.procentBar').attr('style', 'width:' + parse.overageProcent + '%');
                    $('.ordersCount').text(parse.ordersCount);

                    // location.reload();
                }else if(parse.msg === 'warning'){
                    alert('Dates are reveresed');
                    // location.reload();
                }
                // else if(parse.msg === 'error'){
                //     alert('There is no product in the selected category');
                //     // location.reload();
                // }
                else {
                    $('.productName').text(parse.maxPrice.maxPrice);
                    $('.productPrice').text(parse.maxPrice.name);
                    if (parse.maxPrice.img === null){
                        $('.productName').text('0');
                        $('.prod').html('');
                    }else {
                        $('.prod').html('<img class="productImg myimg" src="/uploads/' + parse.maxPrice.img + '">');
                    }
                    $('.maxCountProductName').text(parse.maxCount.maxCount);
                    $('.productMaxCount').text(parse.maxCount.name);
                    if (parse.maxCount.img === null){
                        $('.maxCountProductName').text('0');
                        $('.prodCount').html('');
                    }else{
                        $('.prodCount').html('<img class="productCountImg myimg" src="/uploads/' + parse.maxCount.img + '">');
                    }
                    $('.orderProcent').text(parse.overageProcent + '%');
                    $('.procentBar').attr('style', 'width:' + parse.overageProcent + '%');
                    $('.ordersCount').text(parse.ordersCount);
                    if (parse.ordersTotalPrice.total === null){
                        datatable2[0] = 0;
                        datatable2[1] = 0;
                        datatable2[2] = 0;
                        $('.chart-pie').html('');
                        $('.chart-pie').html('<canvas id="myPieChart" width="447" height="306" style="display: block; height: 245px; width: 358px;" class="chartjs-render-monitor"></canvas>');
                    }else {
                        datatable2[0] = parseInt(parse.ordersTotalPrice.total);
                        datatable2[1] = parseInt(parse.maxCount.revenue);
                        datatable2[2] = parse.maxCount.target_price;
                        $('.chart-pie').html('<canvas id="myPieChart" width="447" height="306" style="display: block; height: 245px; width: 358px;" class="chartjs-render-monitor"></canvas>');
                    }
                    title = parse.label;
                    revenue = parse.revenue;
                    sellsPrice = parse.price;
                    paymentPrice =parse.paymentPrice;
                    target_price = parse.target_price;
                }
            })

        // Pie Chart Example
        var option = {
            type: 'doughnut',
            data: {
                labels: ['sells','revenue','target revenue'],
                datasets: [{
                    data: datatable2,
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
                    hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
                    hoverBorderColor: "rgba(234, 236, 244, 1)",
                }],
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    backgroundColor: "rgb(255,255,255)",
                    bodyFontColor: "#858796",
                    borderColor: '#dddfeb',
                    borderWidth: 1,
                    xPadding: 15,
                    yPadding: 15,
                    displayColors: false,
                    caretPadding: 10,
                },
                legend: {
                    display: false
                },
                cutoutPercentage: 80,
            },
        }
        var myPieChart = new Chart(document.getElementById("myPieChart"), option);
        myPieChart.data.datasets[0].data = datatable2;
        myPieChart.update();
        // myPieChart.updateOptions({
        //     data: {
        //         labels: ['sells','revenue','target revenue'],
        //         datasets: [{
        //             data: datatable2,
        //             backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc'],
        //             hoverBackgroundColor: ['#2e59d9', '#17a673', '#2c9faf'],
        //             hoverBorderColor: "rgba(234, 236, 244, 1)",
        //         }],
        //     },
        // })


        var options = {
            series: [{
                name: "Revenue",
                data: revenue
            },
                {
                    name: "Target price",
                    data: target_price
                },
                {
                    name: 'Sells price',
                    data: sellsPrice
                },{
                    name: 'Payments',
                    data: paymentPrice
                },
            ],
            chart: {
                height: 350,
                type: 'line',
                zoom: {
                    enabled: false
                },
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: [5, 7, 5],
                curve: 'straight',
                dashArray: [0, 8, 5]
            },
            title: {
                text: 'Page Statistics',
                align: 'left'
            },
            legend: {
                tooltipHoverFormatter: function(val, opts) {
                    return val + ' - ' + opts.w.globals.series[opts.seriesIndex][opts.dataPointIndex] + ''
                }
            },
            markers: {
                size: 0,
                hover: {
                    sizeOffset: 6
                }
            },
            xaxis: {
                categories: title,
            },
            tooltip: {
                y: [
                    {
                        title: {
                            formatter: function (val) {
                                return val + " (mins)"
                            }
                        }
                    },
                    {
                        title: {
                            formatter: function (val) {
                                return val + " per session"
                            }
                        }
                    },
                    {
                        title: {
                            formatter: function (val) {
                                return val;
                            }
                        }
                    } ,
                    {
                        title: {
                            formatter: function (val) {
                                return val;
                            }
                        }
                    }
                ]
            },
            grid: {
                borderColor: '#f1f1f1',
            }
        };
        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
        chart.updateOptions({
            series: [{
                name: "Revenue",
                data: revenue
            },
                {
                    name: "Target price",
                    data: target_price
                },
                {
                    name: 'Sells price',
                    data: sellsPrice
                },
                {
                    name: 'Payments',
                    data: paymentPrice
                }
            ],
            xaxis: {
                categories: title,
            }
        })
    }

    
})

function print() {
    var prtContent = document.getElementById("chart");
    var WinPrint = window.open('', '', 'left=0,top=0,width=800,height=900,toolbar=0,scrollbars=0,status=0');
    WinPrint.document.write(prtContent.innerHTML);
    WinPrint.document.close();
    WinPrint.focus();
    WinPrint.print();
    // WinPrint.close();
}