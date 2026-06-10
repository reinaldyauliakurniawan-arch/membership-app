INASA Membership App — PRD
Project: INASA (Indonesia Australia Scrabble Academy) Membership System
Base Codebase: reinaldyauliakurniawan-arch/membership-app (forked from Gymie)
Stack: Laravel + Filament Admin Panel
Status: Pre-development — fresh clone, no DB, no env

1. Background
INASA adalah scrabble club yang baru diinisiasi, fokus pada market Indonesia, dengan sistem keanggotaan dan coaching yang mirip dengan gym. Club rutin mengadakan turnamen minimal sebulan sekali, dan semua turnamen di-rating oleh ISF (Indonesia Scrabble Federation). Member dibagi ke dalam divisi berdasarkan rating ISF masing-masing.

2. Scope of Work
Development dibagi dua fase:

Fase 1 — Cleanup: Hapus dan edit fitur warisan Gymie yang tidak relevan, tanpa menambah fitur baru
Fase 2 — Additions: Tambah fitur baru spesifik untuk scrabble club


3. Fase 1 — Cleanup & Rebranding
3.1 Hapus Fields
members table & semua layer-nya
Hapus field health_issue dan goal dari file-file berikut:
database/migrations/2025_06_10_101915_create_members_table.php

Hapus baris $table->string('health_issue')->nullable();
Hapus baris $table->string('goal')->default('fitness')->nullable();

app/Models/Member.php

Hapus 'health_issue' dan 'goal' dari array $fillable
Hapus @property string|null $health_issue dan @property string|null $goal dari PHPDoc

app/Filament/Resources/Members/Schemas/MemberForm.php

Hapus blok TextInput::make('health_issue') beserta semua chain method-nya
Hapus blok Select::make('goal') beserta semua option dan chain method-nya
Kedua field ini berada dalam satu Group yang juga berisi gender, dob, dan source — pastikan Group-nya tidak kosong setelah penghapusan, hanya hapus dua field tersebut

app/Filament/Resources/Members/Schemas/MemberInfolist.php

Hapus blok TextEntry::make('goal') beserta chain method-nya
Hapus blok TextEntry::make('health_issue') beserta chain method-nya
Keduanya berada dalam satu Group bersama dob dan source — pastikan Group tidak kosong

app/Filament/Resources/Members/Pages/CreateMember.php

Hapus baris 'goal' => $enquiry->goal, dari array di method mount()

app/Services/Api/Schemas/MemberSchema.php

Hapus 'health_issue' => ['nullable', 'string', 'max:500'], dari storeRules()
Hapus 'goal' => ['nullable', 'string', 'max:255'], dari storeRules()
Hapus 'health_issue' => ['sometimes', 'nullable', 'string', 'max:500'], dari updateRules()
Hapus 'goal' => ['sometimes', 'nullable', 'string', 'max:255'], dari updateRules()
Hapus 'health_issue' => $member->health_issue ? ... : null, dari resource()
Hapus 'goal' => $member->goal ? ... : null, dari resource()

database/factories/MemberFactory.php

Hapus baris 'health_issue' => $this->faker->optional()->sentence(),
Hapus baris 'goal' => $this->faker->randomElement([...]),


enquiries table & semua layer-nya
Hapus field goal dari file-file berikut:
database/migrations/2025_05_26_020228_create_enquiries_table.php

Hapus baris $table->string('goal')->nullable();

app/Models/Enquiry.php

Hapus 'goal' dari array $fillable
Hapus @property string|null $goal dari PHPDoc

app/Filament/Resources/Enquiries/Schemas/EnquiryForm.php

Hapus seluruh blok Select::make('goal') beserta semua option dan chain method-nya
Field ini berada dalam section preferences bersama interested_in dan source — section tidak akan kosong setelah penghapusan

app/Filament/Resources/Enquiries/Schemas/EnquiryInfolist.php

Hapus blok TextEntry::make('goal') beserta chain method-nya
Field ini berada dalam section preferences bersama interested_in dan source — section tidak akan kosong

app/Services/Api/Schemas/EnquirySchema.php

Hapus 'goal' => ['nullable', 'string', 'max:255'], dari storeRules()
Hapus 'goal' => ['sometimes', 'nullable', 'string', 'max:255'], dari updateRules()
Hapus 'goal' => $enquiry->goal ? (string) $enquiry->goal : null, dari resource()

database/factories/EnquiryFactory.php

Hapus baris 'goal' => $this->faker->randomElement([...]),


