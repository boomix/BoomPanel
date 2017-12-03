<?php if(!isset($db)) die(); ?>
<?php if(!empty($_SESSION['error'])) { ?>
<div class="alert alert-error alert-block" style="font-size: 13px">
    <strong><?=UP(ERROR);?>!</strong> <?=htmlspecialchars($_SESSION['error']);?>
</div>
<?php unset($_SESSION['error']);} ?>

<?php if(!empty($_SESSION['success'])) {?>
    <div class="alert alert-success alert-block" style="font-size: 13px">
        <button class="close" data-dismiss="alert">×</button>
        <strong><?=UP(SUCCESS);?>!</strong> <?=htmlspecialchars($_SESSION['success']);?>
    </div>
<?php unset($_SESSION['success']); } ?>

<?php if(!empty($_SESSION['warning'])) { ?>
    <div class="alert alert-warning alert-block" style="font-size: 13px">
        <strong><?=UP(WARNING);?>!</strong> <?=htmlspecialchars($_SESSION['warning']);?>
    </div>
<?php unset($_SESSION['warning']); } ?>