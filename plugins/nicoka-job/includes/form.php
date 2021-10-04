<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class to generate Form
 *
 * @package Nicoka Job
 * @since 1.0.0
 */
class NicokaForm {


	/**
	 * The single instance of the class.
	 *
	 * @var self
	 * @since  1.0.0
	 */
	private static $_instance = null;

	/**
	 * Allows for accessing single instance of class. Class should only be constructed once per call.
	 *
	 * @since  1.0.0
	 * @static
	 * @return self Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
    }
    
    /**
	 * Constructor.
	 */
	public function __construct() {
		global $wp_version;
	}

    /**
     * Text area input field.
     *
     * @param array  $option
     * @param array  $attributes
     * @param mixed  $value
     * @param string $placeholder
     */
    protected function input_textarea( $option, $attributes, $value, $required, $placeholder ) {
        ?>
        <fieldset>
            <label for='<?php echo esc_attr( $option['name'] ); ?>' <?php if (isset($option['label_hidden']) && $option['label_hidden']) echo "class='hide'"; ?>><?php echo $placeholder; ?></label>
            <textarea
            id="setting-<?php echo esc_attr( $option['name'] ); ?>"
            class="large-text"
            cols="50"
            rows="3"
            name="<?php echo esc_attr( $option['name'] ); ?>"
            ><?php echo esc_textarea( $value ); ?></textarea>
        </fieldset>

        <?php

        if ( ! empty( $option['desc'] ) ) {
            echo ' <p class="description">' . wp_kses_post( $option['desc'] ) . '</p>';
        }
    }

    /**
     * To check value 
     *
     * @param [type] $helper
     * @param [type] $current
     * @param [type] $echo
     * @param [type] $type
     * @return void
     */
    private function __checked_selected_helper( $helper, $current, $echo, $type ) {
        if ( (string) $helper === (string) $current )
            $result = " $type='$type'";
        else
            $result = '';
    
        if ( $echo )
            echo $result;
    
        return $result;
    }

	/**
	 * Checkbox input field.
	 *
	 * @param array  $option
	 * @param array  $attributes
	 * @param mixed  $value
	 * @param string $ignored_placeholder
	 */
	protected function input_checkbox( $option, $attributes, $value, $required) {
        ?>
         <fieldset class='fieldset-<?php echo esc_attr( $option['name'] ); ?>'>
		<label>
		<input type="hidden" name="<?php echo esc_attr( $option['name'] ); ?>" value="0" />
		<input
			id="<?php echo esc_attr( $option['name'] ); ?>"
			name="<?php echo esc_attr( $option['name'] ); ?>"
			type="checkbox"
			value="1"
			<?php
			echo implode( ' ', $attributes ) . ' '; // WPCS: XSS ok.
            $this->__checked_selected_helper( '1', $value, true, 'checked' );
             if($required) echo ' required';
			?>
		/> <?php echo wp_kses_post( $option['cb_label'] ); ?></label>
		<?php
		if ( ! empty( $option['desc'] ) ) {
			echo ' <p class="description">' . wp_kses_post( $option['desc'] ) . '</p>';
        }
        echo '</fieldset>';
	}

	/**
	 * Number input field.
	 *
	 * @param array  $option
	 * @param array  $attributes
	 * @param mixed  $value
	 * @param string $placeholder
	 */
	protected function input_number( $option, $attributes, $value, $required, $placeholder ) {
        ?>
        <fieldset class='fieldset-<?php echo esc_attr( $option['name'] ); ?>'>
        <label for='<?php echo esc_attr( $option['name'] ); ?>' <?php if (isset($option['label_hidden']) && $option['label_hidden']) echo "class='hide'"; ?>><?php echo esc_attr( $option['name'] ); ?></label>
            <div class="field <?php if($required) echo 'required-field'; ?>">
                <input
                    id="<?php echo esc_attr( $option['name'] ); ?>"
                    class="input-text"
                    type="number"
                    name="<?php echo esc_attr( $option['name'] ); ?>"
                    value="<?php echo esc_attr( $value ); ?>"
                    <?php if($required) echo ' required'; ?>
                    <?php
                    echo implode( ' ', $attributes ) . ' '; // WPCS: XSS ok.
                     if ($placeholder) echo ' placeholder=\''.$placeholder.'\'';  // WPCS: XSS ok.
                    ?>
                />
            </div>
        </fieldset>
		<?php

		if ( ! empty( $option['desc'] ) ) {
			echo ' <p class="description">' . wp_kses_post( $option['desc'] ) . '</p>';
		}
    }
    