3.2 Edit Lang Files
Berlaku untuk ketiga file: resources/lang/en/app.php, fr/app.php, ar/app.php
Hapus entri:

fields.health_issues — hapus baris 'health_issues' => '...',
placeholders.health_issues — hapus baris 'health_issues' => '...',
fields.goal — hapus baris 'goal' => 'Goal', (berada di section fields, baris 69 di en/app.php)

Ganti value (jangan ubah key):
KeyValue baru (en)options.goal arrayGanti seluruh isi array — lihat 3.2.1settings.tabs.gym_info'Club Info'settings.fields.gym_name'Club Name'settings.fields.gym_logo'Club Logo'settings.options.name_type.gym_name'Club Name'settings.options.name_type.gym_logo'Club Logo'invoices.pdf.footer_tagline'Powered by INASA | :domain'settings.hints.tokens_invoiceGanti {gym_name} → {club_name} di dalam stringsettings.hints.tokens_receiptGanti {gym_name} → {club_name} di dalam string

Catatan tokens: {gym_name} di tokens_invoice/tokens_receipt adalah token literal yang dipakai user saat menulis subject template email. Harus diganti ke {club_name} bersamaan dengan perubahan key 'gym_name' → 'club_name' di return array method invoiceSubjectTokens() di InvoiceEmailService.php (lihat 3.4) — kalau salah satu tertinggal, token tidak akan ter-replace di subject email.

3.2.1 Ganti isi array options.goal
php'goal' => [
    'competitive'     => 'Competitive Play',
    'recreational'    => 'Recreational / Casual',
    'tournament_prep' => 'Tournament Preparation',
    'coaching'        => 'Personal Coaching',
    'others'          => 'Others',
],

Catatan: options.goal ini hanya dipakai di EnquiryForm.php setelah goal di MemberForm.php dihapus. Pastikan options di EnquiryForm.php ikut diupdate ke opsi yang sama.


3.3 Rename Settings Keys gym_* → club_* (jika ada yang terlewat tolong temukan dan sarankan ke pengguna)
Rename semua key gym_name → club_name, gym_logo → club_logo, gym_email → club_email, gym_contact → club_contact di semua file berikut secara bersamaan dalam satu commit.
app/Filament/Pages/Settings.php (11 kemunculan):

TextInput::make('general.gym_name') → 'general.club_name'
FileUpload::make('general.gym_logo') → 'general.club_logo'
handleFileUpload($state, 'gym_logo', $set) → 'club_logo'
foreach (['gym_logo'] as $logoType) → ['club_logo'] (baris 57)
foreach (['gym_logo'] as $logoKey) → ['club_logo'] (baris 369)
TextInput::make('general.gym_email') → 'general.club_email'
TextInput::make('general.gym_contact') → 'general.club_contact'
Option values di Select::make('invoice.name_type'): 'gym_name' → 'club_name', 'gym_logo' → 'club_logo'


Perhatian invoice.name_type: Nilai ini disimpan ke file JSON sebagai stored value ('gym_name' atau 'gym_logo'), lalu dibandingkan di document.blade.php dengan @if ($nameType === 'gym_name'). Semua titik berikut harus konsisten dan diganti bersamaan: option values di Settings.php, default value data_get($settings, 'invoice.name_type', 'gym_name') di blade (ganti default-nya ke 'club_name'), dan comparison @if ($nameType === 'gym_name') di blade (ganti ke 'club_name').

app/Support/Invoices/InvoiceDocument.php:

data_get($settings, 'general.gym_logo') → 'general.club_logo'

app/Services/Email/InvoiceEmailService.php:

data_get($settings, 'general.gym_name', ...) → 'general.club_name'
data_get($settings, 'general.gym_email', '') → 'general.club_email'
data_get($settings, 'general.gym_contact', '') → 'general.club_contact'

resources/views/invoices/document.blade.php:

data_get($settings, 'general.gym_name', 'Gymie') → key 'general.club_name'
data_get($settings, 'general.gym_email', '') → 'general.club_email'
data_get($settings, 'general.gym_contact', '') → 'general.club_contact'
data_get($settings, 'invoice.name_type', 'gym_name') → default 'club_name'
@if ($nameType === 'gym_name') → 'club_name'

storage/data/settingsData.json.example:

Rename semua key di dalam object "general": gym_logo → club_logo, gym_name → club_name, gym_email → club_email, gym_contact → club_contact

Test files:

