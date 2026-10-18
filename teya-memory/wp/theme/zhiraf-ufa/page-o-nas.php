<?php
/**
 * Template Name: О нас
 * Template Post Type: page
 *
 * @package Zhiraf_Ufa
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<main id="primary" class="site-main site-main--about">
	<section class="page-hero page-hero--editorial">
		<div class="container">
			<h1><?php esc_html_e( 'О турагентстве «Жираф» в Уфе', 'zhiraf-ufa' ); ?></h1>
			<p class="lead"><?php esc_html_e( 'Семейное агентство с апреля 2011 года. Подбираем туры с вылетом из Уфы честно — как для друзей.', 'zhiraf-ufa' ); ?></p>
		</div>
	</section>
	<section class="section">
		<div class="container about-grid">
			<div class="prose">
				<h2><?php esc_html_e( 'История', 'zhiraf-ufa' ); ?></h2>
				<p><?php esc_html_e( 'Основатели Зульфия и Ксения открыли офис в 2011 году. В 2014 к команде присоединились Лия, Алина и Рафаэль. Сегодня «Жираф» — это менеджеры, которые сопровождают клиента от первой заявки до возвращения.', 'zhiraf-ufa' ); ?></p>
				<h2><?php esc_html_e( 'Ценности', 'zhiraf-ufa' ); ?></h2>
				<ul>
					<li><?php esc_html_e( 'Прозрачность — объясняем, за что платите', 'zhiraf-ufa' ); ?></li>
					<li><?php esc_html_e( 'Уважение к времени — готовим варианты, а не «100 отелей на почту»', 'zhiraf-ufa' ); ?></li>
					<li><?php esc_html_e( 'Локальность — офис в Уфе, можно приехать лично', 'zhiraf-ufa' ); ?></li>
				</ul>
				<p><a href="<?php echo esc_url( home_url( '/kontakty/' ) ); ?>"><?php esc_html_e( 'Контакты и реквизиты', 'zhiraf-ufa' ); ?></a> · <a href="<?php echo esc_url( home_url( '/tour/' ) ); ?>"><?php esc_html_e( 'Каталог туров', 'zhiraf-ufa' ); ?></a></p>
			</div>
			<div class="about-visual">
				<?php zhiraf_ufa_img( 'team-travel.jpg', __( 'Команда за работой', 'zhiraf-ufa' ), 'about-img', true ); ?>
				<?php zhiraf_ufa_img( 'office-ufa.jpg', __( 'Офис', 'zhiraf-ufa' ), 'about-img', true ); ?>
			</div>
		</div>
	</section>
	<section class="section section--sand">
		<div class="container">
			<h2><?php esc_html_e( 'Команда', 'zhiraf-ufa' ); ?></h2>
			<div class="team-grid">
				<div class="team-card"><h3><?php esc_html_e( 'Рафаэль', 'zhiraf-ufa' ); ?></h3><p><?php esc_html_e( 'Директор', 'zhiraf-ufa' ); ?></p></div>
				<div class="team-card"><h3><?php esc_html_e( 'Зульфия', 'zhiraf-ufa' ); ?></h3><p><?php esc_html_e( 'Основатель, зам. директора', 'zhiraf-ufa' ); ?></p></div>
				<div class="team-card"><h3><?php esc_html_e( 'Альфия', 'zhiraf-ufa' ); ?></h3><p><?php esc_html_e( 'Менеджер', 'zhiraf-ufa' ); ?></p></div>
				<div class="team-card"><h3><?php esc_html_e( 'Гульнара', 'zhiraf-ufa' ); ?></h3><p><?php esc_html_e( 'Менеджер', 'zhiraf-ufa' ); ?></p></div>
			</div>
		</div>
	</section>
	<section class="section cta-strip">
		<div class="container cta-strip__inner">
			<p><?php esc_html_e( 'Готовы обсудить поездку?', 'zhiraf-ufa' ); ?></p>
			<button type="button" class="btn btn--primary" data-quiz-open><?php esc_html_e( 'Подобрать тур', 'zhiraf-ufa' ); ?></button>
		</div>
	</section>
	<?php
	while ( have_posts() ) {
		the_post();
		if ( get_the_content() ) {
			echo '<section class="section"><div class="container prose">';
			the_content();
			echo '</div></section>';
		}
	}
	?>
</main>
<?php
get_footer();
