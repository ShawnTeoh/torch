<!--Footer-->
		<footer>
			<div class="container">
				<div class="site-sns">
                <?php 
				for($i=0;$i<10; $i++){
					$social_icon = esc_attr(torch_options_array('social_icon_'.$i));
					$social_link = esc_url(torch_options_array('social_link_'.$i));
					if($social_link !=""){
					echo '<a href="'.$social_link.'" target="_blank"><i class="'.$social_icon.'"></i></a>';
					}
					}
					?>
				</div>
				<div class="site-info">
					<?php
						if (function_exists('pll_e')) {
							pll_e(torch_options_array('footer_text'));
						} else {
							echo torch_options_array('footer_text');
						}
					?>
				</div>
				<div class="footer-translate">
					<?php
						if (function_exists('pll_the_languages')) {
							pll_the_languages(array('dropdown' => 1));
						}
					?>
				</div>
			</div>
		</footer>
    <?php wp_footer();?>
</body>
</html>
