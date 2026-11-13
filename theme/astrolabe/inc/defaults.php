<?php
/**
 * Default content — the text of the approved prototype.
 *
 * The site renders completely from these values on a fresh install. Section
 * texts can be overridden per field on the front page (ACF), and the lists
 * (features, privacy points, plans, questions) become editable posts once
 * "Create editable copies" is used under Site content. The illustrative parts
 * (client mock, timeline, chart facts) stay here.
 *
 * @package AstroLabe
 */

defined( 'ABSPATH' ) || exit;

/**
 * All default content, keyed by area.
 */
function astrolabe_defaults(): array {
	static $defaults = null;
	if ( null !== $defaults ) {
		return $defaults;
	}

	$defaults = array(
		'sections' => array(
			'hero'     => array(
				'eyebrow'       => 'For professional astrologers',
				'title'         => 'Open a client. <em>See everything.</em>',
				'lede'          => "Birth data, the natal chart, today's transits, past consultations, notes, files, payments and what comes next — together in one workspace, so you start every reading prepared.",
				'cta_primary'   => 'Start your free trial',
				'cta_secondary' => "See what's inside",
				'notes'         => "14 days free\nNo card needed\nHosted in the EU",
			),
			'problem'  => array(
				'eyebrow' => 'Why AstroLabe',
				'title'   => 'Seven places <em>for one client.</em>',
				'text_1'  => 'Messages in a chat app, the appointment in a calendar, notes in a document, payments in a spreadsheet, the chart in desktop software and the recording somewhere in a cloud folder.',
				'text_2'  => '<strong>AstroLabe keeps all of it on the client</strong> — so getting ready for a consultation takes a minute, not twenty, and nothing you promised falls through the cracks.',
			),
			'features' => array(
				'eyebrow' => 'Everything a consultation touches',
				'title'   => 'Your practice and your charts, <em>in the same place.</em>',
				'lede'    => 'Not a generic CRM with astrology bolted on — every screen is built around how readings actually happen.',
			),
			'charts'   => array(
				'eyebrow' => 'Serious about the sky',
				'title'   => 'Charts you can <em>put your name to.</em>',
			),
			'story'    => array(
				'eyebrow' => 'One client, the whole story',
				'title'   => 'Pick up exactly <em>where you left off.</em>',
				'text'    => "Every reading, note, payment, file and follow-up lands on the client's timeline in order. Before the next session you scroll once and you are back where you left off — including what the sky is doing to their chart right now.",
			),
			'privacy'  => array(
				'eyebrow' => 'Privacy & security',
				'title'   => 'Your clients trust you with their birth data. <em>We treat it that way.</em>',
				'lede'    => 'Birth details, notes and files are handled with the care you would give health records — in how they are stored, logged and exported.',
			),
			'pricing'  => array(
				'eyebrow'      => 'Pricing',
				'title'        => 'Try it first. <em>Then choose a plan.</em>',
				'lede'         => 'Every practice starts with 14 free days. Nothing is charged unless you choose a plan.',
				'banner_title' => '14-day free trial — no card',
				'banner_text'  => 'Work with up to 5 clients and see whether AstroLabe fits the way you work.',
				'banner_cta'   => 'Start your free trial',
				'note'         => 'Prices in euros. Monthly or yearly — the yearly plan costs about ten months.',
			),
			'trial'    => array(
				'eyebrow'    => 'Free trial',
				'title'      => 'Try it with <em>your own practice.</em>',
				'text'       => 'Create an account in a minute and work with AstroLabe for 14 days. No card, nothing to install.',
				'steps'      => "Create your account with your email and a password, then confirm the address.\nFor 14 days, add up to 5 clients and work with their charts, transits, consultations, notes, calendar and payments.\nChoose a plan to carry on with everything you entered. Without one, nothing is charged: your practice stays readable and you can export all of it.",
				'card_title' => '14 days, free',
				'facts'      => "No card needed\nUp to 5 clients\nUp to 30 chart calculations a day\nClient portal and chart snapshots come with a plan",
				'cta'        => 'Start your free trial',
			),
			'faq'      => array(
				'eyebrow' => 'Questions',
				'title'   => 'Good to know',
			),
			'closing'  => array(
				'title' => 'Every client, <em>every chart,</em><br>in one place.',
				'text'  => 'Start a free 14-day trial and run your practice from a workspace made for astrologers.',
				'cta'   => 'Start your free trial',
			),
		),

		'feature'  => array(
			array( 'icon' => 'user', 'title' => 'Client profiles', 'text' => 'Birth data with the time zone that applied on that day and place, related people, tags and a timeline of everything that happened.' ),
			array( 'icon' => 'chart', 'title' => 'Natal charts', 'text' => 'Positions calculated with the Swiss Ephemeris. Tropical or sidereal, ten house systems, aspects with the orbs you choose.' ),
			array( 'icon' => 'transit', 'title' => 'Transits', 'text' => "Transits to any client's chart for any moment, with exact dates — and a dashboard card for the people you are seeing next." ),
			array( 'icon' => 'synastry', 'title' => 'Synastry & composite', 'text' => "Lay a partner's chart over your client's, read the contacts and house overlays, or switch to the composite chart." ),
			array( 'icon' => 'sky', 'title' => 'Sky calendar', 'text' => 'Aspects, stations, ingresses, new and full moons for the weeks ahead — to the minute, on your own clock.' ),
			array( 'icon' => 'notes', 'title' => 'Consultations & notes', 'text' => 'Notes during or after a reading, the chart as it was on the day, and recordings, PDFs and images kept with the client.' ),
			array( 'icon' => 'calendar', 'title' => 'Calendar & reminders', 'text' => 'Day, week, month and agenda views, a warning when bookings overlap, and an email reminder before each appointment.' ),
			array( 'icon' => 'payment', 'title' => 'Fees & payments', 'text' => 'Prices per service, deposits, what is paid and what is still owed — in any currency, exported to CSV for your bookkeeping.' ),
			array( 'icon' => 'task', 'title' => 'Tasks & follow-ups', 'text' => 'Follow-ups tied to a consultation, due dates in your own time zone, and a morning email with what is due today.' ),
		),

		'trust'    => array(
			array( 'icon' => 'shield', 'title' => 'Hosted in the EU', 'text' => 'Your practice lives on servers in the European Union, on its own machine — never next to a marketing website.' ),
			array( 'icon' => 'doc', 'title' => 'A GDPR agreement included', 'text' => "You are the controller of your clients' data, we are the processor — written down in a data processing agreement you accept at sign-up." ),
			array( 'icon' => 'phone', 'title' => 'Two-factor sign-in', 'text' => 'Turn on an authenticator app, and every new sign-in needs a code from your phone.' ),
			array( 'icon' => 'lock', 'title' => 'Encrypted nightly backups', 'text' => 'Every night, encrypted before it leaves the server and kept for 14 days — with restores tested, not just promised.' ),
			array( 'icon' => 'download', 'title' => 'Take everything with you', 'text' => 'Export your whole practice — clients, charts, notes, files and payments — as one download, whenever you want.' ),
			array( 'icon' => 'trash', 'title' => 'Delete means delete', 'text' => 'When a client asks to be forgotten, their data is erased — not hidden behind a flag.' ),
		),

		'plan'     => array(
			array( 'title' => 'Solo', 'tag' => '', 'for' => 'For an astrologer working on their own.', 'price' => '€19', 'period' => '/ month', 'yearly' => 'or €190 a year', 'featured' => false ),
			array( 'title' => 'Professional', 'tag' => 'Most practices', 'for' => 'For an active practice with clients near and far.', 'price' => '€29', 'period' => '/ month', 'yearly' => 'or €290 a year', 'featured' => true ),
			array( 'title' => 'Studio', 'tag' => '', 'for' => 'For several astrologers under one roof.', 'price' => '€49', 'period' => '/ month', 'yearly' => 'or €490 a year', 'featured' => false ),
		),

		'faq'      => array(
			array(
				'title'  => 'Does AstroLabe replace my chart software?',
				'answer' => '<p>For everyday client work it often can: natal charts, transits, synastry, composite charts and the sky calendar are all built in, next to the client they belong to.</p><p>It does not write interpretations, and some techniques — progressions and solar returns, for example — are not there yet. Tell us which ones you need; what astrologers ask for decides what comes next.</p>',
			),
			array(
				'title'  => 'Which traditions does it support?',
				'answer' => '<p>Western, Vedic, Hellenistic and the schools built on them. You choose the zodiac, ayanamsa, house system and orbs for your practice, and can change them for any single chart.</p>',
			),
			array(
				'title'  => 'Can my clients sign in?',
				'answer' => '<p>Yes, when you invite them. Each invited client gets a private client portal with your practice’s name, logo and colour. They sign in with a link or a code sent to their email — no password — and see their appointments and the notes and files you choose to share.</p><p>They can also book, move or cancel appointments within your rules: your working hours and time off, how much notice you need and until when changes are allowed. You can confirm each booking yourself. Your workspace stays private; clients never see your notes or anyone else.</p>',
			),
			array(
				'title'  => 'What happens to my data if I leave?',
				'answer' => '<p>You can export your whole practice at any time. If you close your account, you have 30 days to change your mind; after that everything is permanently deleted, backups included once they expire.</p>',
			),
			array(
				'title'  => 'Which languages?',
				'answer' => '<p>English for now. More languages are planned — tell us which one you need.</p>',
			),
			array(
				'title'  => 'What happens when the trial ends?',
				'answer' => '<p>Choose a plan — from €19 a month, or €190 a year — and carry on with everything you entered. If you do not, nothing is charged: you gave us no card. Your practice becomes read-only, so you can still sign in, look things up and export all of it, and choosing a plan later opens it again.</p>',
			),
		),

		// Illustrations: edited in code, not in the admin.
		'tools'    => array(
			array( 'icon' => 'chat', 'label' => 'Chat messages', 'x' => 20, 'y' => 15, 'tilt' => -4 ),
			array( 'icon' => 'calendar', 'label' => 'Calendar app', 'x' => 76, 'y' => 11, 'tilt' => 3 ),
			array( 'icon' => 'notes', 'label' => 'Notes file', 'x' => 14, 'y' => 53, 'tilt' => 2 ),
			array( 'icon' => 'sheet', 'label' => 'Spreadsheet', 'x' => 86, 'y' => 49, 'tilt' => -3 ),
			array( 'icon' => 'chart', 'label' => 'Chart software', 'x' => 19, 'y' => 88, 'tilt' => 3 ),
			array( 'icon' => 'folder', 'label' => 'Cloud folder', 'x' => 80, 'y' => 86, 'tilt' => -2 ),
			array( 'icon' => 'bank', 'label' => 'Bank app', 'x' => 50, 'y' => 97, 'tilt' => 1 ),
		),

		'facts'    => array(
			array( 'title' => 'Tropical and sidereal', 'text' => 'Six ayanamsas for Vedic and sidereal work.', 'list' => 'Lahiri · Raman · Krishnamurti · Fagan–Bradley · Yukteshwar · True Citra' ),
			array( 'title' => 'Ten house systems', 'text' => 'A default for your practice, a different one for any chart.', 'list' => 'Placidus · Koch · Whole Sign · Equal · Porphyry · Regiomontanus · Campanus · Alcabitius · Topocentric · Morinus' ),
			array( 'title' => 'Honest about unknown birth times', 'text' => 'No invented angles or houses — and the Moon shown as the span of degrees it could be in.', 'list' => '' ),
			array( 'title' => 'Every place, every clock change', 'text' => 'Five million towns and villages, with the time zone and daylight-saving rules in force on the birth date. Above the Arctic Circle, where Placidus cannot be drawn, you are told — and shown Porphyry instead.', 'list' => '' ),
		),

		'timeline' => array(
			array( 'icon' => 'transit', 'when' => 'Today', 'what' => 'Pluto conjunct natal Saturn', 'more' => "Orb 1°52′, applying — before Thursday's follow-up", 'now' => true ),
			array( 'icon' => 'task', 'when' => '2 Sep', 'what' => 'Follow-up done', 'more' => 'Send the recording and the reading notes' ),
			array( 'icon' => 'notes', 'when' => '28 Aug', 'what' => 'Note', 'more' => '“Career change — look again when Pluto settles on her Saturn.”', 'note' => true ),
			array( 'icon' => 'payment', 'when' => '28 Aug', 'what' => 'Payment · €50 cash', 'more' => 'Natal reading €120 — €70 still owed' ),
			array( 'icon' => 'clock', 'when' => '28 Aug', 'what' => 'Consultation · Natal reading', 'more' => '90 minutes · completed · chart attached as it was' ),
			array( 'icon' => 'plus', 'when' => '14 Aug', 'what' => 'Client added · chart calculated', 'more' => 'Placidus · Tropical · exact birth time' ),
		),

	);

	return $defaults;
}
