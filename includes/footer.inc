<div class="panel-copyright">
    <!-- Footer -->
    <p class="copyright">&copy; UK Pinball League <?=date("Y");?> </p>
	<p class="copyright">(Environment: <?=$env?>)</p>
</div>

<div class="panel-bottom"></div>
</div> <!-- End Container -->

<!-- SlickNav start -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>
<script src="js/jquery.slicknav.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('#menu').slicknav({
	prependTo:'#nav-wrapper',
    closeOnClick:'true' // Close menu when a link is clicked.	
	});
});
</script>
<!-- SlickNav end -->

