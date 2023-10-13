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
                    $('.price').attr('readonly', true);
                    $('.cost').keyup(function()    {
                        let cost = parseInt($('.cost').val());
                        $('.price').val(Math.round(price * cost / 100 + cost) );
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

    $('.downloadXLSX').click(function () {
        var excel = new ExcelJS.Workbook();
        var tables = document.getElementsByClassName("table");
        var sheetNumber = 1;
        for (var i = 0; i < tables.length; i++) {
            var table = tables[i];
            var sheet = excel.addWorksheet("Sheet " + sheetNumber);
            var headRow = table.querySelector("thead tr");
            console.log(headRow)
            if (headRow) {
                var headerData = [];
                var headerCells = headRow.querySelectorAll("th");
                console.log(headerCells)
                headerCells.forEach(function (headerCell) {
                    headerData.push(headerCell.textContent);
                });
                sheet.addRow(headerData);
            }
            var rows = table.querySelectorAll("tbody tr");
            rows.forEach(function (row) {
                var rowData = [];
                var cells = row.querySelectorAll("td");
                // console.log(cells)
                cells.forEach(function (cell) {
                    rowData.push(cell.textContent);
                });
                sheet.addRow(rowData);
            });
            sheetNumber++;
        }

        excel.xlsx.writeBuffer().then(function (data) {
            var blob = new Blob([data], { type: "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" });
            var url = window.URL.createObjectURL(blob);
            var tablename = Math.floor(Math.random() * (1000000 - 1000 + 1)) + 1000;
            var a = document.createElement("a");
            a.href = url;
            a.download = tablename + "table_data.xlsx";
            a.click();
        });
    });

    $('#deleteButton').click(function (){
        let table = $("table");
        let keys = $('#grid').yiiGridView('getSelectedRows');
        alert(keys);
    });
    function getSelectedRows() {
        var $grid = $(this);
        var data = gridData[$grid.attr('id')];
        var keys = [];
        if (data.selectionColumn) {
            $grid.find("input[name='" + data.selectionColumn + "']:checked").each(function () {
                keys.push($(this).parent().closest('tr').data('key'));
            });
        }
        return keys;
    }

    $('.select-all-checkbox').change(function () {
        var isChecked = $(this).prop('checked');
        $('input[name="selection[]"]').prop('checked', isChecked);
    });

    $('#delete-selected').click( function() {
        let selectedIds = $('input[name="selection[]"]:checked').map(function(){
                return $(this).val();
            }).get();
        let startId = $('.startId').val();
        let endId = $('.endId').val();
        $.ajax({
            url: '/category/delete-selected',
            method:'post',
            data:{
                ids:selectedIds,
                startId:startId,
                endId:endId,
            },
            dataType: "json",
            success: function(data){
                if(data.error1 === true){
                    alert("can not  start from greater to less")
                }else if(data.error2 === true){
                    alert('choose one type of delete')
                }else {
                    confirm("Are you sure want to delete this item");
                    alert("Deleted succesfully");
                    window.location.reload();
                }
            },
        })
    });

    $('.configCat').change(function (){
        let configCat = $(this).val();
        $.ajax({
            url:'/config/get-config',
            method:'post',
            datatype:'json',
            data:{
                configCat:configCat
            },
            success:function (data){
                let parse = JSON.parse(data);
                if(parse.msg === 'warning'){
                    $('.procentConfig').attr('readonly', true);
                    alert('Teh percentage of that category exists,update the current category to change')
                }else{
                    $('.procentConfig').attr('readonly', false);
                }
            }
        })
    })
    $('.configCreate').click(function (){
        alert('created successfuly');
    })
    $('.catCreate').click(function (){
        alert('created successfuly');
    })
    $('.productCreate').click(function (){
        alert('created successfuly');
    })
    $('.createStore').click(function (){
        alert('created successfuly');
    })
    $('.targetCreate').click(function (){
        alert('created successfuly');
    })

})


