tests/Unit/JsonSettingsRepositoryCacheTest.php — general.gym_name → general.club_name
tests/Feature/InvoiceIssuedEmailJobTest.php — gym_name → club_name, gym_email → club_email
tests/Feature/Api/SettingsApiTest.php — gym_name → club_name
tests/Feature/SettingsPagePersistenceTest.php — gym_logo → club_logo
tests/Feature/InvoicePaymentReceiptEmailJobTest.php — gym_name → club_name, gym_email → club_email
tests/Feature/InvoiceDocumentControllerTest.php — gym_name → club_name
tests/Feature/InvoiceEmailServiceTest.php — gym_name → club_name, gym_email → club_email, gym_contact → club_contact


3.4 Rebranding Gymie → INASA
app/Providers/Filament/AdminPanelProvider.php

->brandName('Gymie') → ->brandName('INASA')

app/Providers/AppServiceProvider.php

Css::make('gymie-styles', ...) → Css::make('inasa-styles', ...)

app/Mail/InvoiceIssuedMail.php

Rename constructor param $gymName → $clubName, $gymEmail → $clubEmail, $gymContact → $clubContact
Rename property declarations public readonly string $gymName → $clubName, dst
Update array yang di-pass ke view: 'gymName' => $this->gymName → 'clubName' => $this->clubName, dst

app/Mail/InvoicePaymentReceiptMail.php

Sama persis dengan InvoiceIssuedMail.php di atas

app/Services/Email/InvoiceEmailService.php

Fallback string 'Gymie' (muncul 2x) → 'INASA'
Rename method gymIdentityFromSettings() → clubIdentityFromSettings() — update juga kedua pemanggilan di baris 83 dan 140
Rename parameter string $gymName di signature invoiceSubjectTokens() → string $clubName
Rename semua local variable: $gym → $club, $gymName → $clubName
Ganti token key di return array invoiceSubjectTokens(): 'gym_name' => $gymName → 'club_name' => $clubName

resources/views/invoices/document.blade.php

Fallback 'Gymie' → 'INASA'
Rename semua local PHP variable: $gymName → $clubName, $gymEmail → $clubEmail, $gymContact → $clubContact, $gymAddress → $clubAddress, $gymDomain → $clubDomain
Fallback 'gymie' di footer (baris 470) → 'inasa'

resources/views/emails/invoices/layout.blade.php

Update PHPDoc: @var string $gymName → $clubName, dst
Ganti semua {{ $gymName }}, $gymEmail, $gymContact → $clubName, $clubEmail, $clubContact
Sent by {{ $gymName }} via Gymie. → Sent by {{ $clubName }} via INASA.


3.5 Rename Artisan Commands
app/Console/Commands/MarkSubscriptionsStatus.php

protected $signature = 'gymie:subscriptions ...' → 'inasa:subscriptions ...'

app/Console/Commands/MarkInvoiceOverdue.php

protected $signature = 'gymie:invoices ...' → 'inasa:invoices ...'

routes/console.php

Schedule::command('gymie:subscriptions' ...) → 'inasa:subscriptions'
Schedule::command('gymie:invoices --mark-overdue') → 'inasa:invoices --mark-overdue'


