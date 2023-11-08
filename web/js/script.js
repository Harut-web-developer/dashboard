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

    $('body').on('click','.myimg',function (){
        let src = $(this).attr('src');
        $('.imgModal').modal('show');
        $('.img-thumbnail').attr('src', src);
        // console.log(src);
    })

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
                    // console.log(data);
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
                    // console.log(data);
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

    $('body').click(function(){
        $.ajax({
            url: '/site/update-date',
        })
    })
    //
    $('.searchingProduct').keyup(function () {
        if ($(this).val().length >= 3) {
            let searchProduct = $(this).val();
            $.ajax({
                url:'/product/filter',
                method:'post',
                datatype: 'json',
                data:{
                    searchProduct:searchProduct,
                },
                success:function (data){
                    let parse = JSON.parse(data);
                    let html_ = '';
                    var tbody = $('<tbody></tbody>');
                    parse.forEach(function (item){
                        html_ = `<tr>
                                    <td>`+item.id+`</td>
                                    <td>`+item.category_id+`</td>   
                                    <td>`+item.name+`</td>   
                                    <td>`+item.description+`</td>  
                                    <td>`+item.price+`</td> 
                                    <td>`+item.cost+`</td>
                                    <td class="tabImg"><img src="/uploads/`+item.img+`"</td>      
                                    <td>`+item.keyword+`</td>
                                    <td>
                                        <a href="/product/view?id=`+item.id+`"><svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:1.125em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M573 241C518 136 411 64 288 64S58 136 3 241a32 32 0 000 30c55 105 162 177 285 177s230-72 285-177a32 32 0 000-30zM288 400a144 144 0 11144-144 144 144 0 01-144 144zm0-240a95 95 0 00-25 4 48 48 0 01-67 67 96 96 0 1092-71z"></path></svg></a>
                                        <a href="/product/update?id=`+item.id+`"><svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:1em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M498 142l-46 46c-5 5-13 5-17 0L324 77c-5-5-5-12 0-17l46-46c19-19 49-19 68 0l60 60c19 19 19 49 0 68zm-214-42L22 362 0 484c-3 16 12 30 28 28l122-22 262-262c5-5 5-13 0-17L301 100c-4-5-12-5-17 0zM124 340c-5-6-5-14 0-20l154-154c6-5 14-5 20 0s5 14 0 20L144 340c-6 5-14 5-20 0zm-36 84h48v36l-64 12-32-31 12-65h36v48z"></path></svg></a>
                                        <a href="/product/delete?id=`+item.id+`"><svg aria-hidden="true" style="display:inline-block;font-size:inherit;height:1em;overflow:visible;vertical-align:-.125em;width:.875em" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M32 464a48 48 0 0048 48h288a48 48 0 0048-48V128H32zm272-256a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zm-96 0a16 16 0 0132 0v224a16 16 0 01-32 0zM432 32H312l-9-19a24 24 0 00-22-13H167a24 24 0 00-22 13l-9 19H16A16 16 0 000 48v32a16 16 0 0016 16h416a16 16 0 0016-16V48a16 16 0 00-16-16z"></path></svg></a>
                                    </td>
                                 </tr>`;
                        tbody.append(html_);
                    })
                    $('.searchTab').children('.grid-view').children('.table').children('tbody').replaceWith(tbody);
                    $('.searchTab').children('.grid-view').children('.pagination').html('');

                }
            })
        }else if($(this).val().length === 0) {
            // alert(1)
            location.reload();
        }
    })

})






















