<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>


<div class="wrapper">

	<header class="header">

        <div class="container">
            <div class="row">
                <h2>Expandable when is Focus</h2>
                <h3>Mac Search Style</h3>
                <div class="span12 newSearch">
                    <form id="custom-search-form"  class="form-search form-horizontal pull-right">
                        <div class="input-append span12">
                            <input type="text" class="search-query mac-style" placeholder="Search">
                            <button type="button" class="btn btnSearch"><i class="icon-search">DD</i></button>
                        </div>
                    </form>
                </div>
            </div>
        </div>



	</header><!-- .header-->

	<div class="middle">

		<div class="container">
			<main class="content">

        <div class="container col-md-12">
                <a href="#" class="buttonCreate">Create</a>

            <form id="createForm" method="post" action="/adding" class="" style="display:none;" enctype="multipart/form-data">

                    <select name="select_content" class="selectpicker add">
                        <option value="category">Category</option>
                        <option value="product_type">Product type</option>
                        <option value="product">Products</option>
                    </select>


                <select name="select_content_category" id="select_content_category" class="selectpicker blocked" style="display: none">
                    <?foreach ($categorys as $category):  ?>

                    <option value="<?=$category->category_id;?>"><?=$category->category_name;?></option>
                    <? endforeach;?>
                </select>

                <select name="select_content_type" id="select_content_type" class="selectpicker blocked" style="display: none">
                    <?foreach ($productType as $type):  ?>
                        <option value="<?=$type->product_type_id;?>"><?=$type->product_type_name;?></option>
                    <? endforeach;?>
                </select>


                <input id="image"  type="file"  name="images" class="blocked" style="display: none"/ >


                <input type="text" class="" id="addingC" name="adding" placeholder="">
                <input id="btnSubm" name="submit" type="submit" value="Go create">!</input>


            </form>


        </div>
<!--                <div class="container">-->
                    <h2>Product Table</h2>
                    <p>Simple product table:</p>
                <div class="get_content">
                    <table class="table table-container" style="width: 100%">
                        <thead>
                        <tr>
                            <th>Category</th>
                            <th>Product type</th>
                            <th>Products</th>
                            <th>Image</th>

                        </tr>
                        </thead>

                        <tbody class="table_content">

                        <?php if (!empty($allProduct)): ?>
                        <?php  foreach ($allProduct as $products):?>
                        <tr>

                                <?php if (!empty($products->product_name)): ?>
                            <td class="category_product" data-cat_id="<?=$products->category_id?>"><?=$products->category_name; ?></td>
                            <td class="product_type" data-type-id="<?=$products->product_type_id;?>"><?=$products->product_type_name; ?></td>
                            <td class="products"  data-prod-id="<?=$products->product_id;?>"><?=$products->product_name; ?></td>
                            <td class="productsImg"  data-prod-id="<?=$products->product_id;?>"> <img class="img-thumbnail img_mod"  alt="" src="./img/thumbs/<?=$products->product_image;?>"></td>
                                <?php endif;?>

                        </tr>
                        <?php endforeach; ?>
                        <?php endif;?>
                        </tbody>
                    </table>
                </div>

                <?php
                echo $this->pagination->create_links();
                ?>

			</main><!-- .content -->

        </div><!-- .container-->



	</div><!-- .middle-->

</div><!-- .wrapper -->
<div class="get_modal_content">
<div class="modal_content">
<div style="display: none;">
    <div class="box-modal" id="modalProduct_type">
        <form id="infoForm" action="" method="post" enctype="multipart/form-data">

            <div class="form-group">
                <input type="text" class="form-control" data-prType="" id="other_products_content" name="other_products_conten" placeholder="Change product type">
                <input type="hidden" id="pr_id" name="pr_id" value="">
                <input id="imagess"  type="file"  name="images" class="blocked" style="display: none"/ >
            </div>
            <input type="button" class="btn btn-primary submit_button" value="Edit">

            <input type="button" id="del" class="btn btn-danger submit_button" value="Del">
            <div class="pull-right">

    </div>

        </form>

        <div class="box-modal_close arcticmodal-close">Close</div>

    </div>
</div>
</div>
</div>


<div class="modal_content">
<div style="display: none;">
    <div class="box-modal" style="width: auto" id="modalProduct_img">

        <div class="box-modal_close arcticmodal-close">Close</div>

    </div>
</div>
</div>
