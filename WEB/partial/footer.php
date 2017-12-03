
</div>
<!--end-main-container-part-->

<!--Footer-part-->

<div class="row-fluid">
    <!--<div id="footer" class="span12"> 2013 &copy; Matrix Admin. Brought to you by <a href="http://themedesigner.in">Themedesigner.in</a> </div>-->
</div>

<!--end-Footer-part-->
<script src="<?=WEBSITE;?>/js/jquery.min.js"></script>
<script src="<?=WEBSITE;?>/js/jquery.ui.custom.js"></script>
<script src="<?=WEBSITE;?>/js/bootstrap.min.js"></script>
<script src="<?=WEBSITE;?>/js/excanvas.min.js"></script>
<script src="<?=WEBSITE;?>/js/jquery.flot.min.js"></script>
<script src="<?=WEBSITE;?>/js/jquery.flot.resize.min.js"></script>
<script src="<?=WEBSITE;?>/js/jquery.peity.min.js"></script>
<script src="<?=WEBSITE;?>/js/fullcalendar.min.js"></script>
<script src="<?=WEBSITE;?>/js/matrix.js"></script>
<script src="<?=WEBSITE;?>/js/jquery.gritter.min.js"></script>
<script src="<?=WEBSITE;?>/js/matrix.interface.js"></script>
<script src="<?=WEBSITE;?>/js/matrix.chat.js"></script>
<script src="<?=WEBSITE;?>/js/jquery.validate.js"></script>
<script src="<?=WEBSITE;?>/js/matrix.form_validation.js"></script>
<script src="<?=WEBSITE;?>/js/jquery.wizard.js"></script>
<script src="<?=WEBSITE;?>/js/jquery.uniform.js"></script>
<script src="<?=WEBSITE;?>/js/select2.min.js"></script>
<script src="<?=WEBSITE;?>/js/matrix.popover.js"></script>
<script src="<?=WEBSITE;?>/js/jquery.dataTables.min.js"></script>
<script src="<?=WEBSITE;?>/js/matrix.tables.js"></script>


<script src="<?=WEBSITE;?>/js/bootstrap-colorpicker.js"></script>
<script src="<?=WEBSITE;?>/js/bootstrap-datepicker.js"></script>
<script src="<?=WEBSITE;?>/js/masked.js"></script>
<script src="<?=WEBSITE;?>/js/select2.min.js"></script>
<script src="<?=WEBSITE;?>/js/jquery.peity.min.js"></script>
<script src="<?=WEBSITE;?>/js/bootstrap-wysihtml5.js"></script>
<script src="<?=WEBSITE;?>/js/noty.js" type="text/javascript"></script>
<script src="<?=WEBSITE;?>/js/matrix.form_common.js"></script>


<script src="<?=WEBSITE;?>/js/clipboard.min.js"></script>

<script type="text/javascript">

    var clipboard = new Clipboard('.clipboard');
    $('select').on('select2-open', function () {
        if( this.selectedIndex > 0) {
            var viewport = $('#select2-drop .select2-results');
            var itemTop = viewport.find('.select2-highlighted').position().top;
            viewport.scrollTop(itemTop + viewport.scrollTop());
        }
    });

</script>
</body>
</html>
