<?php
	/**
	 * @package vs-achivements
	 * @version 1.0
	 */
	/*
		Plugin Name: Visual Studio Achievements
		Plugin URI: https://github.com/kobowi/vs_achievements
		Description: Shows Visual Studio Achievements from your Channel9 profile in a widget.
		Author: Matt Melling
		Version: 1.0
		Author URI: http://kobowi.co.uk
	*/

	class VS_Achievements_Widget extends WP_Widget {
		function __construct() {
			parent::WP_Widget('VS_Achievements_Widget', 'VS_Achievements_Widget',
				array('description' => 'Visual Studio Achievements Widget'));
		}

		function form($instance) {
			if($instance) {
				$username = esc_attr($instance['username']);
				$showCSS = esc_attr($instance['showCSS']);

			} else { 
				$username = '';
				$showCSS = 'true';
			}
			?>
				<label for="<?php echo $this->get_field_id('username'); ?>">
					<?php _e('Username:'); ?>
				</label>
				<input class="widefat" id="<?php echo $this->get_field_id('username'); ?>"
				       name="<?php echo $this->get_field_name('username'); ?>"
                                       type="text" value="<?php echo $username; ?>" />
				<br/><br/>
				<label for="<?php echo $this->get_field_id('showCSS'); ?>">
                                        <?php _e('Show default CSS:'); ?>
                                </label>
				<input type="checkbox" id="<?php echo $this->get_field_id('showCSS'); ?>"
				       name="<?php echo $this->get_field_name('showCSS'); ?>"
                                       value="true" <?php echo $showCSS == 'true' ? 'checked="true"' : '' ?> />
			<?php	
		}

		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$showCSS = strip_tags($new_instance['showCSS']);
			$instance['showCSS'] = $showCSS == 'true' ? 'true' : '';
			$instance['username'] = strip_tags($new_instance['username']);
			return $instance;
		}

		function widget($args, $instance) {
			$query = '';
			if($instance['showCSS'] == 'true') {
				$query .= '&defaultCSS=true';
			}
			?>
				<script src="http://video.ch9.ms/widgets/VSachievements.min.js?user=<?php echo $instance['username']; ?><?php echo $query; ?>" 
                                        id="ch9VSachievements" defer="defer"></script>
			<?php
		}
	}
	add_action('widgets_init', create_function('', 'register_widget("VS_Achievements_Widget");'));
?>
