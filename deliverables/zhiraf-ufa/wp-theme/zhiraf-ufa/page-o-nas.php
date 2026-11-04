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

$zh_map_url = zhiraf_ufa_office_map_url();
$zh_wa      = zhiraf_ufa_brand_whatsapp_url();
$zh_wa_dir  = zhiraf_ufa_whatsapp_url_with_text(
	$zh_wa,
	__( 'Здравствуйте! Хочу задать вопрос руководителю турагентства «Жираф».', 'zhiraf-ufa' )
);

get_header();
?>
<main id="primary" class="site-main site-main--about">
	<section class="page-hero page-hero--editorial">
		<div class="container">
			<h1><?php esc_html_e( 'О турагентстве «Жираф» в Уфе', 'zhiraf-ufa' ); ?></h1>
			<p class="lead"><?php esc_html_e( 'Семейное турагентство в Уфе с апреля 2011 года. Честно подбираем и организуем пакетные туры с прямым вылетом из Уфы — без фиктивных цен «от», без навязчивого спама и со строгим соблюдением стандартов туристического законодательства.', 'zhiraf-ufa' ); ?></p>
		</div>
	</section>

	<section class="section section--about-trust" aria-label="<?php esc_attr_e( 'Показатели надёжности', 'zhiraf-ufa' ); ?>">
		<div class="container">
			<div class="about-trust-metrics reveal">
				<?php foreach ( zhiraf_ufa_about_trust_metrics() as $metric ) : ?>
					<div class="about-trust-metric">
						<span class="about-trust-metric__value"><?php echo esc_html( $metric['value'] ); ?></span>
						<span class="about-trust-metric__label"><?php echo esc_html( $metric['label'] ); ?></span>
						<?php if ( ! empty( $metric['detail'] ) ) : ?>
							<p class="about-trust-metric__detail"><?php echo esc_html( $metric['detail'] ); ?></p>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>

			<div class="about-office-gallery reveal">
				<?php foreach ( zhiraf_ufa_about_office_gallery() as $slot ) : ?>
					<?php
					$file    = ! empty( $slot['file'] ) ? (string) $slot['file'] : '';
					$caption = ! empty( $slot['caption'] ) ? (string) $slot['caption'] : '';
					$placeholder_title = ! empty( $slot['placeholder_title'] ) ? (string) $slot['placeholder_title'] : __( 'Офис турагентства «Жираф» в Уфе', 'zhiraf-ufa' );
					$has_img = '' !== $file && zhiraf_ufa_theme_image_exists( $file );
					?>
					<figure class="about-office-slot">
						<div class="about-office-slot__media">
							<?php if ( $has_img ) : ?>
								<?php zhiraf_ufa_img( $file, $caption, 'about-office-slot__img', true ); ?>
							<?php else : ?>
								<div class="about-office-slot__placeholder">
									<strong><?php echo esc_html( $placeholder_title ); ?></strong>
									<p><?php echo esc_html( $caption ); ?></p>
									<a class="btn btn--outline btn--sm" href="<?php echo esc_url( $zh_map_url ); ?>" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'Маршрут в Яндекс Картах', 'zhiraf-ufa' ); ?></a>
								</div>
							<?php endif; ?>
						</div>
						<?php if ( $has_img && '' !== $caption ) : ?>
							<figcaption class="about-office-slot__caption"><?php echo esc_html( $caption ); ?></figcaption>
						<?php endif; ?>
					</figure>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="section section--sand" id="about-team">
		<div class="container">
			<div class="section-head reveal">
				<h2 class="section-title"><?php esc_html_e( 'Команда агентства «Жираф»', 'zhiraf-ufa' ); ?></h2>
				<p class="lead"><?php esc_html_e( 'Живые менеджеры в Уфе — с именем, специализацией и прямой связью в мессенджере.', 'zhiraf-ufa' ); ?></p>
			</div>
			<div class="about-team-grid">
				<?php
				foreach ( zhiraf_ufa_team_roster() as $member ) {
					get_template_part( 'template-parts/about/team', 'card', array( 'member' => $member ) );
				}
				?>
			</div>
		</div>
	</section>

	<section class="section">
		<div class="container about-story-grid">
			<div class="prose reveal">
				<h2><?php esc_html_e( 'Наша история и развитие', 'zhiraf-ufa' ); ?></h2>
				<p><?php esc_html_e( 'Турагентство «Жираф» работает в Уфе с апреля 2011 года. С первых дней главным ориентиром остаётся бережное и честное отношение к путешественникам: подбирать поездку так, как мы выбираем отдых для своих семей. Сегодня вас консультируют директор Рафаэль и менеджеры Лиана и Алина.', 'zhiraf-ufa' ); ?></p>
				<p><?php esc_html_e( 'За годы работы агентство ООО «Жираф» (реестровый номер в Едином федеральном реестре турагентств — РТА 0012822, ИНН 0278177191, ОГРН 1110280012457) выстроило прямые надёжные партнёрские отношения с ведущими туроператорами России. Мы непрерывно инспектируем курорты и отели Турции, ОАЭ, Египта, Таиланда и Вьетнама, чтобы предоставлять проверенную и объективную информацию о каждом объекте размещения.', 'zhiraf-ufa' ); ?></p>
			</div>
			<div class="reveal reveal-delay-1">
				<h2 class="section-title" style="font-size:1.75rem;margin-bottom:16px;"><?php esc_html_e( 'Ключевые принципы работы', 'zhiraf-ufa' ); ?></h2>
				<ul class="about-principles">
					<li>
						<strong><?php esc_html_e( 'Честность и прозрачность', 'zhiraf-ufa' ); ?></strong>
						<?php esc_html_e( 'Озвучиваем полную стоимость тура, питание AI/UAI и возможные депозиты до подписания договора.', 'zhiraf-ufa' ); ?>
					</li>
					<li>
						<strong><?php esc_html_e( 'Экспертный фокус', 'zhiraf-ufa' ); ?></strong>
						<?php esc_html_e( '2–4 проверенных варианта вместо бесконечного списка — с честным сравнением плюсов и минусов.', 'zhiraf-ufa' ); ?>
					</li>
					<li>
						<strong><?php esc_html_e( 'Офис на Гагарина', 'zhiraf-ufa' ); ?></strong>
						<?php esc_html_e( 'ул. Юрия Гагарина, 74/1, офис 3 — можно приехать на кофе и обсудить поездку лично.', 'zhiraf-ufa' ); ?>
					</li>
					<li>
						<strong><?php esc_html_e( 'Сопровождение до дома', 'zhiraf-ufa' ); ?></strong>
						<?php esc_html_e( 'На связи от заявки и документов до вылета, отеля и возвращения в Уфу.', 'zhiraf-ufa' ); ?>
					</li>
				</ul>
				<p>
					<a href="<?php echo esc_url( home_url( '/kontakty/' ) ); ?>" class="btn btn--outline btn--sm"><?php esc_html_e( 'Контакты и реквизиты', 'zhiraf-ufa' ); ?></a>
					<a href="<?php echo esc_url( home_url( '/tour/' ) ); ?>" class="btn btn--outline btn--sm"><?php esc_html_e( 'Каталог направлений', 'zhiraf-ufa' ); ?></a>
				</p>
			</div>
		</div>
	</section>

	<section class="section bg-milky">
		<div class="container container--narrow">
			<div class="section-head reveal">
				<h2 class="section-title"><?php esc_html_e( 'Частые вопросы о работе агентства', 'zhiraf-ufa' ); ?></h2>
			</div>
			<div class="faq-home__list">
				<?php foreach ( zhiraf_ufa_about_faq_items() as $item ) : ?>
					<details class="faq-home__item">
						<summary class="faq-home__question"><?php echo esc_html( $item['q'] ); ?></summary>
						<div class="faq-home__answer">
							<p><?php echo esc_html( $item['a'] ); ?></p>
						</div>
					</details>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<section class="section cta-strip">
		<div class="container cta-strip__inner">
			<div class="cta-strip__content">
				<h2><?php esc_html_e( 'Готовы доверить организацию отпуска профессионалам?', 'zhiraf-ufa' ); ?></h2>
				<p><?php esc_html_e( 'Подберём 2–4 проверенных варианта с вылетом из Уфы — или ответим на вопросы лично, в мессенджере или в офисе на Гагарина.', 'zhiraf-ufa' ); ?></p>
			</div>
			<div class="cta-strip__actions cta-strip__actions--about">
				<button type="button" class="btn btn--primary btn--lg" data-quiz-open><?php esc_html_e( 'Подобрать тур', 'zhiraf-ufa' ); ?></button>
				<a href="<?php echo esc_url( home_url( '/tour/' ) ); ?>" class="btn btn--outline btn--lg"><?php esc_html_e( 'Выбрать направление', 'zhiraf-ufa' ); ?></a>
				<?php if ( '' !== $zh_wa_dir ) : ?>
					<a href="<?php echo esc_url( $zh_wa_dir ); ?>" class="btn btn--outline btn--lg" target="_blank" rel="noopener noreferrer" data-track="messenger" data-channel="whatsapp"><?php esc_html_e( 'Вопрос руководителю в WhatsApp', 'zhiraf-ufa' ); ?></a>
				<?php endif; ?>
				<a href="<?php echo esc_url( home_url( '/kontakty/' ) ); ?>" class="btn btn--outline btn--lg"><?php esc_html_e( 'Приехать в офис на Гагарина', 'zhiraf-ufa' ); ?></a>
			</div>
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
