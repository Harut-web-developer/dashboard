$(document).ready(function (){
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

    $('.myimg').click(function () {
        let src = $(this).attr('src');
        $('.modal').modal('show');
        $('.img-thumbnail').attr('src',src);
    });

})