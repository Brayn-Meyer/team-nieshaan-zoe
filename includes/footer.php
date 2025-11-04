	</div> <!-- End main-container -->
    
	<!-- Global JavaScript -->
	<script src="<?php echo BASE_URL; ?>/assets/js/main.js"></script>
	<script src="<?php echo BASE_URL; ?>/assets/js/api.js"></script>
    
	<?php if (isset($additionalJS)): ?>
		<?php foreach ($additionalJS as $js): ?>
			<script src="<?php echo BASE_URL . $js; ?>"></script>
		<?php endforeach; ?>
	<?php endif; ?>
</body>
</html>

<?php
// (end of footer)
?>
