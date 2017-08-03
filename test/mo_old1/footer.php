		<div data-role="footer" class="footer-docs" data-theme="b">
				<?php if($host == 'omexsol.com') { ?>
				<p>&copy; 2013 Omex</p>
				<?php } else if($host == 'vts.trackeron.com' || $host == 'test.trackeron.com' || $host == 'gogpslive.com') { ?>
				<p>&copy; <?php echo date("Y"); ?>  Trackeron</p>
				<?php } else if($host == 'check.theblackbox.in' || $host == 'www.check.theblackbox.in') { ?>
				<p>&copy;  <?php echo date("Y"); ?>  The Black Box </p>
                                <?php } else if($host == 'eazytrace.co' || $host == 'www.eazytrace.co') { ?>
				<p>&copy;  <?php echo date("Y"); ?>  EazyTrace </p>
				<?php } else { ?>
				<p>&copy; <?php echo date("Y"); ?>  Trackeron</p>
				<?php } ?>
		</div>

	</div>	
</body>
</html>
