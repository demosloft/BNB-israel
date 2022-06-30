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
if( !class_exists( 'ReduxFramework_wpestate_set_search' ) ) {

    /**
     * Main ReduxFramework_wpestate_set_search class
     *
     * @since       1.0.0
     */
    class ReduxFramework_wpestate_set_search {
    
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
            $adv_search_fields_no               =   floatval( wprentals_get_option('wp_estate_adv_search_fields_no') );
            if( $this->field['name'] =='wprentals_admin[wpestate_set_search_half_map]' ){
                $adv_search_fields_no               =   floatval( wprentals_get_option('wp_estate_adv_search_fields_no_half_map') );
            }
            
            $adv_search_what                    =   wprentals_get_option('wp_estate_adv_search_what','');
            $adv_search_how                     =   wprentals_get_option('wp_estate_adv_search_how','');
            $adv_search_label                   =   wprentals_get_option('wp_estate_adv_search_label','');
            $adv_search_icon                    =   wprentals_get_option('wp_estate_search_field_label','');

    
            $name= $this->field['name'] . $this->field['name_suffix'] . '[unit_field_value][]';
       
      
            print  '<div class="custom_fields_wrapper">';
            print '<div class="field_row">
            <div class="field_item"><strong>'.__('Place in advanced search form','wprentals-core').'</strong></div>
            <div class="field_item"><strong>'.__('Search field','wprentals-core').'</strong></div>
            <div class="field_item"><strong>'.__('How it will compare','wprentals-core').'</strong></div>
            <div class="field_item"><strong>'.__('Label on Front end','wprentals-core').'</strong></div>
            <div class="field_item"><strong>'.__('Icon ','wprentals-core').'</strong></div>
            </div>';
            $to_send='';
            while($i< $adv_search_fields_no ){
           

                print '<div class="field_row">
                        <div class="field_item">'.__('Spot no ','wprentals-core').($i+1).'</div>';
            
                        print '
                        <div class="field_item">
                            <select id="adv_search_what'.$i.'" name="' . $this->field['name'] . $this->field['name_suffix'] . '[adv_search_what][]"  >';
                           
                                if(isset($this->value['adv_search_what'][$i])){
                                    $to_send=$this->value['adv_search_what'][$i];
                                }
                                print   wpestate_show_advanced_search_options_redux( $to_send );
                            print'</select>
                        </div>';
                
                
                        print '<div class="field_item">
                            <select id="adv_search_how'.$i.'"  name="' . $this->field['name'] . $this->field['name_suffix'] . '[adv_search_how][]"  >';
                                $to_send_how='';
                                if(isset($this->value['adv_search_how'][$i])){
                                    $to_send_how=$this->value['adv_search_how'][$i];
                                }
                                print  wpestate_show_advanced_search_how_redux($to_send_how);
                            print '</select>
                        </div>';
                
                            
                        $to_send_label='';
                        if(isset($this->value['adv_search_label'][$i])){
                           $to_send_label=  $this->value['adv_search_label'][$i];
                        }
                        print '<div class="field_item"><input type="text" id="adv_search_label'.$i.'"  name="' . $this->field['name'] . $this->field['name_suffix'] . '[adv_search_label][]"  value="'.$to_send_label.'"></div>';

                
                        $to_send_label2='';
                        if(isset($this->value['search_field_label'][$i])){
                           $to_send_label2=  $this->value['search_field_label'][$i];
                        }
                        
                        print ' <div class="field_item">
                            <div class="form-group">
                                <div class="input-group">
                                    <input data-placement="bottomRight"  name="' . $this->field['name'] . $this->field['name_suffix'] . '[search_field_label][]"   class="form-control icp icp-auto" value="'. $to_send_label2.'"
                                           type="text"/>
                                    <div class="input-group-addon searchf">';
                                    if($to_send_label2!=''){
                                        print '<i class="'.$to_send_label2.'"></i>';
                                    }

                                    print'</div>
                                </div>
                            </div>          
                        </div>';
                
                  
                print '</div>';
              
                                        
                $i++;
            }
            print'</div>';
            wpestate_font_awesome_list();
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
                $this->extension_url . 'field_wpestate_set_search.js', 
                array( 'jquery' ),
                time(),
                true
            );

            wp_enqueue_style(
                'redux-field-icon-select-css', 
                $this->extension_url . 'field_wpestate_set_search.css',
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
