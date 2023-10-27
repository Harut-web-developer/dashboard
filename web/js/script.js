$(document).ready(function () {
    $('.category').change(function () {
        var category_id = $(this).children("option:selected").val();
        $.ajax({
            url: '/product/get-product',
            method: 'get',
            data: {
                option: category_id,
            },
            dataType: "json",
            success: function (parse) {
                let price = parseInt(parse.category_id);
                if (!(parse.category_id == null)) {
                    $('.price').attr('readonly', true);
                    $('.cost').keyup(function () {
                        let cost = parseInt($('.cost').val());
                        $('.price').val(Math.round(price * cost / 100 + cost));
                    });
                } else {
                    $('.price').attr('readonly', false);
                    $('.price').val('');
                    $('.price').focus();
                }
            },
        })
    })

    $('.myimg').click(function () {
        let src = $(this).attr('src');
        $('.modal').modal('show');
        $('.img-thumbnail').attr('src', src);
    });


    $('.downloadXLSX').click(function () {
        var excel = new ExcelJS.Workbook();
        var tables = '';
        var sheetNumber = 1;
        var PromiseArray = [];
        function addImage(url, workbook, worksheet, excelCell) {
            return new Promise(function (resolve, reject) {
                var xhr = new XMLHttpRequest();
                xhr.open('GET', url);
                xhr.responseType = 'blob';
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        var reader = new FileReader();
                        reader.readAsDataURL(xhr.response);
                        reader.onloadend = function () {
                            var base64data = reader.result;
                            const image = workbook.addImage({
                                base64: base64data,
                                extension: 'png',
                            });
                            worksheet.getRow(excelCell.row).height = 75;
                            worksheet.addImage(image, {
                                tl: { col: excelCell.col - 1, row: excelCell.row - 1 },
                                br: { col: excelCell.col, row: excelCell.row }
                            });
                            resolve();
                        };
                    } else {
                        console.error('Failed to fetch image. Status code:', xhr.status);
                        resolve();
                    }
                };
                xhr.onerror = function () {
                    console.error('Could not add image to excel cell');
                    resolve();
                };
                xhr.send();
            });
        }
        $.ajax({
            url: '',
            method: 'post',
            data: {
                action: 'xls-alldata',
            },
            dataType: "html",
            success: function(data) {
                $('body').append(data);
                tables = document.getElementsByClassName("chatgbti_");
                $(".chatgbti_").hide();
                $(".deletesummary").hide();
                for (var i = 0; i < tables.length; i++) {
                    var table = tables[i];
                    var sheet = excel.addWorksheet("Sheet " + sheetNumber);
                    var headRow = table.querySelector("thead tr");
                    if (headRow) {
                        var headerData = [];
                        var headerCells = headRow.querySelectorAll("th:not(:last-child)");
                        headerCells.forEach(function (headerCell) {
                            headerData.push(headerCell.textContent);
                        });
                        sheet.addRow(headerData);
                    }
                    var rows = table.querySelectorAll("tbody tr");
                    rows.forEach(function (row) {
                        var rowData = [];
                        var cells = row.querySelectorAll("td:not(:last-child)");
                        cells.forEach(function (cell) {
                            if (cell.querySelector("img")) {
                                var imgElement = cell.querySelector("img");
                                var imageUrl = imgElement.src;
                                var excelCell = {
                                    row: sheet.rowCount + 1,
                                    col: headerCells.length
                                };
                                PromiseArray.push(addImage(imageUrl, excel, sheet, excelCell));
                            } else {
                                rowData.push(cell.textContent);
                            }
                        });
                        if (rowData.length > 0) {
                            sheet.addRow(rowData);
                        }
                    });

                    sheetNumber++;
                }
                Promise.all(PromiseArray)
                    .then(function () {
                        return excel.xlsx.writeBuffer();
                    })
                    .then(function (buffer) {
                        var blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
                        var url = window.URL.createObjectURL(blob);
                        var a = document.createElement('a');
                        a.href = url;
                        var tablename = Math.floor(Math.random() * (1000000 - 1000 + 1)) + 1000;
                        a.download = tablename + "table_data.xlsx";
                        a.style.display = 'none';
                        document.body.appendChild(a);
                        a.click();
                        window.URL.revokeObjectURL(url);
                    })
                    .catch(function (error) {
                        console.error('Error:', error);
                    });
                $(".chatgbti_").removeClass();
            },
        });
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

    $('#delete-selected').click(function () {
        let selectedIds = $('input[name="selection[]"]:checked').map(function () {
            return $(this).val();
        }).get();
        let startId = $('.startId').val();
        let endId = $('.endId').val();
        $.ajax({
            url: '/category/delete-selected',
            method: 'post',
            data: {
                ids: selectedIds,
                startId: startId,
                endId: endId,
            },
            dataType: "json",
            success: function (data) {
                if (data.error1 === true) {
                    alert("can not  start from greater to less")
                } else if (data.error2 === true) {
                    alert('choose one type of delete')
                } else if (data.error3 === true) {
                    alert('Fill in the field');

                }
                else if(data.success === true) {
                    let con = confirm("Are you sure want to delete this item");
                        if (con){
                            alert("Deleted succesfully");
                            window.location.reload();
                        }
                }
            },
        })
    });
    
    $('.configCat').change(function () {
        let configCat = $(this).val();
        $.ajax({
            url: '/config/get-config',
            method: 'post',
            datatype: 'json',
            data: {
                configCat: configCat
            },
            success: function (data) {
                let parse = JSON.parse(data);
                if (parse.msg === 'warning') {
                    $('.procentConfig').attr('readonly', true);
                    alert('Teh percentage of that category exists,update the current category to change')
                } else {
                    $('.procentConfig').attr('readonly', false);
                }
            }
        })
    })
    $('.configCreate').click(function () {
        alert('created successfuly');
    })
    $('.catCreate').click(function () {
        alert('created successfuly');
    })
    $('.productCreate').click(function () {
        alert('created successfuly');
    })
    $('.createStore').click(function () {
        alert('created successfuly');
    })
    $('.targetCreate').click(function () {
        alert('created successfuly');
    })

    // $('.inputval').on('input', function () {
    //     var inputValue = $(this).val();
    //     $('.shearch_menu').addClass('activ');
    //     if (inputValue == "") {
    //         $('.shearch_menu').removeClass('activ');
    //     }
    //     $.ajax({
    //         url: '/product/searching',
    //         method: 'post',
    //         data: {
    //             option: inputValue,
    //         },
    //         dataType: "json",
    //         success: function (data) {
    //             console.log(data);
    //             $('.parentLiProduct').html('');
    //             $('.parentLiCategory').html('');
    //             for (let i = 0; i < data.query_product.length; i++) {
    //                 let idval = data.query_product[i].id;
    //                 $(".parentLiProduct").append('<li class="fs-search-result-column-list-el"><a href="/product/view?id=' + idval + '" target="_blank">' + data.query_product[i].name + '</a></li>');
    //             }
    //
    //             for (let i = 0; i < data.query_category.length; i++) {
    //                 $(".parentLiCategory").append(' <li class="fs-search-result-column-list-el"> <a href="/category/index?searchtable=' + $('.inputval').val() + '" target="_blank">' + data.query_category[i].name + '</a> </li> ');
    //             }
    //         },
    //     });
    // });

    $(".icon").click(function() {
        var icon = $(this),
            input = icon.parent().find("#search"),
            submit = icon.parent().find(".submit"),
            is_submit_clicked = false;
        input.animate({
            "width": "100px",
            "padding": "10px",
            "opacity": 1
        }, 300, function() {
            input.focus();
        });

        submit.mousedown(function() {
            is_submit_clicked = true;
        });

        icon.fadeOut(300);
        input.blur(function() {
            if(!input.val() && !is_submit_clicked) {
                input.animate({
                    "width": "0",
                    "padding": "0",
                    "opacity": 0
                }, 200);
                icon.fadeIn(200);
            };
        });
    });
        $('.inputval').on('input', function () {
            var inputValue = $(this).val();
            $('.shearch_menu').addClass('activ');
            if (inputValue == "") {
                $('.shearch_menu').removeClass('activ');
            }
            $.ajax({
                url: '/product/searching',
                method: 'post',
                data: {
                    option: inputValue,
                },
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    $('.parentLiProduct').html('');
                    $('.parentLiCategory').html('');
                    for (let i = 0; i < data.query_product.length; i++) {
                        let idval = data.query_product[i].id;
                        $(".parentLiProduct").append('<li class="fs-search-result-column-list-el"><a href="/product/view?id=' + idval + '" target="_blank">' + data.query_product[i].name + '</a></li>');
                    }

                    for (let i = 0; i < data.query_category.length; i++) {
                        $(".parentLiCategory").append(' <li class="fs-search-result-column-list-el"> <a href="/category/index?searchtable=' + $('.inputval').val() + '" target="_blank">' + data.query_category[i].name + '</a> </li> ');
                    }
                },
            });
        });
        $('.inputValue').on('input',function (){
            let inp = $(this).val();
            $('.searchMenu').addClass('activeMenu');
            if (inp == "") {
                $('.searchMenu').removeClass('activeMenu');
            }
            $.ajax({
                url: '/product/searching',
                method: 'post',
                data: {
                    option: inp,
                },
                dataType: "json",
                success: function (data) {
                    console.log(data);
                    $('.parentLiProd').html('');
                    $('.parentLiCat').html('');
                    for (let i = 0; i < data.query_product.length; i++) {
                        let idval = data.query_product[i].id;
                        $(".parentLiProd").append('<li class="fs-search-result-column-list-el li-prod"><a href="/product/view?id=' + idval + '" target="_blank">' + data.query_product[i].name + '</a></li>');
                    }

                    for (let i = 0; i < data.query_category.length; i++) {
                        $(".parentLiCat").append(' <li class="fs-search-result-column-list-el"> <a href="/category/index?searchtable=' + $('.inputValue').val() + '" target="_blank">' + data.query_category[i].name + '</a> </li> ');
                    }
                },
            })
        })

    // $('.h4_product').click(function (){
    //
    // })

})






















