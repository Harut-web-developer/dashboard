$(document).ready(function (){
    // $('.orderSave').click(function (e){
    //     e.preventDefault();
    //     let order_id = $('.order_id').val();
    //     $.ajax({
    //         url:'/orders/get-orders',
    //         method: 'post',
    //         datatype: 'json',
    //         data:{
    //             order_id:order_id,
    //         },
    //         success:function (data){
    //             console.log(12351);
    //         }
    //     })
    // })

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
        var lab = [];
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
            lab[0] = parse.ordersTotalPrice.total;
            console.log(parse);
            })
        // Pie Chart Example
        var ctx = document.getElementById("myPieChart");
        var myPieChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['sells','revenue'],
                datasets: [{
                    data: [15, 30, 15],
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

    $('.category').change(function () {
        var category_id = $(this).children("option:selected").val();
        $.ajax({
            url: '/product/get-product',
            method:'get',
            data:{
                option:category_id,
            },
            dataType: "json",
            success: function(parse){

               let price = parseInt(parse.category_id);
                if (!(parse.category_id == null)) {
                    console.log(price)
                    $('.price').attr('readonly', true);
                    $('.cost').keyup(function()    {
                        let cost = parseInt($('.cost').val());
                        $('.price').val(   price * cost / 100 + cost );
                    });
                }
                else
                {
                    $('.price').attr('readonly', false);
                }

            },
        })
    })


})