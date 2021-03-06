<?php
$cat = Url::getParams('category');
if (empty($cat)) {
    require_once 'error.php';
} else {
    $objCatalog = new Catalog();
    $category = $objCatalog->getCategory($cat);
    if (empty($category)) {
        require_once 'error.php';
    } else {
        $rows = $objCatalog->getProducts($cat);
        $objPaging = new Paging($rows, 3);
        $rows = $objPaging->getRecords();
        require_once("_header.php");
        ?>
        <h1>Catalog: </h1> <?php echo $category['name']; ?>
        <?php
        if (!empty($rows)) {

            foreach ($rows as $row) {
                ?>
                <div class="catalogue_wrapper">
                    <div class="catalogue_wrapper_left">
                        <?php
                        $image = !empty($row['image']) ? $objCatalog->_path . $row['image'] : $objCatalog->_path . 'unavaliable.png';
                        $width = Helper::getImgSize($image, 0);
                        $width = $width > 120 ? 120 : $width;
                        ?>
                        <a href="/?page=catalogue-item&amp;category=<?php echo $category['id']; ?>&amp;id=<?php echo $row['id']; ?>"><img src="<?php echo $image; ?>" alt="<?php echo Helper::encodeHtml($row['name'], 1); ?>" width="<?php echo $width; ?>" /></a>
                    </div>
                    <div class="catalogue_wrapper_right">
                        <h4><a href="/?page=catalogue-item&amp;category=<?php echo $category['id']; ?>&amp;id=<?php echo $row['id']; ?>"><?php Helper::encodeHTML($row['name'], 1) ?></a></h4>
                        <h4>PRICE:&nbsp;
                            <?php
                            echo Catalog::$_currency;
                            echo number_format($row['price']);
                            ?>
                        </h4>
                        <p>
                            <?php echo Helper::shortenString(Helper::encodeHTML($row['description'])); ?>
                        </p>
                        <p><?php echo Basket::activeButton($row['id']); ?></p>
                    </div>
                </div>
                <?php
            }
            echo $objPaging->getPaging();
        } else {
            ?>
            <p>There are no products in this category.</p>
            <?php
        }
        require_once("_footer.php");
    }
}

