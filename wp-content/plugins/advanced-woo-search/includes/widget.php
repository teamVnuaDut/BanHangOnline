<?php
/*
 * Initialized plugins widget
 */

add_action( 'widgets_init', 'aws_register_widget' );
 
function aws_register_widget() {
    register_widget("AWS_Widget");
}

class AWS_Widget extends WP_Widget {

    /*
     * Constructor
     */
    function __construct() {
        $widget_ops = array( 'description' => __('Advanced WooCommerce search widget', 'advanced-woo-search' ) );
        $control_ops = array( 'width' => 400 );
        parent::__construct( false, __( '&raquo; AWS Widget', 'advanced-woo-search' ), $widget_ops, $control_ops );
    }

    /*
     * Display widget
     */
    function widget( $args, $instance ) {
        extract( $args );

        $title = apply_filters( 'widget_title',
            ( ! empty( $instance['title'] ) ? $instance['title'] : '' ),
            $instance,
            $this->id_base
        );

        echo $before_widget;

        if ( $title ) {
            echo $before_title;
            echo $title;
            echo $after_title;
        }

        // Generate search form markup
        echo AWS()->markup();

        echo $after_widget;
    }

    /*
     * Update widget settings
     */
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $params = array( 'title' );
        foreach ( $params as $k ) {
            $instance[$k] = strip_tags( $new_instance[$k] );
        }
        return $instance;
    }

    /*
     * Widget settings form
     */
    function form( $instance ) {
        global $shortname;
        $defaults = array(
            'title' => __( 'Search...', 'advanced-woo-search' )
        );
        $instance = wp_parse_args( (array) $instance, $defaults );
        ?>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>"><?php esc_html_e( 'Title:', 'advanced-woo-search' ); ?></label>
            <input type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>">
        </p>

    <?php
    }
}
?>