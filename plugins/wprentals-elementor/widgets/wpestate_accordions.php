<?php
namespace ElementorWpRentals\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class WpRentals_Accordions extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'WpRentals_Accordions';
	}

        public function get_categories() {
		return [ 'wprentals' ];
	}


	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'WpRentals Accordions', 'rentals-elementor' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-accordion';
	}

	public function get_keywords() {
		return [ 'accordions', 'accordion', 'toggle' ];
	}


	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
	return [ '' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
         public function elementor_transform($input){
            $output=array();
            if( is_array($input) ){
                foreach ($input as $key=>$tax){
                    $output[$tax['value']]=$tax['label'];
                }
            }
            return $output;
        }




        protected function register_controls() {
		$this->start_controls_section(
			'section_accordions',
			[
				'label' => __( 'Elements', 'elementor' ),
			]
		);

		$repeater =  new \Elementor\Repeater();

		$repeater->add_control(
			'accordion_title',
			[
				'label' => __( 'Title & Description', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Accordion Title', 'elementor' ),
				'placeholder' => __( 'Accordion Title', 'elementor' ),
				'label_block' => true,
			]
		);

                $repeater->add_control(
			'accordion_icon',
			[
				'label' => __( 'Icon', 'elementor' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
				'placeholder' => __( 'Accordion Icon', 'elementor' ),
				'label_block' => true,
			]
		);


		$repeater->add_control(
                    'template_accordion',
                    [
                        'type' => Controls_Manager::SELECT,
                        'options' => $this->wpestate_drop_posts('elementor_library'),
                        'multiple' => false,
                        'label' =>   esc_html__('Template', 'elementor'),
                        'label_block' => true,

                    ]
                );
		$repeater->add_control(
			'accordion_content',
			[
				'label' => __( 'Content', 'elementor' ),
				'default' => __( 'Accordion Content', 'elementor' ),
				'placeholder' => __( 'Accordion Content', 'elementor' ),
				'type' => Controls_Manager::WYSIWYG,
				'show_label' => false,
				'dynamic' => [
					'active' => false,
				],
			]
		);




		$this->add_control(
			'accordions',
			[
				'label' => __( 'accordion Items', 'elementor' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'accordion_title' => __( 'Item #1', 'elementor' ),
						'accordion_content' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'elementor' ),
					],
					[
						'accordion_title' => __( 'Item #2', 'elementor' ),
						'accordion_content' => __( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'elementor' ),
					],
				],
				'title_field' => '{{{ accordion_title }}}',
			]
		);

		$this->add_control(
			'view',
			[
				'label' => __( 'View', 'elementor' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => 'traditional',
			]
		);



		$this->end_controls_section();

		$this->start_controls_section(
			'section_accordions_style',
			[
				'label' => __( 'Items', 'elementor' ),
				'accordion' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'navigation_width',
			[
				'label' => __( 'Navigation Width', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => '%',
				],
				'range' => [
					'%' => [
						'min' => 10,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-accordions-wrapper' => 'width: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'type' => 'vertical',
				],
			]
		);

















		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */


        public function wpestate_drop_posts($post_type){
            $args = array(
              'numberposts' => -1,
              'post_type'   => $post_type
            );

            $posts = get_posts( $args );
            $list = array();
            $list['']='none';
            foreach ($posts as $cpost){

                $list[$cpost->ID] = $cpost->post_title;
            }
            return $list;
    }


        public function wpresidence_send_to_shortcode($input){
            $output='';
            if($input!==''){
                $numItems = count($input);
                $i = 0;

                foreach ($input as $key=>$value){
                    $output.=$value;
                    if(++$i !== $numItems) {
                      $output.=', ';
                    }
                }
            }
            return $output;
        }






        protected function render() {
		$accordions = $this->get_settings_for_display( 'accordions' );

                $id_int = substr( $this->get_id_int(), 0, 3 );

		?>

                    <div class="wpestate-elementor-nav-accordions">

				<?php
				foreach ( $accordions as $index => $item ) :
					$accordion_count = $index + 1;

					$accordion_title_setting_key = $this->get_repeater_setting_key( 'accordion_title', 'accordions', $index );

					$this->add_render_attribute( $accordion_title_setting_key, [
						'id' => 'elementor-accordion-title-' . $id_int . $accordion_count,
						'class' => [ 'elementor-accordion-title', 'elementor-accordion-desktop-title' ],
						'data-accordion' => $accordion_count,
						'role' => 'accordion',
						'aria-controls' => 'elementor-accordion-content-' . $id_int . $accordion_count,
					] );

                                        ?>
                                        <div class="panel-group panel-wrapper property-panel" id="<?php echo 'master_'.$item['_id'];?>">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <a data-toggle="collapse" class="collapsed" data-parent="#<?php echo 'master_'.$item['_id'];?>" href="#<?php echo $item['_id'];?>">
                                                        <h4 class="panel-title">
                                                            <?php
                                                                if($item['accordion_icon']['id']!=''){
                                                                    if( 'image/svg+xml' == get_post_mime_type($item['accordion_icon']['id']) ){
                                                                        $term_icon_wp=wp_remote_get($item['accordion_icon']['url']);
                                                                        $term_icon=wp_remote_retrieve_body($term_icon_wp);

                                                                        print $term_icon;
                                                                    }else{
                                                                        print '<img class="wpestate_accordion_icon_elementor" src="'.$item['accordion_icon']['url'].'" alt="icon">';
                                                                    }
                                                                }

                                                                echo $item['accordion_title'];
                                                            ?>

                                                        </h4>

                                                     </a>
                                                </div>
                                                <div id="<?php echo $item['_id'];?>" class="panel-collapse collapse  wprentals_acc_fix ">
                                                  <div class="panel-body">
                                                        <?php

                                                        $acc_content_setting_key = '';
                                                        $acc_title_mobile_setting_key = $this->get_repeater_setting_key( 'tab_title_mobile', 'tabs', $accordion_count );

                                                        $this->add_render_attribute( $acc_content_setting_key, [
                                                                'id' => 'elementor-tab-content-' . $id_int . $accordion_count,
                                                                'class' => [ 'elementor-tab-content', 'elementor-clearfix' ],
                                                                'data-tab' => $accordion_count,
                                                                'role' => 'tabpanel',
                                                                'aria-labelledby' => 'elementor-tab-title-' . $id_int . $accordion_count,
                                                        ] );

                                                        $this->add_render_attribute( $acc_title_mobile_setting_key, [
                                                                'class' => [ 'elementor-tab-title', 'elementor-tab-mobile-title' ],
                                                                'data-tab' => $accordion_count,
                                                                'role' => 'tab',
                                                        ] );



                                                        $this->add_inline_editing_attributes( $acc_content_setting_key, 'advanced' );
                                                            $pluginElementor = \Elementor\Plugin::instance();
                                                            echo  $pluginElementor->frontend->get_builder_content( $item['template_accordion'], true );
                                                            echo  $this->parse_text_editor( $item['accordion_content'] );
                                                        ?>

                                                  </div>
                                                </div>
                                            </div>
                                        </div>








				<?php endforeach; ?>

                    </div>




		<?php
	}







}
