$(document).ready(function (){
    $('body').on('click', '.create', function(){
        var html_ = '';
        $('.order_products').html('');
        $('.table_tr').each(function (){

            if ($(this).find("input:checkbox").is(':checked')){

                // debugger;
                let id = $(this).find("input:checkbox").attr('data-id');
                let name = $(this).children(".prodname").text();
                let price = $(this).children('.prodprice').val();
                let cost = $(this).children('.prodcost').val();
                let count = $(this).children('.prodcount').children('input').val();
                let total = price * count;

                html_ +=`<tr class="product_tr">
                            <th scope="row">`+id+` <input type="hidden" name="productid[]" value="`+id+`"></th>
                            <td class="name">`+name+`</td>
                            <td class="count"><input type="number" name="count_[]" value="`+count+`" class="form-control"></td>
                            <td class="price">`+price+` <input type="hidden" name="price[]" value="`+price+`"></td>
                            <td class="total">`+total+` <input type="hidden" name="total[]" value="`+total+`"></td>
                            <td class="cost">`+cost+` <input type="hidden" name="cost[]" value="`+cost+`"></td>
                            <td class="btn"><button onclick="$(this).closest('tr').remove()" type="button" class="btn btn-outline-danger">Delete</button></td>
                        </tr>`;
            }
            $('#exampleModal .close').click();
        })
        $('.order_products').append(html_);
    })


    $('.store').change(function (){
        let store = $(this).val();
        let category = $('.category').val();
        let start = $('.start_date').val();
        let end = $('.end_date').val();
        workChart(store,category,start,end);
    })
    $('.category').change(function (){
        let store = $('.store').val();
        let category = $(this).val();
        let start = $('.start_date').val();
        let end = $('.end_date').val();
        workChart(store,category,start,end);
    })
        $(window).on('load', function (){
            let start = $('.start_date').val();
            let end = $('.end_date').val();

            workChart(0,0,start,end);
        })

    function  workChart(store,category,start,end){
        var title = [];
        var datatable1 = [];
        var datatable2 = [];
        $.ajax({
            url:'/chart/get-data',
            method:'post',
            datatype:'json',
            data:{
                start:start,
                end:end,
                store:store,
                category:category
            },
            async: false,
        }).done(function(data){
            let parse = JSON.parse(data, true);
            $('.productName').text(parse.maxPrice.maxPrice);
            $('.productPrice').text(parse.maxPrice.name);
            $('.productImg').attr('src','/uploads/' + parse.maxPrice.img);
            $('.maxCountProductName').text(parse.maxCount.maxCount);
            $('.productMaxCount').text(parse.maxCount.name);
            $('.productCountImg').attr('src','/uploads/' + parse.maxCount.img);
            $('.orderProcent').text(parse.overageProcent + '%');
            $('.procentBar').attr('style', 'width:' + parse.overageProcent + '%');
            $('.ordersCount').text(parse.ordersCount);
            datatable2[0] = parseInt(parse.ordersTotalPrice.total);
            datatable2[1] = parseInt(parse.maxCount.revenue);
            datatable2[2] = parse.maxCount.target_price;
            // console.log(datatable);
            })
        // Pie Chart Example
        var ctx = document.getElementById("myPieChart");
        var myPieChart = new Chart(ctx, {
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
        });
    }



    var options = {
        series: [{
            name: "Session Duration",
            data: [45, 52, 38, 24, 33, 26, 21, 20, 6, 8, 15, 10]
        },
            {
                name: "Page Views",
                data: [35, 41, 62, 42, 13, 18, 29, 37, 36, 51, 32, 35]

            },
            {
                name: 'Total Visits',
                data: [87, 57, 74, 99, 75, 38, 62, 47, 82, 56, 45, 47]
            }
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
            categories: ['01 Jan', '02 Jan', '03 Jan', '04 Jan', '05 Jan', '06 Jan', '07 Jan', '08 Jan', '09 Jan',
                '10 Jan', '11 Jan', '12 Jan'
            ],
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
                }
            ]
        },
        grid: {
            borderColor: '#f1f1f1',
        }
    };

    var chart = new ApexCharts(document.querySelector("#chart"), options);
    chart.render();

})