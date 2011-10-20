<?php 
/**
 * 
 * @todo 	I did something very bad here, to make time.  We need to separate these out into separate view files, and pick the view file by the banner type.  That way each banner type can have its own form fields, and we don't need this horrible formatting.
 * @todo	These size variables should be passed to this view by the controller.
 */
?>
<div class="banners form">
<?php echo $this->Form->create('Banner', array('type' => 'file'));?>
<h1><?php $this->request->data['Banner']['banner_position_id'] == 1 ?  __('Daily Deal Builder') : __('Ad Builder'); ?></h1>
	<fieldset>
 		<fieldset> 
		<?php
			echo $this->Form->input('Banner.id', array('class' => 'text-1'));
			echo $this->Form->input('Banner.banner_position_id', array('type' => 'hidden')); 
			echo $this->Form->input('name', array('class' => 'text-1', 
											'label' => 'Give this ad a title.', 
											'div' => array('class' => 'text-inputs')));
			echo $this->request->data['Banner']['banner_position_id'] == 1 ? $this->Form->input('description', array('class' => 'text-1', 
												   'label' => 'A very short (3-8 word) tagline.', 
												   'div' => array('class' => 'text-inputs'))) : '';
		?>
		</fieldset>
		<?php 
			echo $this->request->data['Banner']['banner_position_id'] == 1 ? $this->Form->input('price', array('class' => 'text-1',
											 'label' => 'What is the regular price for the item being advertised?',
											 'div' => array('class' => 'text-inputs'))) : '';
			echo $this->request->data['Banner']['banner_position_id'] == 1 ? $this->Form->input('discount_price', array('class' => 'text-1',
													  'label' => 'What is the sale price for the item?', 
													  'div' => array('class' => 'text-inputs'))) : '';
			echo $this->request->data['Banner']['banner_position_id'] == 2 ? $this->Form->input('redemption_url', array('class' => 'text-1', 
													  'label' => 'Where should users who click the ad go?',
													  'div' => array('class' => 'text-inputs'))) : '';
			echo $this->element('thumb', array('plugin' => 'galleries', 
											   'model' => 'Banner', 'foreignKey' => $this->request->data['Banner']['id'], 
											   'thumbSize' => 'medium', 'thumbLink' => '#'));  
			echo $this->request->data['Banner']['banner_position_id'] == 1 ? 
				 $this->Form->input('GalleryImage.filename', array('type' => 'file', 
															 'label' => 'Upload Image for Ad (w 284 x h 125 pixels)', 
															 'div' => array('class' => 'search-file'),
															 'class' => 'text')) : 
				 $this->Form->input('GalleryImage.filename', array('type' => 'file', 
															 'label' => 'Upload Image for Ad (w 284 x h 260 pixels)', 
															 'div' => array('class' => 'search-file'),
															 'class' => 'text'));
		    echo $this->Form->input('GalleryImage.dir', array('type' => 'hidden'));
		    echo $this->Form->input('GalleryImage.mimetype', array('type' => 'hidden'));
		    echo $this->Form->input('GalleryImage.filesize', array('type' => 'hidden'));
		    echo $this->Form->input('Gallery.id', array('type' => 'hidden'));
			
			# move these variables to the controller and define them there in their own function
			if (defined('__BANNERS_POSITION_'.$this->request->data['Banner']['banner_position_id'].'_WIDTH') &&
					defined('__BANNERS_POSITION_'.$this->request->data['Banner']['banner_position_id'].'_HEIGHT')) {
				echo $this->Form->input('Gallery.medium_width', array('type' => 'hidden', 'value' => 
						constant('__BANNERS_POSITION_'.$this->request->data['Banner']['banner_position_id'].'_WIDTH')));
				echo $this->Form->input('Gallery.medium_height', array('type' => 'hidden', 'value' => 
						constant('__BANNERS_POSITION_'.$this->request->data['Banner']['banner_position_id'].'_HEIGHT')));
			} else {
				# temporary holder for defaults
				echo  $this->Form->input('Gallery.medium_width', array('type' => 'hidden', 'value' => '284'));
				echo $this->request->data['Banner']['banner_position_id'] == 1 ? $this->Form->input('Gallery.medium_height', array('type' => 'hidden', 'value' => '125')) : $this->Form->input('Gallery.medium_height', array('type' => 'hidden', 'value' => '260'));
			}
						
		?>
		</fieldset>
	
	<?php
		$options = array(
			'name' => 'Submit',
			'label' => 'Submit',
			'class' => 'submit1'
		);
		echo $this->Form->end($options); 
	?>
</div>