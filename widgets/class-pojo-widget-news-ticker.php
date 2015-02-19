<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Pojo_Widget_News_Ticker extends Pojo_Widget_Base {

	public function __construct() {
		$this->_form_fields = array();

		$this->_form_fields[] = array(
			'id' => 'title',
			'title' => __( 'Title:', 'pojo-news-ticker' ),
			'std' => '',
		);

		$this->_form_fields[] = array(
			'id' => 'category',
			'title' => __( 'Categories:', 'pojo-news-ticker' ),
			'type' => 'multi_taxonomy',
			'taxonomy' => 'category',
			'std' => array(),
		);

		$this->_form_fields[] = array(
			'id' => 'posts_per_page',
			'title' => __( 'Number Posts:', 'pojo-news-ticker' ),
			'std' => get_option( 'posts_per_page' ),
			'filter' => array( &$this, '_valid_number' ),
		);

		$this->_form_fields[] = array(
			'id' => 'ticker_transition_style',
			'title' => __( 'Transition Style:', 'pojo-news-ticker' ),
			'type' => 'select',
			'std' => 'fade',
			'options' => array(
				'fade' => __( 'Fade', 'pojo-news-ticker' ),
				'slide' => __( 'Slide', 'pojo-news-ticker' ),
			),
			'filter' => array( &$this, '_valid_by_options' ),
		);

		$this->_form_fields[] = array(
			'id' => 'ticker_delay',
			'title' => __( 'Delay', 'pojo-news-ticker' ),
			'std' => '2000',
			'filter' => array( &$this, '_valid_number' ),
		);
		
		$this->_form_fields[] = array(
			'id' => 'ticker_pause_hover',
			'title' => __( 'Stop On Hover:', 'pojo-news-ticker' ),
			'type' => 'select',
			'std' => 'on',
			'options' => array(
				'on' => __( 'On', 'pojo-news-ticker' ),
				'off' => __( 'Off', 'pojo-news-ticker' ),
			),
			'filter' => array( &$this, '_valid_by_options' ),
		);
		
		$this->_form_fields[] = array(
			'id' => 'metadata_date',
			'title' => __( 'Date:', 'pojo-news-ticker' ),
			'type' => 'select',
			'std' => 'show',
			'options' => array(
				'show' => __( 'Show', 'pojo-news-ticker' ),
				'hide' => __( 'Hide', 'pojo-news-ticker' ),
			),
			'filter' => array( &$this, '_valid_by_options' ),
		);

		$this->_form_fields[] = array(
			'id' => 'metadata_time',
			'title' => __( 'Time:', 'pojo-news-ticker' ),
			'type' => 'select',
			'std' => 'show',
			'options' => array(
				'show' => __( 'Show', 'pojo-news-ticker' ),
				'hide' => __( 'Hide', 'pojo-news-ticker' ),
			),
			'filter' => array( &$this, '_valid_by_options' ),
		);

		$this->_form_fields[] = array(
			'id' => 'custom_wrapper',
			'title' => __( 'Advanced Options', 'pojo-news-ticker' ),
			'type' => 'button_collapse',
			'mode' => 'start',
		);

		$this->_form_fields[] = array(
			'id' => 'orderby',
			'title' => __( 'Order By:', 'pojo-news-ticker' ),
			'type' => 'select',
			'std' => '',
			'options' => array(
				'' => __( 'Date', 'pojo-news-ticker' ),
				'menu_order' => __( 'Menu Order', 'pojo-news-ticker' ),
				'title' => __( 'Title', 'pojo-news-ticker' ),
				'author' => __( 'Author', 'pojo-news-ticker' ),
				'name' => __( 'Post Slug', 'pojo-news-ticker' ),
				'modified' => __( 'Modified', 'pojo-news-ticker' ),
				'comment_count' => __( 'Comment Count', 'pojo-news-ticker' ),
				'ID' => __( 'Post ID', 'pojo-news-ticker' ),
				'rand' => __( 'Random', 'pojo-news-ticker' ),
				'none' => __( 'None', 'pojo-news-ticker' ),
			),
			'filter' => array( &$this, '_valid_by_options' ),
		);

		$this->_form_fields[] = array(
			'id' => 'order',
			'title' => __( 'Order:', 'pojo-news-ticker' ),
			'type' => 'select',
			'std' => '',
			'options' => array(
				'' => __( 'Descending', 'pojo-news-ticker' ),
				'ASC' => __( 'Ascending', 'pojo-news-ticker' ),
			),
			'filter' => array( &$this, '_valid_by_options' ),
		);

		$this->_form_fields[] = array(
			'id' => 'custom_wrapper',
			'title' => __( 'Custom', 'pojo-news-ticker' ),
			'type' => 'button_collapse',
			'mode' => 'end',
		);

		parent::__construct(
			'pojo_news_ticker',
			__( 'News Ticker', 'pojo-news-ticker' ),
			array( 'description' => __( 'Display ticker posts by category', 'pojo-news-ticker' ), )
		);
	}

	public function widget( $args, $instance ) {
		$instance = wp_parse_args( $instance, $this->_get_default_values() );

		$query_args = array(
			'post_type' => 'post',
			'posts_per_page' => $instance['posts_per_page'],
			'order' => $instance['order'],
			'orderby' => $instance['orderby'],
		);

		if ( ! empty( $instance['category'] ) && is_array( $instance['category'] ) ) {
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => 'category',
					'field' => 'id',
					'terms' => $instance['category'],
					'include_children' => false,
				),
			);
		}
		$recent_posts = new WP_Query( $query_args );

		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) )
			echo $args['before_title'] . $instance['title'] . $args['after_title'];

		if ( ! empty( $instance['category'] ) ) :
			if ( $recent_posts->have_posts() ) :
				$ticker_options = array(
					'delay' => absint( $instance['ticker_delay'] ),
					'effect' => $instance['ticker_transition_style'],
					'effect' => 'typing',
					'pauseHover' => 'on' === $instance['ticker_pause_hover'],
				);
				
				echo '<ul class="pojo-news-ticker" data-ticker_options=\'' . json_encode( $ticker_options ) . '\'>';
				while ( $recent_posts->have_posts() ) : $recent_posts->the_post();
					echo '<li><a href="' . get_permalink() . '">';
					if ( 'show' === $instance['metadata_date'] )
						echo get_the_date();
					
					if ( 'show' === $instance['metadata_time'] )
						echo get_the_time();
					
					the_title();
					echo '</a></li>';
				endwhile;
				echo '</ul>';
				
				wp_reset_postdata();
			else :
				printf( '<p>%s</p>', __( 'No posts found.', 'pojo-news-ticker' ) );
			endif;
		else :
			printf( '<p>%s</p>', __( 'Content not found. Please select a category.', 'pojo-news-ticker' ) );
		endif;

		echo $args['after_widget'];
	}
	
}