	/**
	 * Text input field.
	 *
	 * @param array  $option
	 * @param array  $attributes
	 * @param mixed  $value
	 * @param string $placeholder
	 */
	protected function input_text( $option, $attributes, $value, $required, $placeholder) {?>
        <fieldset class='fieldset-<?php echo esc_attr( $option['name'] ); ?>'>
        <label for='<?php echo esc_attr( $option['name'] ); ?>' <?php if (isset($option['label_hidden']) && $option['label_hidden']) echo "class='hide'"; ?>><?php echo $option['name']; ?></label>
            <div class="field <?php if($required) echo 'required-field'; ?>">
                <input
                    id="<?php echo esc_attr( $option['name'] ); ?>"
                    class="input-text"
                    type="<?php if($option['name'] == 'email') {echo 'email' ;}else{ echo 'text'; }?>"
                    name="<?php echo esc_attr( $option['name'] ); ?>"
                    value="<?php echo esc_attr( $value ); ?>"
                    <?php if($required) echo ' required'; ?>
                    <?php
                    echo implode( ' ', $attributes ) . ' '; // WPCS: XSS ok.
                     if($placeholder) echo ' placeholder=\''.$placeholder.'\'';  // WPCS: XSS ok.
                    ?>
                />
            </div>
            <?php
                if ( ! empty( $option['desc'] ) ) {
                    echo ' <p class="description">' . wp_kses_post( $option['desc'] ) . '</p>';
                }
		    ?>
        </fieldset>
		<?php
    }
    
    /**
	 * Text input field.
	 *
	 * @param array  $option
	 * @param array  $attributes
	 * @param mixed  $value
	 * @param string $placeholder
	 */
	protected function input_hidden( $option, $attributes, $value) {
        ?>
                <input
                    id="<?php echo esc_attr( $option['name'] ); ?>"
                    type="hidden"
                    value="<?php echo esc_attr( $value ); ?>"
                    <?php
                    echo implode( ' ', $attributes ) . ' '; // WPCS: XSS ok.
                    ?>
                />
		<?php 
	}

	/**
	 * File upload input field.
	 *
	 * @param array  $option
	 * @param array  $attributes
	 * @param mixed  $value
	 * @param string $placeholder
	 */
	protected function input_file( $option, $attributes, $value, $required, $placeholder) {
        ?>
        <fieldset class='fieldset-<?php echo esc_attr( $option['name'] ); ?>'>
        <label for='<?php echo esc_attr( $option['name'] ); ?>' <?php if (isset($option['label_hidden']) && $option['label_hidden']) echo "class='hide'"; ?>><?php echo $placeholder; ?></label>
            <div class="field <?php if($required) echo 'required-field'; ?>">
                <input
                    id="<?php echo esc_attr( $option['name'] ); ?>"
                    class="input-text"
                    type="file"
                    name="<?php echo esc_attr( $option['name'] ); ?>"
                    value="<?php echo esc_attr( $value ); ?>"
                    <?php if($required) echo ' required'; ?>
                    <?php
                    echo implode( ' ', $attributes ) . ' '; // WPCS: XSS ok.
                     if($placeholder) echo ' placeholder='.$placeholder;  // WPCS: XSS ok.
                    ?>
                />
            </div>
        </fieldset>
		<?php

		if ( ! empty( $option['desc'] ) ) {
			echo ' <p class="description">' . wp_kses_post( $option['desc'] ) . '</p>';
		}
	}
    function stripslashes_deep($value)
    {
        $value = is_array($value) ?
                    array_map('stripslashes_deep', $value) :
                    stripslashes($value);
    
        return $value;
    }
    
