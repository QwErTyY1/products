$(document).ready(function () {

        // var table_content =  $("tbody.table_content");
        var table_content =  $(".get_content");
        var other_products_content = $("#other_products_content");
        var product_id = $("#pr_id");
        var schemaName = "";
        var imgUplEdit = $("#imagess");
        var modalType  = $("#modalProduct_type");
        var pagination = $(".product_page");
        var search = $(".search-query");

     function cleanField(field) {

         for (var i=0;i<field.length;i++)
         {
                field[i].val("");
         }
     }

    table_content.on("click","td", function (e) {

        if (this.className == "products"){
            imgUplEdit.show();
        }else {
            imgUplEdit.hide();
        }
    });

    table_content.on("click",".category_product", function (e) {
        e.preventDefault();

        var attr = $(this).attr("data-cat_id");

        var name = $(this).html();

        schemaName = "category";

        other_products_content.attr("data-prType", schemaName)

        // var prType = $("#other_products_content")
            .attr("value", name);

        product_id.val(attr);

        modalType.arcticmodal();
    });

    table_content.on("click","td.product_type", function (e) {

        e.preventDefault();

        var attr = $(this).attr("data-type-id");

        var name = $(this).html();

        var prCategory = $("#prCategory").attr("placeholder", name);

        schemaName = "product_type";

        other_products_content.attr("data-prType", schemaName)
            .attr("value", name);

        product_id.val(attr);

        modalType.arcticmodal();
    });

    table_content.on("click","td.products", function (e) {

        e.preventDefault();

        var attr = $(this).attr("data-prod-id");
        var name = $(this).html();
        var prProduct = $("#prProduct").attr("placeholder", name);

        schemaName = "product";
            other_products_content.attr("data-prType", schemaName)
                .attr("value", name);

            product_id.val(attr);
            modalType.arcticmodal()
    });

    table_content.on("click","td.productsImg", function (e) {

        e.preventDefault();

        var attr = $(this).attr("data-prod-id");
        var name = $(this).html();
        var prProduct = $("#prProduct").attr("placeholder", name);

        other_products_content.attr("data-prType", schemaName)
                .attr("value",name);
            schemaName = "product";

            product_id.val(attr);

            var img = $(this).find('.img_mod').attr("src");

            var text = img.replace('thumbs/','product/');
            var img = text.replace('_thumb','');

            $("#modalProduct_img").arcticmodal()
                .html('<img width="350" height="300" src="'+img+'" />');
    });

    $("a.buttonCreate").click(function (e) {
        e.preventDefault();

        // $(".selectpicker")

        var form = $("#createForm");

        if (form.is(":visible")){
            form.hide();
        }else if (form.show()){
            form.show();
        }

    });

    $('body').on('click','#btnSubm', function(e){

        var addingC = $("#addingC");
        var data_clean = [addingC];

        var form = $("form#createForm");
        form.ajaxForm();

        form.ajaxSubmit({
            url: 'adding',
            iframe: true,
            dataType: 'json',

            beforeSubmit: function (arr, $form, options) {

            },
            success: function (data) {

                if (data.html.status == "OK"){

                    sweetAlert(data.html.success);
                    $( ".table-container" ).load( "home/index .table-container" );
                }

                if (data.html.status == "ERR"){
                    swal(data.html.error_valid.adding,"","error");
                }
                cleanField(data_clean);
            }
        });
        e.preventDefault();
    });


    $("select.selectpicker.add").on("change", function (e) {
       var select_content = $(this).val();
       var blockedDisplay = $("select.selectpicker.blocked") && $(".blocked");
        var select_content_category = $('#select_content_category');
        var select_content_type = $('#select_content_type');

       if (select_content === "product"){

           $.post("home/ajaxChangeProduct",{}, function (data) {

              select_content_category.find('option').remove();
              select_content_type.find('option').remove();

               $.each(data.html.category_product, function( index, value ) {

                       select_content_category.append($('<option>', {
                           value: value.category_id,
                           text : value.category_name

                       }));
               });

               $.each(data.html.type_product, function( index, value ) {

                   select_content_type.append($('<option>', {
                       value: value.product_type_id,
                       text : value.product_type_name
                   }));
               });

              // $("#select_content_category").append(data.html())

           });

            blockedDisplay
                .show();
       } else {
           blockedDisplay
               .hide();
       }

    });

    $("form").on("click", ".submit_button", function (e) {
        e.preventDefault();

        var submit_button = $(this).val();
        var form     = $("form#infoForm");
        var formData = form.serialize()+"&schema="+schemaName;

        if (submit_button == "Del"){

            sweetAlert(
                {
                    title: "Are you sure?",
                    text: "Are you sure you want to delete this entry?!",
                    type: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Yes, delete it!"
                }, function () {
                    $.post("home/deleted",formData,function (data) {

                        if (data.html.status == "OK"){
                            sweetAlert(data.html.success);
                            $( ".table-container" ).load( "home/index .table-container" );
                            $.arcticmodal("close");
                        }
                    },'json')
                }

            );
        }

        if (submit_button == "Edit"){
            form.ajaxForm();

            form.ajaxSubmit({
                url: 'adding',
                iframe: true,
                dataType: 'json',
                data: {schema:schemaName},
                beforeSubmit: function (arr, $form, options) {

                },
                success: function (data) {
                    if (data.html.status == "OK"){

                        sweetAlert(data.html.success);
                        table_content.load( "home/index .table-container" );
                        $.arcticmodal("close");

                        $("#imagess").val("");

                        form[0].reset();
                    }
                }
            });
        }
    });

    $(".newSearch").on("click", ".btnSearch", function (e) {

        $.post("search", {search:search.val()}, function (ddd) {

            var data = $(ddd).find('.table-container');
            table_content.html(data);


            var totalSearch = data.find("tr").length -1;

            sweetAlert("Search "+totalSearch);

        });

    });

    pagination.on("click","li", function (e) {
       e.preventDefault();

       cleanField([search]);

       $("li").removeClass("active");
       $(this).addClass("active");

        var cur_page = $(this).find('a').attr("data-ci-pagination-page");
        var getPage;

        if (cur_page === undefined){
            getPage = 0;
        } else {
            getPage = cur_page;
        }

        table_content.load( "home/index/"+getPage+" .table-container" );

    });

});
