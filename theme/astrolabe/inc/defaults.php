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
				'cta_primary'   => 'Request a beta invite',
				'cta_secondary' => "See what's inside",
				'notes'         => "Free during the closed beta\nNo card needed\nHosted in the EU",
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
				'title'        => 'Free while we <em>build it with you.</em>',
				'lede'         => 'AstroLabe is in a closed beta with a small group of working astrologers. Paid plans start after the beta.',
				'banner_title' => 'Closed beta — free, no card',
				'banner_text'  => 'Use it with your real practice, tell us what is missing, and hear about plans before anyone else.',
				'banner_cta'   => 'Request an invite',
				'note'         => 'Indicative prices — confirmed with beta members before paid plans open. Every plan will start with a 14-day free trial.',
			),
			'beta'     => array(
				'eyebrow'    => 'Closed beta',
				'title'      => 'Help shape the tool <em>you will work in.</em>',
				'text'       => 'We are inviting a small number of astrologers who see clients regularly. Tell us a little about your practice.',
				'steps'      => "We read every request and reply personally, usually within a few days.\nYou get an invitation link to create your practice — free for the whole beta.\nA short call now and then, if you like: what works, what is missing, what is in the way.",
				'submit'     => 'Request an invite',
				'done_title' => 'Thank you — request received.',
				'done_text'  => 'We will write to you personally, usually within a few days.',
			),
			'faq'      => array(
				'eyebrow' => 'Questions',
				'title'   => 'Good to know',
			),
			'closing'  => array(
				'title' => 'Every client, <em>every chart,</em><br>in one place.',
				'text'  => 'Join the closed beta and run your practice from a workspace made for astrologers.',
				'cta'   => 'Request a beta invite',
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
			array( 'title' => 'Solo', 'tag' => 'Planned', 'for' => 'For an astrologer working on their own.', 'price' => '€19', 'period' => '/ month', 'yearly' => 'or €190 a year', 'featured' => false ),
			array( 'title' => 'Professional', 'tag' => 'Planned · most practices', 'for' => 'For an active practice with clients near and far.', 'price' => '€29', 'period' => '/ month', 'yearly' => 'or €290 a year', 'featured' => true ),
			array( 'title' => 'Studio', 'tag' => 'Planned', 'for' => 'For several astrologers under one roof.', 'price' => '€49', 'period' => '/ month', 'yearly' => 'or €490 a year', 'featured' => false ),
		),

		'faq'      => array(
			array(
				'title'  => 'Does AstroLabe replace my chart software?',
				'answer' => '<p>For everyday client work it often can: natal charts, transits, synastry, composite charts and the sky calendar are all built in, next to the client they belong to.</p><p>It does not write interpretations, and some techniques — progressions and solar returns, for example — are not there yet. Tell us which ones you need; the beta decides what comes next.</p>',
			),
			array(
				'title'  => 'Which traditions does it support?',
				'answer' => '<p>Western, Vedic, Hellenistic and the schools built on them. You choose the zodiac, ayanamsa, house system and orbs for your practice, and can change them for any single chart.</p>',
			),
			array(
				'title'  => 'Can my clients sign in?',
				'answer' => '<p>Not yet. AstroLabe is your private workspace; clients never see it. A client portal with online booking is planned after the beta.</p>',
			),
			array(
				'title'  => 'What happens to my data if I leave?',
				'answer' => '<p>You can export your whole practice at any time. If you close your account, you have 30 days to change your mind; after that everything is permanently deleted, backups included once they expire.</p>',
			),
			array(
				'title'  => 'Which languages?',
				'answer' => '<p>English for now. More languages are planned — tell us yours when you request an invite.</p>',
			),
			array(
				'title'  => 'What will it cost after the beta?',
				'answer' => '<p>The plans we have in mind start at €19 a month, or €190 a year. Prices are confirmed with beta members before any paid plan opens, and nobody is moved to a paid plan without asking.</p>',
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

		// Beta form choices (value => label).
		'practice' => array(
			'solo'    => 'On my own',
			'online'  => 'Online, with clients abroad',
			'studio'  => 'In a studio with other astrologers',
			'teacher' => 'I teach or mentor',
		),
		'volume'   => array(
			'1-5'   => '1–5',
			'6-20'  => '6–20',
			'21-50' => '21–50',
			'50+'   => 'More than 50',
		),
		'tradition' => array(
			'western'       => 'Western',
			'vedic'         => 'Vedic / Jyotish',
			'hellenistic'   => 'Hellenistic',
			'psychological' => 'Psychological',
			'evolutionary'  => 'Evolutionary',
			'horary'        => 'Horary & electional',
			'other'         => 'Other',
		),
	);

	return $defaults;
}