    /**
     * Private helper function for checked, selected, disabled and readonly.
     *
     * Compares the first two arguments and if identical marks as $type
     *
     * @access private
     *
     * @param mixed  $helper  One of the values to compare
     * @param mixed  $current (true) The other value to compare if not just true
     * @param bool   $echo    Whether to echo or just return the string
     * @param string $type    The type of checked|selected|disabled|readonly we are doing
     * @return string html attribute or empty string
     */
    private function checked_selected_helper( $helper, $current, $echo, $type ) {

        if(is_array($helper)){
            if(in_array($current,$this->stripslashes_deep($helper))){
            $result = " $type='$type'";
        }
        else
            $result = '';
    }else{
        if ( (string) $helper === (string) $current )
            $result = " $type='$type'";
        else
            $result = '';
    }
        if ( $echo )
            echo $result;

        return $result;
    
    }

	/**
	 * Select input field.
	 *
	 * @param array  $option
	 * @param array  $attributes
	 * @param mixed  $value
	 * @param string $ignored_placeholder
	 */
	protected function input_select($option, $attributes, $value, $required, $placeholder=null) {
    
        if (isset($option['select2'])){ ?>
        <script type="text/javascript">
            function formatFiliere(item) {
                opt = jQuery(item.element);
                og = opt.closest('optgroup').attr('label').toUpperCase();
                return og+' | '+item.text;
            };

            function modelMatcher (params, data) {
                data.parentText = data.parentText || "";

                // Always return the object if there is nothing to compare
                if (jQuery.trim(params.term) === '') {
                    return data;
                }

                // Do a recursive check for options with children
                if (data.children && data.children.length > 0) {
                    // Clone the data object if there are children
                    // This is required as we modify the object to remove any non-matches
                    var match = jQuery.extend(true, {}, data);

                    // Check each child of the option
                    for (var c = data.children.length - 1; c >= 0; c--) {
                        var child = data.children[c];
                        child.parentText += data.parentText + " " + data.text;

                        var matches = modelMatcher(params, child);

                        // If there wasn't a match, remove the object in the array
                        if (matches == null) {
                            match.children.splice(c, 1);
                        }
                    }

                    // If any children matched, return the new object
                    if (match.children.length > 0) {
                     return match;
                    }

                    // If there were no matching children, check just the plain object
                    return modelMatcher(params, match);
                }

                // If the typed-in term matches the text of this term, or the text from any
                // parent term, then it's a match.
                var original = (data.parentText + ' ' + data.text).toUpperCase();
                var term = params.term.toUpperCase();


                // Check if the text contains the term
                if (original.indexOf(term) > -1) {
                    return data;
                }

                // If it doesn't contain the term, don't return anything
                return null;
            }

            jQuery(document).ready(function() {
                
                var Events_ = new Events(jQuery);

                var sel = jQuery('<?php echo '#'.esc_attr( $option['name'] ); ?>').select2({templateSelection: formatFiliere, matcher:modelMatcher
                        <?php if(isset($placeholder)){ echo ',placeholder: \''.$placeholder.'\''; }  ?>
                }).on('select2:select', function () {
                   
                        var result = jQuery(this).select2('val');
                     
                        Events_.getFilieres(result, function(data){
                            Events_.createElements(data);
                        });

                }).on('select2:unselect', function(elem){
                    var id_ = JSON.parse(elem.params.data.id);
                    Events_.remove(id_.loc);
                });
                
                jQuery('<?php echo '#'.esc_attr( $option['name'] ); ?>').trigger({type: 'select2:select'});
                
            });
            
        </script>
        <?php } ?>
        <fieldset class='fieldset-<?php echo esc_attr( $option['name'] ); ?> '>
        
            <label for='<?php echo esc_attr($option['name']); ?>' <?php if (isset($option['label_hidden']) && $option['label_hidden']) echo "class='hide'"; ?>><?php echo esc_attr($option['name']); ?></label>
            <div class="field <?php if($required) echo 'required-field'; ?>">
                <select
                    <?php if($required) echo 'required '; ?>
                    id="<?php echo esc_attr($option['name']); ?>" 
                    name="<?php echo esc_attr($option['name']); ?><?php if (isset($option['select2'])) echo '[]'; ?>"
                    <?php
                    echo implode(' ', $attributes); // WPCS: XSS ok.
                    if(empty($option['options']) && $value){
                        echo 'data-selected='.$value;
                    }
                    ?>>
                <?php
                $lastGroup = null;

                if ($placeholder && !isset($option['select2']))
                    echo '<option value="" class="placeholder" disabled selected>' . esc_html($placeholder) . '</option>';
                $lastKey = end(array_keys($option['options']));
                foreach ((array)$option['options'] as $key => $name) {
                    if (is_array($name) && isset($name['group']))
                    {
                        if ($lastGroup !==  $name['group']){
                            if($lastGroup <> null) echo '</optgroup>';
                            $lastGroup = $name['group'];
                            echo '<optgroup label="'.$name['group'].'">';
                        }
                        $values = ["loc"=>$name['location'], "val" => esc_attr($key), "loclabel" => $name['group']];

                        echo '<option value="'.esc_attr(json_encode($values)).'" '. $this->checked_selected_helper($value ,json_encode($values), false, 'selected').'>' . esc_html($name['name']) . '</option>';
                       
                        if ($lastKey == $key)  echo '</optgroup>';

                    }  else
                        echo '<option value="' . esc_attr($key + 1) . '" '. $this->checked_selected_helper($value, $key + 1, false, 'selected').'>' . esc_html($name) . '</option>';
                }
                ?>
                </select>
            </div>
        </fieldset>
		<?php
		if  (!empty( $option['desc'] )) {
			echo '<p class="description">' . wp_kses_post( $option['desc'] ) . '</p>';
		}
    }

