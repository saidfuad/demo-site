		<div data-role="footer" class="footer-docs" data-theme="b">
				<?php if($host == 'omexsol.com') { ?>
				<p>&copy; 2013 Omex</p>
				<?php } else if($host == 'vts.trackeron.com' || $host == 'test.trackeron.com' || $host == 'gogpslive.com') { ?>
				<p>&copy; 2013 Cadsite</p>
				<?php } else if($host == 'itmsafrica.com' || $host == 'www.itmsafrica.com') { ?>
				<p>&copy;  <?php echo date("Y"); ?>  ITMS AFRICA </p>
                                <?php } else if($host == 'eazytrace.co' || $host == 'www.eazytrace.co') { ?>
				<p>&copy;  <?php echo date("Y"); ?>  EazyTrace </p>
				<?php } else { ?>
				<p>&copy;  <?php echo date("Y"); ?> RASTREARNANET</p>
				<?php } ?>
		</div>

	</div>	
</body>
</html>
