<?php
/**
 * Redux Framework is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Redux Framework is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Redux Framework. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package     ReduxFramework
 * @author      Dovy Paukstys
 * @version     3.1.5
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

// Don't duplicate me!
if( !class_exists( 'ReduxFramework_wpestate_custom_fields_list' ) ) {

    /**
     * Main ReduxFramework_wpestate_custom_fields_list class
     *
     * @since       1.0.0
     */
    class ReduxFramework_wpestate_custom_fields_list {
    
        /**
         * Field Constructor.
         *
         * Required - must call the parent constructor, then assign field and value to vars, and obviously call the render field function
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        function __construct( $field , $value , $parent ) {
        
            
            $this->parent = $parent;
            $this->field = $field;
            $this->value = $value;

            if ( empty( $this->extension_dir ) ) {
                $this->extension_dir = trailingslashit( str_replace( '\\', '/', dirname( __FILE__ ) ) );
                $this->extension_url = site_url( str_replace( trailingslashit( str_replace( '\\', '/', ABSPATH ) ), '', $this->extension_dir ) );
            }    

            // Set default args for this field to avoid bad indexes. Change this to anything you use.
            $defaults = array(
                'options'           => array(),
                'stylesheet'        => '',
                'output'            => true,
                'enqueue'           => true,
                'enqueue_frontend'  => true
            );
            $this->field = wp_parse_args( $this->field, $defaults );            
        
        }

        /**
         * Field Render Function.
         *
         * Takes the vars and outputs the HTML for the field in the settings
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
     
    
        public function render() {
            $i=0;

            $current_fields='';


            if( !empty(     $this->value)){ 
                if(isset($this->value['add_field_name'])):
                    while($i< count( $this->value['add_field_name']) ){
                        $name_drop=$this->field['name'] . $this->field['name_suffix'] . '[add_field_type][]';
                        $current_fields.='
                        <div class=field_row>
                            <div    class="field_item"><strong>'.__('Field Name','wprentals-core').'</strong></br>'
                                    . '<input  type="text"  name="' . $this->field['name'] . $this->field['name_suffix'] . '[add_field_name][]"   value="'.stripslashes( $this->value['add_field_name'][$i] ).'"  ></div>
                            <div    class="field_item"><strong>'.__('Field Label','wprentals-core').'</strong></br>'
                                    . '<input  type="text"  name="' . $this->field['name'] . $this->field['name_suffix'] . '[add_field_label][]"  value="'.stripslashes( $this->value['add_field_label'][$i] ).'"  ></div>
                   
                            <div    class="field_item"><strong>'.__('Field Order','wprentals-core').'</strong></br>'
                                    . '<input  type="text"   name="' . $this->field['name'] . $this->field['name_suffix'] . '[add_field_order][]" value="'.stripslashes( $this->value['add_field_order'][$i] ) .'"></div>     
                            
                            <div    class="field_item"><strong>'.__('Field Type','wprentals-core').'</strong></br>'
                                    .wpestate_fields_type_select_redux( $name_drop,$this->value['add_field_type'][$i]).'</div>

                            <div    class="field_item"><strong>'.__('Dropdown values','wprentals-core').'</strong></br>'
                                    . '<textarea  name="' . $this->field['name'] . $this->field['name_suffix'] . '[add_dropdown_order][]" >';

                            if( isset($this->value['add_dropdown_order'][$i]) ){
                                   $current_fields.= stripslashes( $this->value['add_dropdown_order'][$i]);
                            } 
//                                 

                            $current_fields.='</textarea></div>     

                            <a class="deletefieldlink" href="#">'.esc_html__( 'delete','wprentals-core').'</a>
                        </div>';    





                        $i++;
                    }
                endif;
            }
   
    
                
        
            print '  
            <div id="custom_fields_wrapper">
                '.$current_fields.'
            </div>';
            
            print '  
            <div class="add_curency">
                <div class="cur_explanations">' . __('Field name', 'wprentals-core') . '</div>
                <input  type="text" id="field_name"  name="field_name"   value="" size="40"/>
            
                <div class="cur_explanations">' . __('Field Label', 'wprentals-core') . '</div>
                <input  type="text" id="field_label"  name="field_label"   value="" size="40" />
            
                <div class="cur_explanations">' . __('Field Type', 'wprentals-core') . '</div>
                <select id="field_type" name="field_type">
                    <option value="short text">short text</option>
                    <option value="long text">long text</option>
                    <option value="numeric">numeric</option>
                    <option value="date">date</option>
                    <option value="dropdown">dropdown</option>
                </select>
                

                <div class="cur_explanations">' . __('Dropdown values separated by "," (only for dropdown field type)','wprentals-core') . '</div>
                <textarea cols="45" id="drodown_values"  name="drodown_values"></textarea>
                    

                <div class="cur_explanations">'.__(' Order in listing page','wprentals-core').'</div>
                <input  type="text" id="field_order"  name="field_order"   value="" size="40" />   
                    
            </div>                        
            <a href="#" id="add_field2">' . __(' click to add field', 'wprentals-core') . '</a>';
           

        }
    
        /**
         * Enqueue Function.
         *
         * If this field requires any scripts, or css define this function and register/enqueue the scripts/css
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */
        public function enqueue() {
            wp_enqueue_script(
                'redux-field-icon-select-js', 
                $this->extension_url . 'field_wpestate_custom_fields_list.js', 
                array( 'jquery' ),
                time(),
                true
            );

            wp_enqueue_style(
                'redux-field-icon-select-css', 
                $this->extension_url . 'field_wpestate_custom_fields_list.css',
                time(),
                true
            );
        
        }
        
        /**
         * Output Function.
         *
         * Used to enqueue to the front-end
         *
         * @since       1.0.0
         * @access      public
         * @return      void
         */        
        public function output() {

            if ( $this->field['enqueue_frontend'] ) {

            }
            
        }        
        
    }
}
