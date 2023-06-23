<div class="page_content_container">
    <div class="page_content_outer">
        <h6>Page content</h6>
        <h3>Custom Page</h3>
    </div>
    <div class="btn_outer">
        <button class=" btn btn-primary delete_btn me-2">Delete</button>
        <button class="btn btn-primary ">View Page</button>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <?php 
            require_once 'constants.php';
            $menu_names = CUSTOM_MENUS;
             foreach($menu_names as $key=>$names): ?>
                <form method="post" class="menuForm" action="<?=$_SERVER['PHP_SELF'];?>">
                    <div class="text_wrapper">
                        <div>
                            <input type="radio" class="form-check-input" name="name" value="<?php echo $names;?>" />
                            <label><?php echo $names;?></label>
                        </div>
                        <button type="submit" class="btn btn-primary" class="menuSubmit">Add</button>
                    </div>
                </form>
            <?php endforeach; ?>
        
    </div>
</div>
