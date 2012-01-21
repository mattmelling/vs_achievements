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

		// Used to generate checkboxes
		private $checkboxes = array(
						'showCSS' => array('default' => 'true', 
								   'text' => 'Show default CSS'),
						// API spells this wrong, d'oh
						'showTimezoneOffest' => array('default' => '', 
									      'text' => 'Show timezone offset'),
						'showUserName' => array('default' => 'true', 
									'text' => 'Show username'),
						'showScore' => array('default' => 'true',
								     'text' => 'Show score'),
                                                'showMainDescription' => array('default' => 'true',
                                                                               'text' => 'Show main description'),
						'showMore' => array('default' => 'true',
                                                                    'text' => 'Show "more" link'),
                                                'showDescription' => array('default' => 'true',
                                                                           'text' => 'Show achievement description'),
                                                'showPoints' => array('default' => 'true',
                                                                      'text' => 'Show points'),
                                                'showIcon' => array('default' => 'true',
                                                                    'text' => 'Show achievement icons'),
                                                'showDateEarned' => array('default' => 'true',
                                                                          'text' => 'Show date earned'),
                                                'showTimeEarned' => array('default' => 'true',
                                                                          'text' => 'Show time earned'),
                                                'showStarted' => array('default' => 'false',
                                                                       'text' => 'Show achievements that have been started'),
                                                'showNotStarted' => array('default' => 'false',
                                                                          'text' => 'Show achievements that have not been started')
		);

		function __construct() {
			parent::WP_Widget('VS_Achievements_Widget', 'VS_Achievements_Widget',
				array('description' => 'Visual Studio Achievements Widget'));
		}

		function form($instance) {
			if($instance) {
				$username = esc_attr($instance['username']);
				$mainTitleHeadline = esc_attr($instance['mainTitleHeadline']);
				$maxAchievements = esc_attr($instance['maxAchievements']);
				$iconSize = esc_attr($instance['iconSize']);
			} else {
				$username = '';
				$mainTitleHeadline = 'h3';
				$maxAchievements = 10;
				$iconSize = 'small';
				
				// Set defaults for checkboxes
				foreach(array_keys($this->checkboxes) as $key) {
					$instance[$key] = $this->checkboxes[$key]['default'];
				}
			}
			?>
				<label for="<?php echo $this->get_field_id('username'); ?>">
					<?php _e('Username:'); ?>
				</label>
				<input class="widefat" id="<?php echo $this->get_field_id('username'); ?>"
				       name="<?php echo $this->get_field_name('username'); ?>"
                                       type="text" value="<?php echo $username; ?>" />
				<br/><br/>

				<label for="<?php echo $this->get_field_id('maxAchievements'); ?>">
					<?php _e('Max. Achievements:'); ?>
				</label>
				<input class="widefat" id="<?php echo $this->get_field_id('maxAchievements'); ?>"
				       name="<?php echo $this->get_field_name('maxAchievements'); ?>"
                                       type="text" value="<?php echo $maxAchievements; ?>" />
				<br/><br/>

			<?php
				// Generate checkboxes
				foreach(array_keys($this->checkboxes) as $key) {
					$default = $this->checkboxes[$key]['default'];
					$text = $this->checkboxes[$key]['text'];					
					?>
						<input type="checkbox" id="<?php echo $this->get_field_id($key); ?>"
						       name="<?php echo $this->get_field_name($key); ?>"
						       value="true" 
						       <?php echo $instance[$key] == 'true' ? 'checked="true"' : '' ?> />
						<label for="<?php echo $this->get_field_id($key); ?>">
							<?php _e($text); ?>
						</label>
						<br/><br/>
					<?php
				}
			?>
				<label for="<?php echo $this->get_field_id('mainTitleHeadline'); ?>">
					<?php _e('Main title headline:'); ?>
				</label>
				<select id="<?php echo $this->get_field_id('mainTitleHeadline'); ?>" class="widefat"
					name="<?php echo $this->get_field_name('mainTitleHeadline'); ?>">
					<option value="h1" <?php echo $mainTitleHeadline == 'h1' ? 'selected="true"' : '' ?>>h1</option>
					<option value="h2" <?php echo $mainTitleHeadline == 'h2' ? 'selected="true"' : '' ?>>h2</option>
					<option value="h3" <?php echo $mainTitleHeadline == 'h3' || $mainTitleHeadline == '' ? 'selected="true"' : '' ?>>h3</option>
					<option value="h4" <?php echo $mainTitleHeadline == 'h4' ? 'selected="true"' : '' ?>>h4</option>
					<option value="h5" <?php echo $mainTitleHeadline == 'h5' ? 'selected="true"' : '' ?>>h5</option>
					<option value="h6" <?php echo $mainTitleHeadline == 'h6' ? 'selected="true"' : '' ?>>h6</option>
					<option value="h7" <?php echo $mainTitleHeadline == 'h7' ? 'selected="true"' : '' ?>>h7</option>
					<option value="div" <?php echo $mainTitleHeadline == 'div' ? 'selected="true"' : '' ?>>div</option>
					<option value="span" <?php echo $mainTitleHeadline == 'span' ? 'selected="true"' : '' ?>>span</option>
				</select>

				<label for="<?php echo $this->get_field_id('iconSize'); ?>">
					<?php _e('Icon size:'); ?>
				</label>
				<select id="<?php echo $this->get_field_id('iconSize'); ?>" class="widefat"
					name="<?php echo $this->get_field_name('iconSize'); ?>">
					<option value="small" <?php echo $iconSize == 'small' ? 'selected="true"' : '' ?>>Small</option>
					<option value="large" <?php echo $iconSize == 'large' ? 'selected="true"' : '' ?>>Large</option>
				</select>
			<?php	
		}

		function update($new_instance, $old_instance) {
			$instance = $old_instance;

			$instance['username'] = strip_tags($new_instance['username']);
			$instance['mainTitleHeadline'] = strip_tags($new_instance['mainTitleHeadline']);
			$instance['maxAchievements'] = strip_tags($new_instance['maxAchievements']);
			$instance['iconSize'] = strip_tags($new_instance['iconSize']);

			// Pull values out of the generated checkboxes.
			foreach(array_keys($this->checkboxes) as $key) {
				$current = strip_tags($new_instance[$key]);
				$instance[$key] = $current == 'true' ? 'true' : 'false';
			}

			return $instance;
		}

		function widget($args, $instance) {
			$query = '';
			
			// Generate query string arguments from checkboxes
			foreach(array_keys($this->checkboxes) as $key) {
				if($instance[$key] != $this->checkboxes[$key]['default']) {
					$query .= '&' . $key . '=' . $instance[$key];
				}
			}

			if($instance['mainTitleHeadline'] != '') {
				$query .= '&mainTitleHeadline=' . $instance['mainTitleHeadline'];
			}
			if($instance['maxAchievements'] != '') {
				$query .= '&maxAchievements=' . $instance['maxAchievements'];
			}
			if($instance['iconSize'] != '') {
				$query .= '&iconSize=' . $instance['iconSize'];
			}

			?>
				<script src="http://video.ch9.ms/widgets/VSachievements.min.js?user=<?php echo $instance['username']; ?><?php echo $query; ?>" 
                                        id="ch9VSachievements" defer="defer"></script>
			<?php
		}
	}
	add_action('widgets_init', create_function('', 'register_widget("VS_Achievements_Widget");'));
?>
