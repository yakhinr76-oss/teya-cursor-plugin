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
			<p class="lead"><?php esc_html_e( 'Семейное турагентство в Уфе с апреля 2011 года. Честно подбираем и организуем пакетные туры с прямым вылетом из Уфы — без фиктивных цен «от», без навязчивого спама и со строгим соблюдением стандартов туристического законодательства.', 'zhiraf-ufa' ); ?></p>
		</div>
	</section>

	<section class="section">
		<div class="container about-grid">
			<div class="prose">
				<h2><?php esc_html_e( 'Наша история и развитие', 'zhiraf-ufa' ); ?></h2>
				<p><?php esc_html_e( 'Турагентство «Жираф» основано в Уфе в апреле 2011 года Зульфией и Ксенией. С первых дней работы главным ориентиром стало бережное и честное отношение к путешественникам: подбирать поездку так, как мы выбираем отдых для своих семей и близких друзей. В 2014 году команда расширилась — к развитию агентства присоединились Рафаэль, Лия и Алина.', 'zhiraf-ufa' ); ?></p>
				<p><?php esc_html_e( 'За годы работы агентство ООО «Жираф» (реестровый номер в Едином федеральном реестре турагентств — РТА 0012822, ИНН 0278177191, ОГРН 1110280012457) выстроило прямые надёжные партнёрские отношения с ведущими туроператорами России. Мы непрерывно инспектируем курорты и отели Турции, ОАЭ, Египта, Таиланда и Вьетнама, чтобы предоставлять проверенную и объективную информацию о каждом объекте размещения.', 'zhiraf-ufa' ); ?></p>

				<h2><?php esc_html_e( 'Ключевые принципы работы', 'zhiraf-ufa' ); ?></h2>
				<ul>
					<li><strong><?php esc_html_e( 'Честность и прозрачность:', 'zhiraf-ufa' ); ?></strong> <?php esc_html_e( 'мы открыто озвучиваем полную и окончательную стоимость тура, детализируем концепции питания (AI/UAI), информируем о возможных отельных депозитах, налогах и сборах до заключения договора.', 'zhiraf-ufa' ); ?></li>
					<li><strong><?php esc_html_e( 'Экспертный фокус и уважение к времени:', 'zhiraf-ufa' ); ?></strong> <?php esc_html_e( 'вместо бесконечных списков из десятков случайных отелей мы готовим персональную подборку из 2–4 наиболее подходящих вариантов, чётко сравнивая их плюсы и минусы.', 'zhiraf-ufa' ); ?></li>
					<li><strong><?php esc_html_e( 'Локальное присутствие и живой контакт:', 'zhiraf-ufa' ); ?></strong> <?php esc_html_e( 'наш физический офис расположен в Уфе на ул. Юрия Гагарина, 74/1 (офис 3). Вы всегда можете встретиться с менеджером лично, выпить кофе и спокойно обсудить детали будущей поездки.', 'zhiraf-ufa' ); ?></li>
					<li><strong><?php esc_html_e( 'Сопровождение на всех этапах:', 'zhiraf-ufa' ); ?></strong> <?php esc_html_e( 'мы остаёмся на связи от момента первого обращения и подготовки документов до вылета из Уфы, заселения в отель и благополучного возвращения домой.', 'zhiraf-ufa' ); ?></li>
				</ul>

				<p>
					<a href="<?php echo esc_url( home_url( '/kontakty/' ) ); ?>" class="btn btn--outline btn--sm"><?php esc_html_e( 'Контакты и реквизиты', 'zhiraf-ufa' ); ?></a>
					<a href="<?php echo esc_url( home_url( '/tour/' ) ); ?>" class="btn btn--outline btn--sm"><?php esc_html_e( 'Каталог направлений', 'zhiraf-ufa' ); ?></a>
				</p>
			</div>
			<div class="about-visual">
				<?php zhiraf_ufa_img( 'team-travel.jpg', __( 'Команда турагентства «Жираф» за работой', 'zhiraf-ufa' ), 'about-img', true ); ?>
				<?php zhiraf_ufa_img( 'office-ufa.jpg', __( 'Офис турагентства «Жираф» в Уфе на улице Гагарина', 'zhiraf-ufa' ), 'about-img', true ); ?>
			</div>
		</div>
	</section>

	<section class="section section--sand">
		<div class="container">
			<div class="section-head reveal">
				<h2 class="section-title"><?php esc_html_e( 'Команда агентства «Жираф»', 'zhiraf-ufa' ); ?></h2>
				<p class="lead"><?php esc_html_e( 'Специалисты с многолетним опытом работы в туризме, знающие специфику курортов и отельную базу.', 'zhiraf-ufa' ); ?></p>
			</div>
			<div class="team-grid">
				<div class="team-card">
					<h3><?php esc_html_e( 'Рафаэль', 'zhiraf-ufa' ); ?></h3>
					<p class="team-card__role"><?php esc_html_e( 'Директор', 'zhiraf-ufa' ); ?></p>
					<p class="team-card__desc"><?php esc_html_e( 'Организация работы агентства, контроль качества сервиса, юридическое сопровождение договоров и стратегическое партнёрство с туроператорами.', 'zhiraf-ufa' ); ?></p>
				</div>
				<div class="team-card">
					<h3><?php esc_html_e( 'Зульфия', 'zhiraf-ufa' ); ?></h3>
					<p class="team-card__role"><?php esc_html_e( 'Основатель, заместитель директора', 'zhiraf-ufa' ); ?></p>
					<p class="team-card__desc"><?php esc_html_e( 'У истоков агентства с 2011 года. Эксперт по сложным индивидуальным маршрутам, премиальным резортам Турции, ОАЭ и семейному отдыху.', 'zhiraf-ufa' ); ?></p>
				</div>
				<div class="team-card">
					<h3><?php esc_html_e( 'Альфия', 'zhiraf-ufa' ); ?></h3>
					<p class="team-card__role"><?php esc_html_e( 'Ведущий менеджер по туризму', 'zhiraf-ufa' ); ?></p>
					<p class="team-card__desc"><?php esc_html_e( 'Специализируется на пляжных направлениях Средиземного и Красного морей (Турция, Египет), подборе отелей All Inclusive и турах для семей с детьми.', 'zhiraf-ufa' ); ?></p>
				</div>
				<div class="team-card">
					<h3><?php esc_html_e( 'Гульнара', 'zhiraf-ufa' ); ?></h3>
					<p class="team-card__role"><?php esc_html_e( 'Менеджер по туризму', 'zhiraf-ufa' ); ?></p>
					<p class="team-card__desc"><?php esc_html_e( 'Эксперт по направлениям Азии и Ближнего Востока (Таиланд, Вьетнам, ОАЭ), визовым правилам, стыковочным авиаперелётам и экскурсионным турам.', 'zhiraf-ufa' ); ?></p>
				</div>
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
				<p><?php esc_html_e( 'Пройдите короткий квиз или оставьте заявку — менеджер свяжется с вами и подготовит 2–4 проверенных варианта с прямым вылетом из Уфы.', 'zhiraf-ufa' ); ?></p>
			</div>
			<div class="cta-strip__actions">
				<button type="button" class="btn btn--primary btn--lg" data-quiz-open><?php esc_html_e( 'Подобрать тур', 'zhiraf-ufa' ); ?></button>
				<a href="tel:+79018105018" class="btn btn--outline btn--lg" data-track="phone"><?php esc_html_e( '+7 901 810-50-18', 'zhiraf-ufa' ); ?></a>
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
