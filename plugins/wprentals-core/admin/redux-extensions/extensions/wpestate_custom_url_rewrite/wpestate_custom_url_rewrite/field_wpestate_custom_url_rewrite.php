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
if( !class_exists( 'ReduxFramework_wpestate_custom_url_rewrite' ) ) {

    /**
     * Main ReduxFramework_wpestate_custom_url_rewrite class
     *
     * @since       1.0.0
     */
    class ReduxFramework_wpestate_custom_url_rewrite {

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
            $rewrites = $this->value;


            if(empty($rewrites)){
               $rewrites=array('properties','listings','action','city','area','features','status','owners');

            }

            for ($i = 0; $i <= 7; $i++){
                if(  !isset( $rewrites[$i])  ){
                     $rewrites[$i]='';
                }
            }
            $i=0;
            $links_to_rewrite = array(
                    'property_page'         =>  array(
                                                    $rewrites[0],
                                                    esc_html__('Property Page','wprentals-core')
                                                ),

                    'property_category'     =>  array(
                                                     $rewrites[1],
                                                    esc_html__('Property Categories Page','wprentals-core')
                                                ),

                    'property_action_category'     =>  array(
                                                    $rewrites[2],
                                                    esc_html__('Property Action Category Page','wprentals-core')
                                                ),
                    'property_city'     =>  array(
                                                    $rewrites[3],
                                                    esc_html__('Property City Page','wprentals-core')
                                                ),

                    'property_area'     =>  array(
                                                    $rewrites[4],
                                                    esc_html__('Property Area Page','wprentals-core')
                                                ),
                    'property_features'     =>  array(
                                                     $rewrites[5],
                                                    esc_html__('Property Features','wprentals-core')
                                                ),
                    'property_status'     =>  array(
                                                     $rewrites[6],
                                                    esc_html__('Property Status','wprentals-core')
                                                ),

                    'agent_page'     =>  array(
                                                     $rewrites[7],
                                                    esc_html__('Owner Page','wprentals-core')
                                                ),


                );



                    foreach ($links_to_rewrite as $key=>$value){
                        $current_fields .=  '<div class="label_option_row">'.$value[1].'</div>';
                        $current_fields .=  '<div class="option_row_explain">'.esc_html__('Custom link for ','wprentals-core').' '.$value[1].'</div> ';
                        $current_fields .=  esc_url( home_url('/') ).'<input  type="text"  name="' . $this->field['name'] . $this->field['name_suffix'] . '[]"   value="'.$rewrites[$i].'"  ></div>';
                        $current_fields .= '</div>';
                        $i++;
                    }
                //endif;




            print $current_fields;




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
                $this->extension_url . 'field_wpestate_custom_url_rewrite.js',
                array( 'jquery' ),
                time(),
                true
            );

            wp_enqueue_style(
                'redux-field-icon-select-css',
                $this->extension_url . 'field_wpestate_custom_url_rewrite.css',
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
