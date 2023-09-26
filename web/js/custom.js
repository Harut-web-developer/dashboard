$(document).ready(function (){
  // $('.product').change(function (){
  //    let option = $(this).children("option:selected").val();
  //   $.ajax({
  //       url: '/product/get-product',
  //       method:'post',
  //       datatype:'json',
  //       data:{
  //         option:option,
  //       },
  //       success: function(data){
  //           let parse = JSON.parse(data);
  //           $('.qty').keyup(function (){
  //              $('.price').val(parse.price * $('.qty').val());
  //              $('.cost').val(parse.cost * $('.qty').val());
  //               $('.revenue').val($('.price').val() - $('.cost').val());
  //           })
  //
  //       }
  //   })
  // })

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
})