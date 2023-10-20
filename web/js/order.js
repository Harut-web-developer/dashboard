$(document).ready(function () {
    $('body').on('click', '.create', function(){
        var html_ = '';
        var newTbody = $('<tbody></tbody>');
        $('.table_tr').each(function (){
            if ($(this).find("input:checkbox").is(':checked')){
                let id = $(this).find("input:checkbox").attr('data-id');
                let name = $(this).children(".prodname").text();
                let price = $(this).children('.prodprice').val();
                let cost = $(this).children('.prodcost').val();
                let count = $(this).children('.prodcount').children('input').val();
                let total = price * count;
                html_ +=`<tr class="product_tr">
                            <th scope="row">`+id+` <input type="hidden" name="productid[]" value="`+id+`"></th>
                            <td class="name">`+name+`</td>
                            <td class="count"><input type="number" name="count_[]" value="`+count+`" class="form-control countProduct"></td>
                            <td class="price">`+price+` <input type="hidden" name="price[]" value="`+price+`"></td>
                            <input type="hidden" name="total[]" value="`+total+`">
                            <td class="total">`+total+` </td>
                            <td class="cost">`+cost+` <input type="hidden" name="cost[]" value="`+cost+`"></td>
                            <td class="btnn"><button type="button" class="btn btn-outline-danger delItems">Delete</button></td>
                         </tr>`;
            }
        });
        newTbody.append(html_);
        $('.order_products tbody').replaceWith(newTbody);
        $('#examMod .close').click();
    });
    $('body').on('click', '.create', function() {
        let summury = 0;
        $('.product_tr').each(function () {
            summury += parseInt($(this).find('.total').text());
        });
        $('.last_total_price').attr('value', summury);
    })
        $('body').on('input click','.countProduct', function() {
        $(this).parent('.count').closest('.product_tr').children('input').val($(this).val() * parseInt($(this).parent('.count').closest('.product_tr').children('.price').children('input').val()))
        $(this).parent('.count').closest('.product_tr').children('.total').text($(this).val() * parseInt($(this).parent('.count').closest('.product_tr').children('.price').children('input').val()))
    })
    $('body').on('input keyup','.countProduct', function() {
        let sum = 0;
        $('.product_tr').each(function () {
            sum += parseInt($(this).find('.total').text());
        });
        $('.last_total_price').attr('value', sum);
    });
    // console.log(summury);
    $('body').on('click','.btnn .delItems', function() {
        let confirmed =  confirm("Are you sure want to delete this item");
        if (confirmed){
            $(this).closest('tr').remove();
            alert('deleted successfully');
            let summur = 0;
            $('.product_tr').each(function () {
                summur += parseInt($(this).find('.total').text());
            });
            $('.last_total_price').attr('value', summur);
        }
    });
    $('.ordersCreate').click(function (e){
        e.preventDefault();
        if ($('.table.order_products tbody').children().length === 0) {
            alert('add the product');
        } else {
            if($('#selectPay').val() !== '' && $('#inpPay').val() >= $('.last_total_price').val()){
                alert('Created successfuly');
                $('.ordersCreate').closest('form').submit();
            }else if($('#selectPay').val() === ''){
                alert('Chosse type of payment');
            }else if($('#inpPay').val() < $('.last_total_price').val()){
                alert('Pay more than marker in case');
            }
            else if($('#inpPay').val() === ''){
                alert('Fill in the all field');
            }
        }

    })
})