	</div> <!-- End main-container -->
    
	<!-- Bootstrap JS Bundle with Popper -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
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