    /**
     * Output inputs in HTML
     *
     * @param array $form array of configuration inputs 
     * @example
     * @return void
     * 
     */
    public function outputForm($form){
        foreach ((array)$form as $input){
            $required = isset($input['required']) ? $input['required'] : false;
            $attributes = isset($input['attributes']) ? $input['attributes'] : [];
            $value = isset($input['value']) ? $input['value'] : null;
            $placeholder =  isset($input['placeholder']) ? $input['placeholder'] : null;
            switch ($input['type']){
                case 'text':
                    $this->input_text($input['option'], $attributes, $value, $required, $placeholder);
                    break;
                case 'textarea':
                    $this->input_textarea($input['option'], $attributes, $value, $required, $placeholder);
                    break;
                case 'number':
                    $this->input_number($input['option'], $attributes, $value, $required, $placeholder);
                    break;
                case 'file':
                    $this->input_file($input['option'], $attributes, $value, $required, $placeholder);
                    break;
                case 'select':
                    $this->input_select($input['option'], $attributes, $value, $required, $placeholder);
                    break;
                case 'select2':
                    $input['option']['select2'] = true;
                    $this->input_select($input['option'], $attributes, $value, $required, $placeholder);
                    break;
                case 'hidden':
                    $this->input_hidden($input['option'], $attributes, $value, $required);
                    break;
                case 'checkbox':
                    $this->input_checkbox($input['option'], $attributes, $value, $required);
                    break;
            }
        }
    }
}

NicokaForm::instance();