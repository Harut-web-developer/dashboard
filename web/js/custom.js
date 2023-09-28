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

    function delay(callback) {
        var timer = 0;
        return function() {
            var context = this, args = arguments;
            clearTimeout(timer);
            timer = setTimeout(function () {
                callback.apply(context, args);
            },  0);
        };
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