4. Fase 2 — Fitur Baru
4.1 Model Division (baru)
Tabel: divisions
FieldTipeKeteranganidbigint PKnamestringNama divisi, e.g. "A", "B", "C"min_ratingintegerBatas bawah rating (inclusive)max_ratinginteger nullableBatas atas rating (inclusive). Null = no ceilingdescriptionstring nullableorderintegerUrutan display, Divisi A = 1timestamps
Logic: Saat current_rating member berubah, sistem auto-assign division_id berdasarkan range. Admin bisa override manual.
Filament Resource: CRUD penuh, bisa diakses dari Settings atau menu tersendiri.
4.2 Tambah Fields ke members table
FieldTipeKeteranganisf_idstring nullableID resmi anggota di ISFcurrent_ratinginteger nullableRating ISF saat inidivision_idFK → divisions nullableAuto-assigned dari rating, bisa override manualnationalitystring nullablee.g. WNI, WNA, Dualskill_levelenum nullablebeginner, intermediate, competitiveis_coachbooleanDefault false
Tambahkan juga ke: MemberForm, MemberInfolist, MemberSchema, MemberFactory.
Observer: Buat MemberObserver — saat current_rating di-set atau diupdate, auto-assign division_id yang sesuai berdasarkan tabel divisions.
4.3 Tabel coach_details (baru)
Relasi one-to-one dengan members, hanya untuk member dengan is_coach = true.
FieldTipeKeteranganidbigint PKmember_idFK → membersspecialtystring nullablee.g. "Endgame", "Rack Management", "Opening"biotext nullablehourly_ratedecimal(10,2) nullableTarif per sesitimestamps
Model: CoachDetail. Relasi: Member hasOne CoachDetail, CoachDetail belongsTo Member.
Di Filament MemberForm: saat is_coach di-toggle true, tampilkan section CoachDetail secara kondisional.
4.4 Tabel coaching_sessions (baru)
FieldTipeKeteranganidbigint PKcoach_idFK → membersMember dengan is_coach = truemember_idFK → membersCoacheesession_datedateduration_minutesintegernotestext nullableCatatan sesi dari coachinvoice_idFK → invoices nullableUntuk billingstatusenumscheduled, completed, cancelledtimestamps
Model: CoachingSession dengan relasi ke Member (sebagai coach & coachee) dan Invoice.
Filament Resource: CRUD penuh. Filter by coach, by member, by status, by date range.
4.5 Model Tournament (baru)
Tabel: tournaments
FieldTipeKeteranganidbigint PKnamestringNama turnamendatedatelocationstring nullableformatenumswiss, round_robin, king_of_the_hilldivision_idFK → divisions nullableNull = semua divisiisf_ratedbooleanDefault truestatusenumupcoming, ongoing, completednotestext nullabletimestamps
Filament Resource: CRUD penuh. Status diupdate manual oleh admin.
4.6 Tabel tournament_participants (pivot baru)
FieldTipeKeteranganidbigint PKtournament_idFK → tournamentsmember_idFK → membersdivision_idFK → divisionsDivisi member saat turnamen berlangsungrating_beforeinteger nullableAuto-filled dari member.current_rating saat peserta ditambahkanrating_afterinteger nullableDiisi setelah turnamen selesaifinal_rankinteger nullablePosisi akhirtotal_winsinteger nullabletotal_spreadinteger nullableSelisih skor akumulatif, khas scrabblepointsdecimal nullablePoin akhir turnamentimestamps
Model: TournamentParticipant. Relasi: Tournament hasMany TournamentParticipant, Member hasMany TournamentParticipant.
Logic: Saat tournament di-mark completed dan rating_after diisi, update member.current_rating secara otomatis — ini akan men-trigger MemberObserver untuk re-assign divisi.
4.7 Update Plans
Tidak ada perubahan struktur tabel. Update label dan seeder saja:

Subscription plans: Bulanan (30 hari), 3 Bulan (90 hari), 6 Bulan (180 hari), Tahunan (365 hari)
Coaching plans: Per Sesi, Paket 4 Sesi, Paket 8 Sesi


5. Urutan Eksekusi yang Disarankan
Fase 1 (lakukan sebelum setup DB)

Lang files (en → fr → ar) — termasuk hapus fields.goal, fields.health_issues, placeholders.health_issues, update options.goal, dan ganti {gym_name} → {club_name} di token hints
Migrations — hapus kolom di members dan enquiries
Models — hapus dari $fillable dan PHPDoc (Member, Enquiry)
Filament Forms & Infolists (MemberForm, MemberInfolist, EnquiryForm, EnquiryInfolist)
CreateMember.php — hapus 'goal' dari enquiry prefill
API Schemas (MemberSchema, EnquirySchema)
Factories (MemberFactory, EnquiryFactory)
Rename settings keys gym_* → club_* — lakukan semua file sekaligus dalam satu commit: Settings.php, InvoiceDocument.php, InvoiceEmailService.php, document.blade.php, settingsData.json.example
Rebranding Gymie → INASA — semua file sekaligus: AdminPanelProvider, AppServiceProvider, InvoiceIssuedMail, InvoicePaymentReceiptMail, InvoiceEmailService (method names + token key + fallback), document.blade.php, layout.blade.php
Artisan command signatures + routes/console.php
Test files — update semua key di 7 test files

Fase 2 (setelah DB sudah berjalan)

Migration create_divisions_table
Migration add_scrabble_fields_to_members_table
Migration create_coach_details_table
Migration create_coaching_sessions_table
Migration create_tournaments_table
Migration create_tournament_participants_table
Models + Relationships + MemberObserver
Filament Resources: Division → Member update → CoachingSession → Tournament


6. Hal yang Tidak Diubah

Struktur Subscription, Invoice, InvoiceTransaction, Expense, Enquiry, FollowUp, User — tetap as-is
Role & permission system (Filament Shield) — tidak disentuh
API routes — tidak disentuh kecuali field yang dihapus di MemberSchema dan EnquirySchema
